import React, {useEffect, useState} from 'react';
import Breadcrumb from '../elements/Breadcrumb';
import ServiceStep from './steps/ServiceStep';
import ConsumptionDetailStep from './steps/ConsumptionDetailStep';
import GeneralInfoStep from './steps/GeneralInfoStep';
import SubscriptionStep from './steps/SubscriptionStep';
import PaymentStep from './steps/PaymentStep';
import GlobalLoaderProps from "@/Components/elements/GlobalLoaderProps";
import {usePage} from "@inertiajs/react";
import {PageProps} from "@/types";
import {getLocalStorage, isInt, setLocalStorage, updateState} from "@/utils/helper";

interface Supplier {
    name: string;
    powwr_id: string;
    logo?: string;
    logo_url?: string;
}

interface Props {
    suppliers: Supplier[];
}

export default function Scgs({suppliers}: Props) {
    const deal = usePage<PageProps>().props.deal;
    const user = usePage<PageProps>().props.user;
    const pricechange = usePage<PageProps>().props.pricechange;

    const [activeStep, setActiveStep] = useState<number>(1);
    const [isItLoading, setIsItLoading] = useState(false);
    const [apiError, setApiError] = useState<any>(null);
    const [apiSuccess, setApiSuccess] = useState<any>(null);

    const [offerData, setOfferData] = useState({
        utilityType: 'electric',
        meterNumber: '',
        meterType: '',
        quoteReference: null,
        currentSupplierName: '',
        newSupplierName: '',
        newSupplierId: '',
        contractRenewalDate: '',
        contractEndDate: '',
        newContractEndDate: '',
        curentSupplier: '',
        measurementClass: '',
        halfHourly: '',
        consumption: {
            amount: '',
            day: '',
            night: '',
            wend: '',
            kva: '',
            kvarh: '',
        },
        postCode: '',
        renewal: null,
        cot: null,
        cotDate: null,
        outOfContract: null,
        uplift: null,
        standingChargeUplift: null,
        paymentMethod: 'Monthly Direct Debit',
        sortByCommission: null,
        businessType: '',
        MPANTop: '',
    });

    const [dealData, setDealData] = useState<any>({
        id: '',
        user_id: user?.id ?? '',
        dealId: '',
        envelopeId: '',
        utilityType: 'electric',
        supplierId: '',
        step: '',
        tab: '',
        customer: {
            title: user?.gender == 'female' ? 'Miss' : 'Mr',
            jobTitle: "Director",
            name: user?.name ?? '',
            firstName: user?.first_name ?? '',
            lastName: user?.last_name ?? '',
            email: user?.email,
            mobile: user?.phone ?? '',
            landline: '',
            buildingName: '',
            subBuildingName: '',
            buildingNumber: '',
            thoroughfareName: '',
            county: '',
            postTown: '',
            postcode: '',
            poBox: '',
            dateOfBirth: '',
            moveInDate: '',
            previousAddress: '',
            contactPreference: ''
        },
        company: {
            name: '',
            type: '',
            number: '',
            buildingName: '',
            subBuildingName: '',
            buildingNumber: '',
            thoroughfareName: '',
            county: '',
            postTown: '',
            postcode: '',
            poBox: '',
            isMicroBusiness: false,
            dateOfIncorporation: '',
            sicCodes: '',
            partner1: {
                firstName: '',
                lastName: '',
                dob: '',
                buildingName: '',
                subBuildingName: '',
                buildingNumber: '',
                thoroughfareName: '',
                county: '',
                postTown: '',
                postcode: '',
                poBox: '',
            },
            partner2: {
                firstName: '',
                lastName: '',
                dob: '',
                buildingName: '',
                subBuildingName: '',
                buildingNumber: '',
                thoroughfareName: '',
                county: '',
                postTown: '',
                postcode: '',
                poBox: '',
            }
        },
        site: {
            name: '',
            address: '',
            buildingName: '',
            subBuildingName: '',
            buildingNumber: '',
            thoroughfareName: '',
            dependentThoroughfareName: '',
            county: '',
            postTown: '',
            postcode: '',
            poBox: '',
        },
        smeDetails: {
            meterNumber: '',
            mpanTop: '',
            meterSerialNumber: '',
            standingChargeType: '',
            IsRenewable: '',
            unit: '',
        },
        contract: {
            currentSupplier: '',
            currentSupplierName: '',
            newSupplier: '',
            newSupplierName: '',
            currentEndDate: '',
            startDate: '',
            endDate: '',
            isNewConnection: true,
            brokeragePaymentTerm: '',
            pricebook: '',
        },
        billingAddress: {
            buildingName: '',
            subBuildingName: '',
            buildingNumber: '',
            thoroughfareName: '',
            county: '',
            postTown: '',
            postcode: '',
            poBox: '',
            number: '',
        },
        paymentDetail: {
            method: 'FixedDirectDebit',
            paymentTermInDays: '',
            directDebitDayOfMonth: '',
            fixedDirectDebitPaymentAmount: '',
            useExistingBankDetails: '',
        },
        bankDetails: {
            name: '',
            branchName: '',
            sortCode: '',
            accountNumber: '',
            accountName: '',
            requires2signatures: '',
        },
        bankAddress: {
            organisationName: '',
            departmentName: '',
            subBuildingName: '',
            buildingName: '',
            buildingNumber: '',
            dependentThoroughfareName: '',
            thoroughfareName: '',
            doubleDependentLocality: '',
            dependentLocality: '',
            postTown: '',
            county: '',
            postcode: '',
            poBox: ''
        },
        rates: {
            rateType: '',
            amount: '',
            uplift: '',
            unit: ''
        },
        usage: {
            unit: 0,
            day: 0,
            night: 0,
            weekend: 0,
            reactive: 0,
            capacity: 0
        },
        consents: {
            authorised: '',
            terms: '',
            data: '',
            communication: '',
        },
        quoteDetails: {}
    });

    const breadcrumbItems = [
        {label: 'Energy', active: activeStep === 1},
        {label: 'Business Information', active: activeStep === 2 || activeStep === 3},
        {label: 'Quotation', active: activeStep === 4},
        {label: 'Payment', active: activeStep === 5},
    ];

    function setOfferValue(name: any, value: any) {
        if (typeof name == 'object' && Object.keys(name).length) {
            setOfferData((prevData: any) => ({
                ...prevData, ...name
            }));
        } else {
            if (name.indexOf('.') > 0) {
                var [p1, p2] = name.split('.');
                setOfferData((prevData: any) => ({
                    ...prevData, [p1]: {...prevData[p1], [p2]: value}
                }));
            } else {
                setOfferData((prevData: any) => ({...prevData, [name]: value}));
            }
        }
    }

    function setDealValue(name: string, value: any) {
        if (typeof value == 'object' && Object.keys(value).length) {
            setDealData((prevData: any) => ({
                ...prevData, [name]: {...prevData[name], ...value}
            }));
        } else {
            if (name.indexOf('.') > 0) {
                let parts = name.split('.');
                console.log(parts.length)
                var [p1, p2, p3] = parts;
                if (parts.length == 3) {
                    setDealData((prevData: any) => ({
                        ...prevData, [p1]: {...prevData[p1], [p2]: {...prevData[p1][p2], [p3]: value}}
                    }));
                } else {
                    setDealData((prevData: any) => ({
                        ...prevData, [p1]: {...prevData[p1], [p2]: value}
                    }));
                }
            } else {
                setDealData((prevData: any) => ({...prevData, [name]: value}));
            }
        }
    }

    function setCompanyAddress(company: any) {
        if (typeof company == 'string') {
            company = {title: company};
        }
        let premises = company?.address?.premises ?? '';
        let address_line_1 = company?.address?.address_line_1 ?? '';
        let address_line_2 = company?.address?.address_line_2 ?? '';
        if (!premises) {
            if (address_line_1) {
                if (address_line_2) {
                    premises = address_line_1;
                    address_line_1 = address_line_2;
                    address_line_2 = '';
                }else{
                    if (address_line_1.match(/^\d/)) {
                        let num = address_line_1.substring(0, address_line_1.indexOf(' '));
                        if (isInt(num)) {
                            premises = num;
                        }
                        if (num.endsWith('st') || num.endsWith('nd') || num.endsWith('rd') || num.endsWith('th')) {
                            premises = address_line_1.split(' ').splice(0, 2).join(' ');

                        }
                        if (premises.length) {
                            address_line_1 = address_line_1.slice(premises.length + 1);
                        }
                    }
                }
            }

            console.log(premises,address_line_1,address_line_2);
        }

        setDealValue('company', {
            name: company.title,
            number: company?.number ?? '',
            buildingNumber: premises,
            buildingName: address_line_1,
            subBuildingName: address_line_2,
            postTown: company?.address?.locality ?? company?.address?.address_line_2 ?? '',
            county: company?.address?.region ?? company?.address?.locality ?? '',
            postcode: company?.address?.postal_code ?? '',
            dateOfIncorporation: company?.date_creation ?? '',
            sicCodes: company?.sic_codes ?? '',
        });
    }

    const scrollToTop = () => {
        window.scrollTo({top: 0, behavior: 'smooth'});
    };

    const handleNextClick = () => {
        const i = Math.min(activeStep + 1, 5);
        setActiveStep(i);
        scrollToTop();
        setDealValue('step', i);
        setDealValue('tab', '');
    };

    const handleBackClick = () => {
        const i = Math.max(activeStep - 1, 1);
        setActiveStep(i);
        setDealValue('step', i);
        setDealValue('tab', '');
    };

    const saveDeal = async (calback?: any, failed?: any) => {
        //return calback ? calback() : false;
        setIsItLoading(true)
        const requestOptions = {
            method: 'POST',
            headers: {'Content-Type': 'application/json', Accept: 'application/json'},
            body: JSON.stringify({...dealData, step: activeStep})
        };
        const url = `/api/powwr/save-data`;
        fetch(url, requestOptions)
            .then(response => response.json())
            .then(resp => {
                if (resp.data.id && dealData.id != resp.data.id) {
                    setDealValue('id', resp.data.id);
                }
                if (resp.data.dealId && dealData.dealId != resp.data.dealId) {
                    setDealValue('dealId', resp.data.dealId);
                }
                if (resp.data.envelopeId && dealData.envelopeId != resp.data.envelopeId) {
                    setDealValue('envelopeId', resp.data.envelopeId);
                }
                if (resp.message) {
                    _message(resp.message, resp.success, resp.errors);
                }
                if (resp.success && typeof calback == "function") {
                    calback(resp)
                }
                setIsItLoading(false)
            })
            .catch(error => {
                setIsItLoading(false)
                if (typeof failed == "function") {
                    failed(error);
                }
                if (error) {
                    _message(error, false);
                }
                console.error('Save data error:', error);
            });
    };

    function _message(message: any, status?: boolean, errors?: any) {
        if (message) {
            if (status) {
                setApiSuccess(message);
            } else {
                setApiError({message: message, errors: errors});
            }
        } else {
            setApiSuccess(null);
            setApiError(null);
        }
    }

    function getSupplierByName(name: string) {
        let supplier = suppliers.filter(
            (item) => item.name === name
        )
        return supplier[0]?.powwr_id ?? '';
    }

    function getSupplierNameById(id: string) {
        let supplier = suppliers.filter(
            (item) => item.powwr_id === id
        )
        return supplier[0]?.name ?? '';
    }

    useEffect(() => {
        let ignore = false;
        if (!ignore) {
            let _deal: any = deal;
            if (!_deal) {
                _deal = getLocalStorage('deal');
            }
            if (_deal) {
                if (_deal?.step) {
                    setActiveStep(Math.min(parseInt(_deal.step), 5));
                }
                setDealData((p: any) => (updateState(p, _deal)));
                setOfferData((p: any) => (updateState(p, {
                    utilityType: _deal.utilityType,
                    meterNumber: _deal.smeDetails.meterNumber,
                    MPANTop: _deal.smeDetails.mpanTop ?? '',
                    postCode: _deal.site.postcode,
                    businessType: _deal.company.type,
                    contractEndDate: _deal.contract.currentEndDate,
                    currentSupplierName: getSupplierNameById(_deal.supplierId),
                    consumption: {
                        amount: _deal?.usage?.unit,
                        day: _deal?.usage?.day,
                    }
                })));
            }
        }
        return () => {
            ignore = true;
        }
    }, []);

    useEffect(() => {
        console.log(dealData);
        if (dealData.site.postcode) {
            setLocalStorage('deal', dealData, (60 * 15));
        }
    }, [dealData]);
    return (
        <div className={`text-center ${activeStep === 1 && ('bg-blue-to-light2 min-h-screen')} py-5`}>
            <div className="container relative">
                <Breadcrumb items={breadcrumbItems} activeCount={activeStep}/>
                {activeStep === 1 && (
                    <ServiceStep
                        onNext={handleNextClick}
                        offerData={offerData}
                        setOfferData={setOfferValue}
                        dealData={dealData}
                        setDealData={setDealValue}
                    />)}
                {activeStep === 3 && (
                    <ConsumptionDetailStep
                        suppliers={suppliers}
                        pricechange={pricechange}
                        onNext={handleNextClick}
                        offerData={offerData}
                        setOfferData={setOfferValue}
                        dealData={dealData}
                        setDealData={setDealValue}
                        saveDeal={saveDeal}
                    />
                )}
                {activeStep === 2 && (
                    <GeneralInfoStep
                        onNext={handleNextClick}
                        offerData={offerData}
                        setOfferData={setOfferValue}
                        dealData={dealData}
                        setDealData={setDealValue}
                        saveDeal={saveDeal}
                        setCompanyAddress={setCompanyAddress}
                    />
                )}
                {activeStep === 4 && <SubscriptionStep
                    onNext={handleNextClick}
                    offerData={offerData}
                    setOfferData={setOfferValue}
                    dealData={dealData}
                    setDealData={setDealValue}
                    suppliers={suppliers}
                    pricechange={pricechange}
                    saveDeal={saveDeal}
                    isLoading={isItLoading}
                    setIsLoading={setIsItLoading}
                />}
                {activeStep === 5 && <PaymentStep
                    onNext={handleNextClick}
                    offerData={offerData}
                    setOfferData={setOfferValue}
                    dealData={dealData}
                    setDealData={setDealValue}
                    saveDeal={saveDeal}
                    setCompanyAddress={setCompanyAddress}
                    handleBackClick={handleBackClick}
                />}

                {activeStep > 1 && activeStep <= 4 && <div className={`mt-2`}>
                    <button className='mt-4 mb-2 bg-slate-100 border px-5 py-2' onClick={handleBackClick}
                            disabled={activeStep === 1}>
                        Back
                    </button>
                </div>}

                {/*{(apiSuccess || apiError) && (
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
                )}*/}
                <GlobalLoaderProps isLoading={isItLoading}/>
            </div>
        </div>
    );
}
