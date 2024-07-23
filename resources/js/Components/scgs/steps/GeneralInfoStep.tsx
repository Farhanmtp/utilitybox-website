import React, {useEffect, useState} from 'react';
import {BlackButton} from '../../elements/BlackButton';
import {validateEmail, validateMobile, validatePhone} from "@/utils/helper";
import {usePage} from '@inertiajs/react';
import {PageProps} from '@/types';
import {useGlobalState} from '@/Layouts/elements/PopupContext';

interface GeneralInfoStepProps {
    onNext: () => void;
    offerData?: any,
    setOfferData: (name: string, value: any) => void,
    dealData?: any,
    setDealData: (name: string, value: any) => void,
    saveDeal: (calback: any, failed?: any) => void,
    setCompanyAddress: (company: any) => void;
}

type Address = {
    Key: string;
    Value: string;
    AddressAsLine: string;
}

const GeneralInfoStep: React.FC<GeneralInfoStepProps> = ({
                                                             onNext,
                                                             offerData,
                                                             saveDeal,
                                                             setOfferData,
                                                             dealData,
                                                             setDealData,
                                                             setCompanyAddress
                                                         }) => {
    const [mobileError, setMobileError] = useState('');
    const [phoneError, setPhoneError] = useState('');
    const [emailError, setEmailError] = useState('');
    const [companies, setCompanies] = useState([]);
    const [isLoading, setIsLoading] = useState(false);
    const [showDropdown, setShowDropdown] = useState(false);
    const [companyName, setCompanyName] = useState('');

    const [isEmailValid, setIsEmailValid] = useState(false);
    const isLoggedIn = usePage<PageProps>().props.loggedin;

    const [isLoginValid, setIsLoginValid] = useState(true);

    const {showModal, setShowModal} = useGlobalState();

    const handleCompanyTypeClick = (event: React.ChangeEvent<HTMLInputElement>) => {
        let value = event.target.value;
        setOfferData('businessType', value);
        setDealData('company', {
            name: '',
            number: '',
            type: value,
            buildingNumber: '',
            buildingName: '',
            subBuildingName: '',
            thoroughfareName: '',
            postTown: '',
            county: '',
            postcode: '',
            dateOfIncorporation: '',
            sicCodes: '',
        });
    };

    const handleMobileNumberChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const mobile = e.target.value.replace(/[a-zA-Z]/g, '').slice(0, 11);
        setDealData('customer.mobile', mobile);

        if (!validateMobile(mobile)) {
            const regExp = /^(07)\d*$/;
            if (!regExp.test(mobile)) {
                setMobileError('Mobile number must start with 07');
            } else {
                setMobileError('Please enter a valid phone number');
            }
        } else {
            setMobileError('');
        }
    };

    const handlePhoneNumberChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const phone1 = e.target.value.replace(/[a-zA-Z]/g, '').slice(0, 11);
        setDealData('customer.landline', phone1);
        if (phone1 && !validatePhone(phone1)) {
            const regExp = /^(0)+(?!7).*$/;
            if (!/^(0).*$/.test(phone1)) {
                setPhoneError('Landline number must start with 0');
            } else if (!regExp.test(phone1)) {
                setPhoneError('Phone number should not start with ' + phone1.slice(0, 2));
            } else {
                setPhoneError('Please enter a valid Landline number');
            }

        } else {
            setPhoneError('');
        }
    };

    const handleEmailChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const newEmail = e.target.value;
        if (!validateEmail(newEmail)) {
            setEmailError('Please enter a valid email address');
        } else {
            setEmailError('');
        }
        validateEmail(newEmail);
        setDealData('customer.email', newEmail);
    };

    const handleNextClick = () => {
        if (validateStep()) {
            if (isLoggedIn == true) {
                onNext();
            } else {
                // Validate email with an API request
                fetch('/api/validate/email', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({email: dealData.customer.email}),
                }).then((response) => response.json()).then((data) => {
                    if (data.success && data.data.user !== null) {
                        // User is not null, proceed with onNext()
                        setIsEmailValid(true);
                        setShowModal(true);
                        setIsLoginValid(false);
                    } else {
                        setIsEmailValid(true);
                        onNext();
                    }
                }).catch((error) => {
                    console.error('Error validating email:', error);
                });
            }
        } else {
            alert("Fill all required fields with valid data.")
        }
    };


    function validateStep() {

        if (!dealData.company.type || !dealData.customer.name || !dealData.customer.mobile || !dealData.customer.email) {
            return false;
        }
        if (dealData.company.type != 'SoleTrader' && !dealData.company.name) {
            return false;
        }

        if ((dealData.company.name && dealData.company.name?.length < 3) || (dealData.customer.name && dealData.customer.name?.length < 3)) {
            return false;
        }

        if (!validateEmail(dealData.customer.email) || !validateMobile(dealData.customer.mobile) || (dealData.customer.landline && !validatePhone(dealData.customer.landline))) {
            return false;
        }

        return true;
    }

    function companyClickHandler(company: any) {
        setCompanyAddress(company);
        setShowDropdown(false);
    }

    const searchCompany = (search: string) => {
        setCompanies([]);
        let type = dealData.company.type;
        if (type == "Limited") {
            type = 'ltd';
        }
        if (type == "LimitedLiabilityPartnership") {
            type = 'llp';
        }
        const requestOptions = {
            method: 'POST',
            headers: {'Content-Type': 'application/json', Accept: 'application/json'},
            body: JSON.stringify({
                q: search,
                type: type,
            })
        };
        if (search) {
            setIsLoading(true);
            fetch('/api/powwr/companies', requestOptions)
                .then(response => response.json())
                .then(resp => {
                    let _companies = resp.data || [];
                    setCompanies(_companies);
                    setShowDropdown(true);
                    setIsLoading(false);
                })
                .catch(error => {
                    setIsLoading(false);
                });
        }
    };

    useEffect(() => {
        const timer = setTimeout(() => {
            searchCompany(companyName);
        }, 1000);
        return () => clearTimeout(timer);
    }, [companyName]);

    return (
        <>
            <div className="mb-3 md:mb-5 type-cmny">
                <h3 className="mb-4">What is your <b className="text-semibold">company type?</b>
                    {/* <Tooltip>add content hare</Tooltip> */}
                </h3>
                <label className={`btn-radio ${dealData.company.type === 'Limited' ? 'active' : ''}`}
                       htmlFor="privateLimited">
                    <input
                        id="privateLimited"
                        type="radio"
                        value="Limited"
                        checked={dealData.company.type === 'Limited'}
                        onChange={handleCompanyTypeClick}
                        className="hidden"
                    />
                    Private Limited (LTD)
                </label>

                <label
                    className={`btn-radio ${dealData.company.type === 'LimitedLiabilityPartnership' ? 'active' : ''}`}
                    htmlFor="llp">
                    <input
                        id="llp"
                        type="radio"
                        value="LimitedLiabilityPartnership"
                        checked={dealData.company.type === 'LimitedLiabilityPartnership'}
                        onChange={handleCompanyTypeClick}
                        className="hidden"
                    />
                    LLP
                </label>

                <label className={`btn-radio ${dealData.company.type === 'SoleTrader' ? 'active' : ''}`}
                       htmlFor="soleTrader">
                    <input
                        className="hidden"
                        id="soleTrader"
                        type="radio"
                        value="SoleTrader"
                        checked={dealData.company.type === 'SoleTrader'}
                        onChange={handleCompanyTypeClick}
                    />
                    Sole Trader
                </label>
            </div>
            {dealData.company.type && (
                <div className='px-2 md:px-0'>
                    {dealData.company.type != 'SoleTrader' ? (
                        <div className="mb-4 md:mb-5">
                            <h3 className="mb-2">What is your <b className="text-semibold">Business Name?</b></h3>
                            <div className={'relative'}>
                                <input
                                    type="text"
                                    className="input-field"
                                    placeholder="Enter your company name"
                                    value={dealData.company.name ?? ''}
                                    onChange={(e) => {
                                        setDealData('company.name', e.target.value);
                                        setCompanyName(e.target.value);
                                    }}
                                />
                                {(showDropdown || isLoading || companies?.length > 0) &&
                                    <div
                                        className="bg-white border absolute right-0 left-0 z-10 lead top-100 max-h-[300px] overflow-auto max-w-sm mx-auto"
                                        id="dropdown">
                                        {isLoading && <div className="text-left p-3">
                                            <div className="loader-inline"></div>
                                            Searching
                                        </div>}
                                        {!isLoading && !companies?.length
                                            && dealData.company.type === "LimitedLiabilityPartnership"
                                            && <div className="py-1 custom-option text-left px-3"
                                                    onClick={() => companyClickHandler(dealData.company.name)}>
                                                {dealData.company.name}
                                            </div>}
                                        {showDropdown && companies?.length > 0 && (
                                            companies?.map((company: any, index) => {
                                                return (
                                                    <div title={company?.title} key={index}
                                                         onClick={() => companyClickHandler(company)}
                                                         className="py-1 custom-option text-left px-3"
                                                         style={{
                                                             maxWidth: "auto",
                                                             borderBottom: "1px solid gray",
                                                             backgroundColor: "white"
                                                         }}
                                                         placeholder={`Enter postcode.`}>
                                                        {company?.title}
                                                    </div>
                                                );
                                            })
                                        )}
                                    </div>
                                }
                            </div>
                        </div>
                    ) : null}

                    <div className="mb-4 md:mb-5">
                        <h3 className="mb-2">What is your <b className="text-semibold">name?</b></h3>
                        <input
                            type="text"
                            className="input-field"
                            placeholder="Enter your name"
                            value={dealData.customer.name}
                            onChange={(e) => {
                                setDealData('customer.name', e.target.value);
                                let [f, m, l] = (e.target.value).split(' ');
                                setDealData('customer.firstName', f + (l ? ' ' + m : ''));
                                setDealData('customer.lastName', l ?? m);
                            }}
                        />
                    </div>
                    <div className="mb-4 md:mb-1">
                        <h3 className="mb-2">How can we <b>contact you?</b></h3>
                        <input
                            type="tel"
                            value={dealData.customer.mobile ?? ''}
                            onChange={handleMobileNumberChange}
                            className="input-field"
                            placeholder="Enter Mobile Number*"
                        />
                        {mobileError && (
                            <div className="text-red-500">{mobileError}</div>
                        )}
                    </div>
                    <div className="mb-2 md:mb-3">
                        <input
                            type="tel"
                            value={dealData.customer.landline ?? ''}
                            onChange={handlePhoneNumberChange}
                            className="input-field"
                            placeholder={`Enter Landline Number (Optional)`}
                        />
                        {phoneError && (
                            <div className="text-red-500">{phoneError}</div>
                        )}
                    </div>
                    <div className="mb-3 md:mb-5">
                        <input
                            type="email"
                            value={dealData.customer.email ?? ''}
                            onChange={handleEmailChange}
                            className="input-field mt-3"
                            placeholder="Enter Email"
                        />
                        {emailError && (
                            <div className="text-red-500">{emailError}</div>
                        )}
                        {isLoginValid === false && (
                            <div className="text-red-500">Email Already Registered Please <a
                                className='text-bold cursor-pointer' onClick={() => setShowModal(true)}>Login</a></div>
                        )}
                    </div>

                    {/*{validateStep() && (*/}
                        <div onClick={handleNextClick}>
                            <BlackButton title="Continue"/>
                        </div>
                    {/*)}*/}

                </div>
            )}
        </>
    );
};

export default GeneralInfoStep;
