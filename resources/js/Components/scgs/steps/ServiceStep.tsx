import React, {useEffect, useRef, useState} from 'react';
import {Image, Modal} from "react-bootstrap";
import {BlackButton} from "@/Components/elements/BlackButton";
import {setMask, validatePostcode} from "@/utils/helper";
import {ContactFormMeter} from '@/Forms/ContactFormMeter';

interface ServiceStepProps {
    onNext: () => void;
    offerData?: any,
    setOfferData: (name: any, value?: any) => void,
    dealData?: any,
    setDealData: (name: string, value: any) => void,
}

const ServiceStep: React.FC<ServiceStepProps> = ({onNext, offerData, setOfferData, dealData, setDealData}) => {
    const ref = useRef<HTMLDivElement | null>(null);
    const [postcodes, setPostcodes] = useState([]);
    const [postCode, setPostCode] = useState('');
    const [pcDropdown, setPcDropdown] = useState(false);
    const [adDropdown, setAdDropdown] = useState(false);
    const [newPostCode, setNewPostCode] = useState('');
    const [postCodeError, setPostCodeError] = useState('');
    const [addresses, setAddresses] = useState([]);
    const [fullAddress, setFullAddress] = useState('');
    const [pcLoading, setPcLoading] = useState(false);
    const [isItLoading, setIsItLoading] = useState(false);
    const [isDomestic, setIsDomestic] = useState(false);
    const [meterExclude, setMeterExclude] = useState(false);


    const handleUtilityTypeClick = (value: string) => {
        setOfferData('utilityType', value);
        setOfferData('postCode', '');

        setDealData('utilityType', value);
        setDealData('site.postcode', '');

        setAddresses([]);
        setFullAddress('')
        setPostCode('');
    }

    function isInt(value: any) {
        return value.replace(/[^0-9]+/g, '') == value;
    }

    // @ts-ignore
    let timer: any = '';
    const handlePostCodeChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        let value = e.target.value;
        setOfferData('postCode', value);
        setDealData('site.postcode', value);
        setPostCode(value);
        if (isInt(value)) {
            setPostCodeError('');
            fetchMeterLookup(value)
        } else {
            if (validatePostcode(value)) {
                setPostCodeError('');
                fetchMeterLookup(value)
            } else {
                fetchPostcode(value);
            }
        }
    };
    const handlePostCodeFocus = (e: React.FocusEvent<HTMLInputElement>) => {
        let value = e.target.value;
        if (isInt(value)) {
            if (addresses.length > 0) {
                setPcDropdown(false);
                setAdDropdown(true);
            } else {
                fetchMeterLookup(value)
            }
        } else if (validatePostcode(value)) {
            if (addresses.length > 0) {
                setPcDropdown(false);
                setAdDropdown(true);
            } else {
                fetchMeterLookup(value)
            }
        } else {
            if (postcodes.length > 0) {
                setPcDropdown(true);
                setAdDropdown(false);
            } else {
                fetchPostcode(value);
            }
        }
    };

    function postcodeClickHandler(postcode: any) {
        setPostCode(postcode);
        setOfferData('postCode', postcode);
        setDealData('site.postcode', postcode);

        setPcDropdown(false);
        setAdDropdown(false);
        setNewPostCode(postcode);
        fetchMeterLookup(postcode)
    }

    function addressClickHandler(address: any) {
        let meterNumber = address.meternumber;
        let mpanTop = address.mpantop ?? '';
        let fulAddress = address.addressfull;
        let domestic = address?.profile == 'domestic';
        let meterExclusion = address.exclusion;
        let isHalfHourly = address?.profile == 'half-hourly';

        let buildingNumber = (address?.addressline1 ?? '');
        let buildingName = (address?.addressline2 ?? '');
        let postTown = address?.posttown ?? '';
        let county = address?.county ?? '';

        setIsDomestic(domestic);
        setMeterExclude(meterExclusion);

        setOfferData({
            meterNumber: meterNumber,
            MPANTop: mpanTop,
            meterType: address?.metertype,
            measurementClass: address?.measurementclass,
            halfHourly: isHalfHourly,
        });
        setDealData('smeDetails', {
            meterNumber: meterNumber,
            mpanTop: mpanTop,
            halfHourly: isHalfHourly,
            meterSerialNumber: address?.meterserialnumber,
        });
        let _address = {
            county: county,
            postTown: postTown,
            postcode: address?.postcode,
            buildingNumber: buildingNumber,
            buildingName: buildingName,
        };
        // setDealData('customer', _address);
        setDealData('site', {
            ..._address,
            address: fulAddress
        });

        setPcDropdown(false);
        setAdDropdown(false);
        setFullAddress(fulAddress);
    }

    const [showDetailModal1, setShowDetailModal1] = useState(false);

    const handleCloseModal1 = () => {
        setShowDetailModal1(false);
    };

    const [showDetailModal2, setShowDetailModal2] = useState(false);

    const handleCloseModal2 = () => {
        setShowDetailModal2(false);
    };

    const handleNextClick = () => {
        if (dealData.utilityType && dealData.site.address && dealData.site.postcode && validatePostcode(dealData.site.postcode)) {
            if (meterExclude) {
                setShowDetailModal2(true);
            } else if (isDomestic) {
                setShowDetailModal1(true);
            } else {
                onNext();
            }
        } else {
            alert('Please select a service, enter a valid postcode.');
        }
    };

    const fetchMeterLookup = (postCode: any) => {
        setAddresses([]);
        setPcDropdown(false);

        if (validatePostcode(postCode) || isInt(postCode)) {
            setIsItLoading(true);
            const requestOptions = {
                method: 'POST',
                headers: {'Content-Type': 'application/json', Accept: 'application/json'},
                body: JSON.stringify({
                    postCode: postCode,
                    utilityType: offerData.utilityType,
                })
            };
            fetch('/api/powwr/meter-lookup', requestOptions)
                .then(response => response.json())
                .then(resp => {
                    let _addresses = resp?.data?.addresses || [];
                    setAddresses(_addresses);
                    setIsItLoading(false);
                    setAdDropdown(true);
                })
                .catch(error => {
                    setIsItLoading(false);
                    console.error('Create deal error:', error);
                });
        }
    };

    const fetchPostcode = (value: string) => {
        setPostcodes([]);
        setPcDropdown(true);
        setAdDropdown(false);
        if (value.length > 1) {
            setIsItLoading(true);
            fetch(`/api/powwr/postcode?q=${value}`)
                .then(response => response.json())
                .then(resp => {
                    setPostcodes(resp.data);
                    setIsItLoading(false);
                    setPcDropdown(true);
                })
                .catch(error => {
                    setIsItLoading(false);
                });
        }
    };

    function checkClickOutside(e: MouseEvent) {
        // @ts-ignore
        if (ref.current && !ref.current.contains(e.target)) {
            setPcDropdown(false);
            setAdDropdown(false);
        }
    }

    /*useEffect(() => {
        const timer = setTimeout(() => {
            fetchMeterLookup();
        }, 1000);
        return () => clearTimeout(timer);
    }, [postCode]);*/

    useEffect(() => {
        document.addEventListener("click", checkClickOutside);
        return () => {
            document.removeEventListener("click", checkClickOutside);
        };
    }, []);

    const afterFormSubmit = () => {
        setShowDetailModal1(false);
    };

    return (
        <div className="flex flex-col lg:flex-row gap-5 px-3 md:px-0">
            <style>
                {`
                  .btn-form-submit {
                    display:none;
                  }
                `}
            </style>
            <div className='text-left order-imp'>
                <h1 className="text-white mb-5 text-2xl md:text-4xl"><b>Thank you</b> for considering <b>Utility Box</b>.
                    To your <b>utility</b> needs, and any specific <b>challenges</b> you're facing.</h1>
                <ul className='text-white'>
                    <li className='mb-3 flex gap-3'>
                    <span className='align-self-center'>
                    <Image className="mr-5" src="/images/icons/pointcheck1.png" width={20}/>
                    </span>
                        Consultation Call: This is an opportunity for us to understand your requirements better and
                        address any questions you may have.
                    </li>
                    <li className='mb-3 flex gap-3'>
                    <span className='align-self-center'>
                    <Image className="mr-5" src="/images/icons/pointcheck1.png" width={20}/>
                    </span>
                        Personalised Quote: Based on our consultation, we'll provide you with a personalised quote
                        outlining the services.
                    </li>
                    <li className='mb-3 flex gap-3'>
                    <span className='align-self-center'>
                    <Image className="mr-5" src="/images/icons/pointcheck1.png" width={20}/>
                    </span>
                        Decision Time: Take your time to review the quote. If you have any further questions or require
                        adjustments, we're here to assist.
                    </li>
                </ul>
            </div>
            <div className="justify-self-end self-center">
                <div className='bg-grey w-fit px-[.5rem] md:px-[5rem] py-5 rounded-md'>
                    <div className="mb-5 order-last">
                        <h3 className="">Get Your <b>Quote</b> <br/>Done <b>in 2 Minutes</b></h3>
                        <div className={'text-center'}>
                            <button type={"button"} onClick={() => handleUtilityTypeClick('electric')}
                                    className={`selectbuttonscgs selectbutton rounded-3 ${offerData.utilityType == 'electric' ? 'active' : ''}`}>
                                <div className="p-[6px] md:p-[14px] bg-blue rounded-3 mb-4"
                                     style={{width: "fit-content"}}>
                                    <Image src={'/images/icons/electric.png'}/>
                                </div>
                                <h5 className="text-black-bold md:text-xl text-sm">Electricity</h5>
                            </button>
                            <button type={"button"} onClick={() => handleUtilityTypeClick('gas')}
                                    className={`selectbuttonscgs selectbutton rounded-3 ${offerData.utilityType == 'gas' ? 'active' : ''}`}>
                                <div className="md:p-[14px] p-[6px] bg-blue rounded-3 mb-4"
                                     style={{width: "fit-content"}}>
                                    <Image src={'/images/icons/gas.png'}/>
                                </div>
                                <h5 className="text-black-bold md:text-xl text-sm">Gas</h5>
                            </button>
                        </div>
                    </div>
                    <div className="mb-2">
                        <h3 className="mb-4">What is your <b className="text-semibold">supply address?</b></h3>
                        <div className="w-full d-grid relative justify-content-center">
                            <div className={'inline-block relative'} ref={ref}>
                                <input type="text" id={`post_code`}
                                       value={postCode}
                                       placeholder={`Enter Post Code`}
                                       onChange={handlePostCodeChange}
                                       onFocus={handlePostCodeFocus}
                                       autoComplete='off'
                                       className={`input-field ${postCodeError ? 'error' : ''}`}/>
                                {postCodeError && <div className={`text-red-500 text-left`}>{postCodeError}</div>}
                                {(pcDropdown || adDropdown || isItLoading || addresses?.length > 0 || postcodes?.length > 0) &&
                                    <div
                                        className="bg-white border absolute right-0 left-0 z-10 lead top-100 max-h-[300px] overflow-auto"
                                        id="dropdown">
                                        {isItLoading && <div className="text-left p-3">
                                            <div className="loader-inline"></div>
                                            Searching
                                        </div>}
                                        {pcDropdown && postcodes?.length > 0 && (
                                            postcodes?.map((postcode: string, index) => {
                                                return (
                                                    <div title={postcode} key={index}
                                                         onClick={() => postcodeClickHandler(postcode)}
                                                         className="py-1 custom-option text-left px-3" style={{
                                                        maxWidth: "24rem",
                                                        borderBottom: "1px solid gray",
                                                        backgroundColor: "white"
                                                    }} placeholder={`Enter postcode.`}>
                                                        {postcode}
                                                    </div>
                                                );
                                            })
                                        )}
                                        {adDropdown && addresses?.length > 0 && (
                                            addresses?.map((address: any, index) => {
                                                const meterNumber = address.meternumber ?? '';
                                                const addressFull = address.addressfull;
                                                const isHalfHourly = address?.profile == 'half-hourly';
                                                const isDomestic = address?.profile == 'domestic';
                                                return (
                                                    <div title={`${addressFull} - ${meterNumber}`} key={index}
                                                         onClick={() => addressClickHandler(address)}
                                                         className="py-1 text-left custom-option px-3" style={{
                                                        maxWidth: "24rem",
                                                        borderBottom: "1px solid gray",
                                                        backgroundColor: "white"
                                                    }} placeholder={`Enter postcode.`}>
                                                        <b className='text-lg'>{`${addressFull}`}</b><br/>
                                                        <span className='text-sm'><b
                                                            className={`text-${isHalfHourly ? "red" : isDomestic ? "orange" : "green"}-500 font-bold`}>#</b> {`${isHalfHourly ? "Half-Hourly Meter No'" : isDomestic ? "Upgradable Meter No'" : "Meter No'"}`} {`${setMask(meterNumber)}`}</span>
                                                    </div>
                                                );
                                            })
                                        )}
                                    </div>
                                }
                            </div>
                            {dealData.site.address && (
                                <span className="md:w-[24rem] md:px-2 w-[15rem] px-1 py-2 border mt-2">
                            {dealData.site.address} - {setMask(dealData.smeDetails?.meterNumber)}
                        </span>
                            )}
                        </div>
                    </div>
                    {(dealData.utilityType && offerData.postCode) && (
                        <div onClick={handleNextClick}>
                            <BlackButton title="Continue"/>
                        </div>
                    )}
                </div>
            </div>

            <Modal show={showDetailModal1} onHide={handleCloseModal1} className="text-center" size="lg" centered>
                <Modal.Body className="p-5 border-topcopper">
                    <div className='px-5 md:px-2'>
                        <h2 className='mb-4'>We have noticed that your meter <br/>requires <b>bespoke pricing</b></h2>

                        <p className='mb-3'>We have noticed that your meter will need an upgrade, No need to worry
                            because we can help you out!</p>
                        <p className='mb-4'>Fill the form Below so we can reach you out and our specialist will give you
                            a call shortly.</p>

                        <ContactFormMeter onSubmit={afterFormSubmit}/>

                        <div className='d-flex gap-3 mt-4'>
                            <div></div>
                            <button onClick={() => setShowDetailModal1(false)}
                                    className="w-full border-blue-btn px-4 py-3 rounded-sm mb-2">
                                Close
                            </button>
                            <button className="w-full btn-blue text-white text-bold px-4 py-3 rounded-sm mb-2"
                                    form='meter00' type="submit">
                                Send
                            </button>
                            <div></div>
                        </div>
                    </div>
                </Modal.Body>
            </Modal>

            <Modal show={showDetailModal2} onHide={handleCloseModal2} className="text-center" size="lg" centered>
                <Modal.Body className="p-5 border-topcopper">
                    <div className='px-5 md:px-2'>
                        <h2 className='mb-4'>We have noticed that your meter <br/>is <b>Already Registered!</b></h2>

                        <p className='mb-3'>To further information and to know how you can get a quote kindly reach us
                            on <a className='text-blue text-medium'
                                  href="mailto:hello@utilitybox.org.uk">hello@utilitybox.org.uk</a></p>
                        {/* <p className='mb-4'>Fill the form Below so we can reach you out and our specialist will give you a call shortly.</p> */}

                        {/* <ContactFormMeter/> */}

                        <div className='d-flex gap-3 mt-4'>
                            <div></div>
                            <button onClick={() => setShowDetailModal2(false)}
                                    className="w-full border-blue-btn px-4 py-3 rounded-sm mb-2">
                                Close
                            </button>
                            {/* <button className="w-full btn-blue text-white text-bold px-4 py-3 rounded-sm mb-2" form='meter00' type="submit">
                                Send
                            </button> */}
                            <div></div>
                        </div>
                    </div>
                </Modal.Body>
            </Modal>

        </div>
    );
};

export default ServiceStep;

