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
    const [errors, setErrors] = useState<{ [key: string]: string }>({});
    const [companies, setCompanies] = useState([]);
    const [isLoading, setIsLoading] = useState(false);
    const [showDropdown, setShowDropdown] = useState(false);
    const [companyName, setCompanyName] = useState('');

    const isLoggedIn = usePage<PageProps>().props.loggedin;

    const [isLoginValid, setIsLoginValid] = useState(true);

    const {showModal, setShowModal} = useGlobalState();

    const handleCompanyTypeClick = (event: React.ChangeEvent<HTMLInputElement>) => {
        setErrors(p => ({...p, mobile: '', type: ''}))
        let value = event.target.value;
        setOfferData('businessType', value);
        setDealData('customer.jobTitle', (value == 'SoleTrader' ? 'Proprietor / Owner' : 'Director'));
        setDealData('company', {
            name: '',
            number: '',
            type: value,
            buildingNumber: '',
            buildingName: '',
            thoroughfareName: '',
            postTown: '',
            county: '',
            postcode: '',
            dateOfIncorporation: '',
            sicCodes: '',
        });
    };

    const handleMobileNumberChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setErrors(p => ({...p, mobile: '', phone: ''}))
        const mobile = e.target.value.replace(/[a-zA-Z]/g, '').slice(0, 11);
        setDealData('customer.mobile', mobile);

        if (mobile && !validateMobile(mobile)) {
            const regExp = /^(07)\d*$/;
            if (!regExp.test(mobile)) {
                setErrors(p => ({...p, mobile: 'Mobile number must start with 07'}))
            } else {
                setErrors(p => ({...p, mobile: 'Please enter a valid phone number'}))
            }
        }
    };

    const handlePhoneNumberChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setErrors(p => ({...p, landline: '', phone: ''}))
        const landline = e.target.value.replace(/[a-zA-Z]/g, '').slice(0, 11);
        setDealData('customer.landline', landline);
        if (landline && !validatePhone(landline)) {
            const regExp = /^(0)+(?!7).*$/;
            if (!/^(0).*$/.test(landline)) {
                setErrors(p => ({...p, landline: 'Landline number must start with 0'}))
            } else if (!regExp.test(landline)) {
                setErrors(p => ({...p, landline: 'Phone number should not start with ' + landline.slice(0, 2)}))
            }
        }
    };

    const handleEmailChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setErrors(p => ({...p, email: ''}))
        const email = e.target.value;
        setDealData('customer.email', email);
        if (!validateEmail(email)) {
            setErrors(p => ({...p, email: 'Please enter a valid email address'}))
        }
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
                        setShowModal(true);
                        setIsLoginValid(false);
                    } else {
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
        const newErrors: { [key: string]: string } = {};
        let isValid = true;

        if (!dealData.company.type) {
            newErrors.type = 'Please select company type';
            isValid = false;
        }

        if (dealData.company.type != 'SoleTrader' && !dealData.company.name) {
            newErrors.company = 'Please enter company name';
            isValid = false;
        }

        if (dealData.company.name && dealData.company.name?.length < 3) {
            newErrors.company = 'Please enter valid company name';
            isValid = false;
        }

        if (!dealData.customer.name) {
            newErrors.name = 'Please enter your name';
            isValid = false;
        } else if (dealData.customer.name?.length < 3) {
            newErrors.name = 'Please enter valid name';
            isValid = false;
        }

        if (!dealData.customer.email || !validateEmail(dealData.customer.email)) {
            newErrors.email = 'Please enter a valid email address';
            isValid = false;
        }

        if (!dealData.customer.mobile && !dealData.customer.landline) {
            newErrors.phone = 'Please enter mobile or landline number';
            isValid = false;
        } else {
            const landline = dealData.customer.landline;
            const mobile = dealData.customer.mobile;
            if (mobile && !validateMobile(mobile)) {
                const regExp = /^(07)\d*$/;
                if (!regExp.test(mobile)) {
                    newErrors.mobile = 'Mobile number must start with 07';
                } else {
                    newErrors.mobile = 'Please enter a valid phone number';
                }
                isValid = false;
            }
            if (landline && !validatePhone(landline)) {
                const regExp = /^(0)+(?!7).*$/;
                if (!/^(0).*$/.test(landline)) {
                    newErrors.landline = 'Landline number must start with 0';
                } else if (!regExp.test(landline)) {
                    newErrors.landline = 'Phone number should not start with ' + landline.slice(0, 2);
                } else {
                    newErrors.landline = 'Please enter a valid Landline number';
                }
                isValid = false;
            }
        }
        setErrors(newErrors);
        return isValid;
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
                <div>
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
                {errors.type && (
                    <div className="text-red-500">{errors.type}</div>
                )}
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
                                        setErrors(p => ({...p, company: ''}))
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
                                                         }}>
                                                        {company?.title}
                                                    </div>
                                                );
                                            })
                                        )}
                                    </div>
                                }
                            </div>
                            {(!isLoading && errors.company) && (
                                <div className="text-red-500">{errors.company}</div>
                            )}
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
                                setErrors(p => ({...p, name: ''}))
                                setDealData('customer.name', e.target.value);
                                let [f, m, l] = (e.target.value).split(' ');
                                setDealData('customer.firstName', f + (l ? ' ' + m : ''));
                                setDealData('customer.lastName', l ?? m);
                            }}
                        />
                        {errors.name && (
                            <div className="text-red-500">{errors.name}</div>
                        )}
                    </div>

                    <div className="mb-4 md:mb-1">
                        <h3 className="mb-2">How can we <b>contact you?</b></h3>


                        <input
                            type="tel"
                            value={dealData.customer.mobile ?? ''}
                            onChange={handleMobileNumberChange}
                            className="input-field"
                            placeholder="Enter Mobile Number"
                        />
                        {errors.phone && (
                            <div className="text-red-500">{errors.phone}</div>
                        )}
                        {errors.mobile && (
                            <div className="text-red-500">{errors.mobile}</div>
                        )}
                    </div>
                    <div className="mb-2 md:mb-3">
                        <input
                            type="tel"
                            value={dealData.customer.landline ?? ''}
                            onChange={handlePhoneNumberChange}
                            className="input-field"
                            placeholder={`Enter Landline Number`}
                        />
                        {errors.landline && (
                            <div className="text-red-500">{errors.landline}</div>
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
                        {errors.email && (
                            <div className="text-red-500">{errors.email}</div>
                        )}
                        {isLoginValid === false && (
                            <div className="text-red-500">Email Already Registered Please <a
                                className='text-bold cursor-pointer' onClick={() => setShowModal(true)}>Login</a></div>
                        )}
                    </div>
                    <div>
                        <span onClick={handleNextClick}>
                        <BlackButton title="Continue"/>
                        </span>
                    </div>
                </div>
            )}
        </>
    );
};

export default GeneralInfoStep;
