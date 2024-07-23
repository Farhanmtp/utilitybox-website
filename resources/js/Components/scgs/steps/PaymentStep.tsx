import React, {useEffect, useState} from 'react';
import {SaveAndContinue} from '@/Components/elements/SaveAndContinue';
import ContactDetails from "@/Components/scgs/steps/paymentTabs/ContactDetails";
import BusinessDetails from "@/Components/scgs/steps/paymentTabs/BusinessDetails";
import SupplyDetails from "@/Components/scgs/steps/paymentTabs/SupplyDetails";
import BillingDetails from "@/Components/scgs/steps/paymentTabs/BillingDetails";
import PaymentDetails from "@/Components/scgs/steps/paymentTabs/PaymentDetails";
import Finalize from "@/Components/scgs/steps/paymentTabs/Finalize";
import {Image, Modal} from "react-bootstrap";
import {validateEmail, validateMobile} from "@/utils/helper";
import moment from "moment/moment";
import {Inertia} from "@inertiajs/inertia";

interface Step {
    name?: string;
    title: string;
    required_fields?: string[]; // Fields for the step
}

const steps: Step[] = [
    {
        name: 'contact_details',
        title: 'Your Contact Details',
        required_fields: ['customer.title', 'customer.firstName', 'customer.email', 'customer.mobile'],
    },
    {
        name: 'business_details',
        title: 'Your Business Details',
        required_fields: ['company.name', 'company.buildingNumber', 'company.buildingName', 'company.county', 'company.postcode'],
    },
    {
        name: 'supply_details',
        title: 'Your Supply Details',
        required_fields: ['site.name', 'site.buildingNumber', 'site.buildingName', 'site.county', 'site.postcode'],
    },
    {
        name: 'billing_details',
        title: 'Your Billing Preferences',
        required_fields: ['billingAddress.buildingNumber', 'billingAddress.buildingName', 'billingAddress.county', 'billingAddress.postcode'],
    },
    {
        name: 'payment_details',
        title: 'Your Payment Details',
        required_fields: [],
    },
    {
        name: 'finalize',
        title: 'Your Obligations & Authority',
        required_fields: ['finalize.consent1', 'finalize.consent2', 'finalize.consent3']
    }
];

interface PaymentStepProps {
    onNext: () => void
    offerData?: any,
    setOfferData: (name: string, value: any) => void,
    dealData?: any,
    setDealData: (name: string, value: any) => void,
    saveDeal: (calback?: any, failed?: any) => void
    setCompanyAddress: (company: any) => void;
    handleBackClick: () => void;
}

export default function PaymentStep({onNext, offerData, dealData, setDealData, setOfferData, saveDeal, setCompanyAddress, handleBackClick}: PaymentStepProps) {
    const [skipPayment, setSkipPayment] = useState<number>(0);
    const [addressPreference, setAddressPreference] = useState<string>('');
    const [currentTab, setCurrentTab] = useState<number>(0);
    const [pendingStep, setPendingStep] = useState<number>(0);
    const [apiError, setApiError] = useState<any>(null);
    const [apiSuccess, setApiSuccess] = useState<any>(null);
    const [validationError, setValidationError] = useState('');

    const [showConfirmModal, setShowConfirmModal] = useState(false);

    const handleCloseModal = () => {
        localStorage.removeItem('deal');
        Inertia.get(route('contract'));
        setShowConfirmModal(false);
    };

    const submitQuote = async () => {
        if (!dealData.dealId) {
            alert('Save deal before submitting quote request.');
            return false;
        } else {
            const requestOptions = {
                method: 'POST',
                headers: {'Content-Type': 'application/json', Accept: 'application/json'},
                body: JSON.stringify({
                    dealId: dealData.dealId,
                    utilityType: dealData.utilityType, // Change this to the appropriate value
                    quotationType: 'custom', // Change this to the appropriate value
                })
            };
            fetch('/api/powwr/submit-quote', requestOptions)
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        _message(data.message, data.success, data.errors);
                    }
                })
                .catch(error => {
                    console.error('Create deal error:', error);
                });
        }
    };

    function _message(message: any, status?: boolean, errors?: any) {
        if (message) {
            if (status) {
                setApiSuccess(message);
                setShowConfirmModal(true);
            } else {
                setApiError({message: message, errors: errors});
            }
        } else {
            setApiSuccess(null);
            setApiError(null);
        }
    }

    function preferenceClickHandler(pref: string) {
        setAddressPreference(pref);
        if (pref) {
            setDealData('billingAddress', {
                buildingNumber: dealData[pref].buildingNumber,
                buildingName: dealData[pref].buildingName,
                thoroughfareName: dealData[pref].thoroughfareName,
                county: dealData[pref].county,
                postTown: dealData[pref].postTown,
                postcode: dealData[pref].postcode,
                poBox: dealData[pref].poBox,
            });
        }
    }

    const handleStepClick = (stepIndex: number) => {
        if (stepIndex < currentTab || pendingStep > currentTab) {
            setCurrentTab(stepIndex);
        }
        return;
    };

    const nextTabHandle = (index: number) => {
        setApiError(null);
        setApiSuccess(null);
        if (validateForm(index)) {
            saveDeal(function (resp: any) {
                index = index + 1;
                setPendingStep(index);
                setCurrentTab(Math.min(steps.length - 1, index));
            })
        }
    };

    const finalize = (index: number) => {
        if (validateForm(index)) {
            setDealData('tab', 'finalize');
            saveDeal(function (resp: any) {
                if (resp.data.envelopeId) {

                }
                setShowConfirmModal(true);
            })
        } else {
            //alert('Fill all required fields with valid data.');
        }
    }

    const validateForm = (index: number) => {
        const requiredFields = steps[index].required_fields ?? [];
        var count = 0;
        for (const field of requiredFields) {
            var elm: any = document.getElementsByName(field);
            if (elm.length) {
                let _field = elm.item(0);
                if (!validateField(_field)) {
                    count++;
                }
            }
        }
        // @ts-ignore
        let fields = document.getElementById('tab' + index).querySelectorAll("[required]");
        fields.forEach((field: any) => {
            if (field.required && requiredFields.indexOf(field.name) == -1 && !validateField(field)) {
                count++;
            }
        });
        if (count) {
            alert('Fill all required fields.');
        }
        return !count;
    }

    const validateField = (field: any, value: any = null) => {
        let _type = field.type, _name = field.name, _valid = true;
        value = value ?? field.value ?? getData(_name);

        if (_type == 'checkbox') {
            _valid = field.checked;
        } else if (!value.length || (['number', 'string'].indexOf(typeof value) != -1 && value.replace(/ /g, "") == '')) {
            _valid = false;
        } else {
            if ((_name == 'customer.mobile' && !validateMobile(value))) {
                _valid = false;
            } else if (_type == 'email' && !validateEmail(value)) {
                _valid = false;
            }
        }

        if (_valid) {
            field.classList.remove('error');
        } else {
            field.classList.add('error');
        }
        return _valid;
    }


    function setData(event: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) {
        event.target.classList.remove('error');
        setApiError(null);
        setApiSuccess(null);

        var {name, value, type} = event.target;
        var fieldValue: any = value;

        if (name == 'customer.mobile') {
            fieldValue = fieldValue.replace(/[a-zA-Z]/g, '').slice(0, 11);
        }

        if (type === 'checkbox') {
            fieldValue = (event.target as HTMLInputElement).checked ? fieldValue : '';
        }

        setDealData(name, fieldValue)

        if (event.target.required || steps[currentTab].required_fields?.indexOf(name) != -1) {
            validateField(event.target, fieldValue);
        }

        if ((type == 'email' && !validateEmail(fieldValue))
            || (name == 'customer.phone' && !validateMobile(fieldValue))
        ) {
            event.target.classList.add('error');
        }

        setValidationError('');
        setPendingStep(currentTab);
    }

    function getData(name: string) {
        var value;
        if (name.indexOf('.') > 0) {
            var [p1, p2] = name.split('.');
            var parts = name.split('.');
            value = dealData[p1][p2];
        } else {
            value = dealData[name];
        }
        return value ?? '';
    }


    function isNewCompany() {
        let comCreated: any = dealData.company.dateOfIncorporation;
        if (comCreated && moment(comCreated).isAfter(moment().subtract(36, 'months'))) {
            return true;
        }
        return false;
    }

    function dobRequired(check_value = true) {
        if (dealData?.customer?.dateOfBirth && check_value) {
            return false;
        }

        if (dealData?.company?.type !== 'Limited' || isNewCompany()) {
            return true;
        }
        return false;
    }

    useEffect(() => {
        setPendingStep(0);
        setCurrentTab(0);
    }, [offerData]);

    useEffect(() => {
        setDealData('tab', steps[currentTab].name);
        //saveDeal();
    }, [currentTab]);

    useEffect(() => {
        let ignore = false;
        if (!ignore && dealData?.tab) {
            steps.forEach((item, index) => {
                if (item.name == dealData.tab) {
                    setPendingStep(index);
                    setCurrentTab(index);
                }
            })
        }
        return () => {
            ignore = true;
        }
    }, []);

    return (
        <div className="container">
            <div className='xl:flex block '>
                <div className='xl:w-3/12 mb-4 border-b-2'>
                    <ul className='xl:block md:flex block'>
                        {steps.map((step, index) => (
                            <li
                                className={`rounded-sm text-left md:text-center lg:text-left px-4 py-[12px] text-semibold ${
                                    currentTab === index ? 'bg-blue text-white ' : 'text-blue'
                                }`}
                                key={index}
                                style={{cursor: 'pointer'}}
                                onClick={() => handleStepClick(index)}
                            >
                                {step.title}
                                {index < currentTab || pendingStep > index ? (
                                    <Image className="mr-3 inline-block float-right md:float-none lg:float-right" src="/images/icons/check.png" width={15}/>
                                ) : (
                                    <Image className="mr-3 inline-block float-right md:float-none lg:float-right" src="/images/icons/check.png" style={{filter: "grayscale(1)", opacity: 0}} width={15}/>
                                )}

                            </li>))}
                    </ul>
                </div>
                <div className="text-left px-md-5 w-9/12">
                    {steps.map((step, index) => (currentTab == index &&
                        <div key={index} id={`tab${index}`}>
                            {step.name == 'contact_details' ? (
                                <ContactDetails isNewCompany={isNewCompany} dobRequired={dobRequired} setData={setData} dealData={dealData} setDealData={setDealData}/>
                            ) : step.name == 'business_details' ? (
                                <BusinessDetails dobRequired={dobRequired} setData={setData} setCompanyAddress={setCompanyAddress} setOfferData={setOfferData} setDealData={setDealData} dealData={dealData}/>
                            ) : step.name == 'supply_details' ? (
                                <SupplyDetails setData={setData} dealData={dealData}/>
                            ) : step.name == 'billing_details' ? (
                                <BillingDetails
                                    setData={setData}
                                    dealData={dealData}
                                    preferenceClickHandler={preferenceClickHandler}
                                    addressPreference={addressPreference}
                                />
                            ) : step.name == 'payment_details' ? (
                                <PaymentDetails
                                    setData={setData}
                                    dealData={dealData}
                                    skipPayment={skipPayment}
                                    setSkipPayment={setSkipPayment}
                                />

                            ) : (
                                <Finalize setData={setData} dealData={dealData}/>
                            )}

                            <div className="mt-4 mb-2 text-center ">
                                {validationError && <div className="mt-0 mb-2 text-center">
                                    <div className="p-3 py-1 border border-danger text-danger inline-block "> {validationError}</div>
                                </div>}
                                {step.name == 'finalize' ? (
                                    <div className="inline-block " onClick={() => finalize(index)}>
                                        <SaveAndContinue title={'Save & ESign Contract'}></SaveAndContinue>
                                    </div>
                                ) : (
                                    <div className="inline-block " onClick={() => nextTabHandle(index)}>
                                        <SaveAndContinue title={'Save & Continue'}></SaveAndContinue>
                                    </div>
                                )}
                                {/**/}
                            </div>
                            <p className='text-semibold mt-4 text-slate-500 text-center'><i className="fa-solid fa-lock text-blue mr-2"></i>Your Personal Data is Protected & Secured</p>

                            <div className="text-center">
                                <button className='mt-4 mb-2 bg-slate-100 border px-5 py-2' onClick={handleBackClick}>
                                    Back
                                </button>
                            </div>
                        </div>
                    ))}

                    {steps[currentTab].name == 'create_deal' && (
                        <>
                            {apiError && (
                                <div className={'mt-5 mb-3 p-2 border border-red-500 text-left text-red-500'}>
                                    <span className={'font-bold'}>{apiError.message}</span>
                                    {apiError.errors?.length && (
                                        <ul className={'ml-2'}>
                                            {apiError.errors.map((error: any, index: number) => (
                                                <li key={index}>• {error}</li>
                                            ))}
                                        </ul>
                                    )}
                                </div>
                            )}
                            {apiSuccess && (
                                <div className={'mt-5 mb-3 p-2 border border-green-500 text-left font-bold text-gree-500'}>{apiSuccess}</div>
                            )}
                        </>
                    )}

                    {/* <button onClick={handleContinue}>Continue</button> */}

                    {/* docusign success  */}
                    <Modal show={showConfirmModal} onHide={handleCloseModal} centered>
                        <Modal.Body className="p-5 text-center">
                            <div className="icon-container self-center">
                                <div className="mail-icon">
                                    <i className="fas fa-check"></i>
                                </div>
                            </div>
                            <h2 className='mb-4'>Your <b>Contract</b> has been successfully submitted!</h2>
                            <p>A docusign will be sent shortly, to your email <span className='text-blue underline font-medium'>{dealData.customer.email}</span>. <br/>
                                Kindly ESign that contract and complete the journey.</p>
                            <button type="button" onClick={handleCloseModal} className="mt-4 mb-2 bg-slate-100 border px-5 py-2">OK</button>
                        </Modal.Body>
                    </Modal>
                </div>
            </div>
        </div>
    );
}
