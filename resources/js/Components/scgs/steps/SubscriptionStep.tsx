import Dropdown from '@/Components/elements/Dropdown';
import React, {useEffect, useState} from 'react';
import {Image, Modal} from 'react-bootstrap';
import {format_date, getSupplierIdByName, setMask} from "@/utils/helper";
import {PageProps} from '@/types';
import {usePage} from '@inertiajs/react';
import {useGlobalState} from '@/Layouts/elements/PopupContext';
import {ContactFormMeter} from "@/Forms/ContactFormMeter";

interface Supplier {
    name: string;
    powwr_id: string;
    logo?: string;
    status?: number;
    logo_url?: string;
}

interface SubscriptionStepProps {
    onNext: () => void;
    offerData?: any,
    setOfferData: (name: any, value?: any) => void,
    dealData?: any,
    setDealData: (name: string, value: any) => void,
    saveDeal: (calback: any, failed?: any) => void,
    suppliers: Supplier[],
    pricechange: any;
    isLoading: boolean;
    setIsLoading: (isLoading: boolean) => void,
}

export default function SubscriptionStep({
                                             onNext,
                                             offerData,
                                             setOfferData,
                                             saveDeal,
                                             dealData,
                                             setDealData,
                                             suppliers,
                                             pricechange,
                                             isLoading,
                                             setIsLoading
                                         }: SubscriptionStepProps) {
    const [offersData, setOffersData] = useState<any>(null);
    const [section1Open, setSection1Open] = useState(true);
    const [section2Open, setSection2Open] = useState(true);
    //const [isLoading, setIsLoading] = useState(false);
    const [selectedOffer, setSelectedOffer] = useState<any>(null);
    const {showModal, setShowModal} = useGlobalState();
    const [isEmailValid1, setIsEmailValid1] = useState(Boolean);
    const [showDetailModal, setShowDetailModal] = useState(false);
    const [showFilterModal, setShowFilterModal] = useState(false);
    const [showDetailModal2, setShowDetailModal2] = useState(false);

    const [showHalfHourlyModal, setShowHalfHourlyModal] = useState(false);
    const handleCloseHalfHourlyModal = () => {
        setShowHalfHourlyModal(false);
    };
    const afterHalfHourlyFormSubmit = () => {
        setShowHalfHourlyModal(false);
    };

    function getLogo(name: string) {
        let logo = name?.replaceAll(' ', '-').toLowerCase();
        return `/images/logos/${logo}.png`;
    }

    const isLoggedIn = usePage<PageProps>().props.loggedin;

    const toggleModal = () => {
        setShowModal(true);
    };

    const toggleContinueForLoggedIn = () => {
        if (isLoggedIn == true) {
            onNext();
        } else {

            fetch('/api/validate/email', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({email: dealData.customer.email}),
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success && data.data.user !== null) {
                        // User is not null, proceed with onNext()
                        setIsEmailValid1(true);
                    } else {
                        setIsEmailValid1(false);
                    }
                })
                .catch((error) => {
                    console.error('Error validating email:', error);
                });

            if (isEmailValid1 == true) {
                toggleModal();
            } else {
                setShowDetailModal2(true);
            }
        }
    };

    function switchOnlineHandler(quote: any) {
        toggleContinueForLoggedIn();

        let paymentMethod = quote?.PaymentMethod?.Type ?? '';
        if (paymentMethod == 'Direct Debit') {
            paymentMethod = 'FixedDirectDebit';
        }

        setDealData('quoteDetails', quote)
        setDealData('smeDetails.IsRenewable', quote?.IsRenewable ?? false)
        setDealData('contract.isRenewalForSupplier', quote?.IsRenewable ?? false)
        setDealData('contract.endDate', quote?.ContractEndDate ?? quote?.Term)
        setDealData('rates.rateType', quote?.StandingChargeType ?? '')
        setDealData('rates.amount', quote?.StandingCharge ?? 0)
        setDealData('rates.uplift', quote?.Uplift ?? 0.2)
        setDealData('rates.unit', 'pencePerDay')
        setDealData('paymentDetail.method', paymentMethod)
        setDealData('contract.newSupplier', getSupplierIdByName(suppliers, quote.Supplier))
        setDealData('contract.newSupplierName', quote.Supplier)
        saveDeal(function (resp: any) {
            toggleContinueForLoggedIn();
        })
    }

    const toggleSection1 = () => {
        setSection1Open(!section1Open);
    };

    const toggleSection2 = () => {
        setSection2Open(!section2Open);
    };

    const handleCloseModal = () => {
        setShowDetailModal(false);
    };

    const handleCloseModal1 = () => {
        setShowFilterModal(false);
    };

    const handleCloseModal2 = () => {
        setShowDetailModal2(false);
    };

    const getOffers = () => {
        setIsLoading(true);
        const options = {
            method: 'POST',
            headers: {'Content-Type': 'application/json', Accept: 'application/json'},
            body: JSON.stringify(offerData),
        };

        fetch('/api/powwr/offers', options)
            .then(response => response.json())
            .then(data => {
                setIsLoading(false);
                setOffersData(data.data);
            }).catch(error => {
            setIsLoading(false);
            console.error('Create deal error:', error);
        });
    };

    useEffect(() => {
        getOffers();
    }, [offerData]);

    function toggleDropdown(element: { classList: { toggle: (arg0: string) => void; }; }) {
        element.classList.toggle('active');
    }

    // Assuming offerData?.contractRenewalDate is a string in the format 'YYYY/MM/DD'
    const formattedContractRemewalDate = offerData?.contractRenewalDate
        ? format_date(offerData.contractRenewalDate, 'UK')
        : '';

    // Assuming selectedOffer?.ContractEndDate is a string in the format 'YYYY-MM-DD'
    const formattedEndDateDate = selectedOffer?.ContractEndDate
        ? format_date(selectedOffer.ContractEndDate, 'UK')
        : '';


    return (<>
        <div className="md:flex container">
            {/* Left Div */}
            <div className="md:w-3/4 w-full p-4">
                <div className="sm:flex justify-between">
                    <p className="text-center text-bold mb-4 sm:mb-0"><b
                        className="text-blue">{offersData?.Rates?.length} Offers</b> Found</p>
                    <div className="sm:w-50 text-right d-flex justify-between gap-3 sm:block">
                        <span className="text-semibold text-blue mt-2" style={{cursor: 'pointer'}}
                              onClick={() => setShowFilterModal(true)}>Filter</span>
                        <Dropdown setValue={(value) => {
                            setOfferData('plans.duration', value)
                        }} buttonText="Sort Plans"
                                  options={{
                                      '': 'All',
                                      12: '1 Year',
                                      24: '2 Years',
                                      36: '3 Years',
                                      48: '4 Years',
                                      60: '5 Years'
                                  }}/>
                    </div>
                </div>
                {offersData?.Rates?.length ? (<div className="flex flex-wrap -mx-4">
                    <OffersCard
                        rates={offersData?.Rates}
                        getLogo={getLogo}
                        handlerDetailsClick={(item) => {
                            setSelectedOffer(item); // Set the selected offer card's data
                            setShowDetailModal(true); // Show the modal
                        }}
                        handlerSwitchOnlineClick={(item) => {
                            switchOnlineHandler(item)
                        }}
                    />
                </div>) : (<div className="text-center text-blue text-bold mt-5">
                    <h3>No Offers Available for your Meter.</h3><br/>
                    No offers from {offerData?.newSupplierName}
                    {/* <Link href="/swift-contract-generation-system">
                            <button className="bg-blue text-white px-4 py-2 rounded-sm mt-4">
                                Want to Check More Offers?
                            </button>
                            </Link> */}
                </div>)}
                {offerData.halfHourly && <div className="text-center text-blue text-bold mt-5">
                    {offersData?.Rates?.length ? "if you do not want to proceed with the above suppliers," : "No offers found?"}
                    <a href="#" onClick={(e) => {
                        setShowHalfHourlyModal(true)
                    }}> please click here</a>
                </div>}
            </div>

            {/* Right Div (Sidebar) */}
            <div className="md:w-1/4 p-2">

                {/* <div className="bg-white rounded-md shadow-md mb-4" style={{borderBottom: "1px solid transparent"}}>
                        <button
                            className="w-full text-blue text-bold text-left px-4 py-3"
                            style={{borderBottom: "1px solid lightgrey"}}
                            onClick={toggleSection1}
                        >
                            Details
                        </button>
                        {section1Open && (<>
                            <div className="bg-white my-3 mx-4 text-left">
                                <p className="font-bold text-blue">Reference Number:</p>
                                <p className="text-gray-700">Text for Cell 1 goes here.</p>
                            </div>

                            <div className="bg-white my-3 mx-4 text-left">
                                <p className="font-bold text-blue">Recieve quote by email?</p>
                                <p className="text-gray-700">Text for Cell 1 goes here.</p>
                            </div>
                        </>)}
                    </div> */}

                <div className="bg-white rounded-md shadow-md mb-4" style={{borderBottom: "1px solid transparent"}}>
                    <button
                        className="w-full text-blue text-bold text-left px-4 py-3"
                        style={{borderBottom: "1px solid lightgrey"}}
                        onClick={toggleSection2}
                    >
                        Summary
                    </button>
                    {section2Open && (<>
                        <div className="bg-white my-3 mx-4 text-left">
                            <p className="font-bold text-blue">Meter Number</p>
                            <p className="text-gray-700">{setMask(dealData.smeDetails.meterNumber)}</p>
                        </div>

                        <div className="bg-white my-3 mx-4 text-left">
                            <p className="font-bold text-blue">Meter Address:</p>
                            <p className="text-gray-700">{dealData.site.address}</p>
                        </div>

                        <div className="bg-white my-3 mx-4 text-left">
                            <p className="font-bold text-blue">Utility Type</p>
                            <p className="text-gray-700 d-flex capitalize"><span className="bg-copper rounded p-1 mr-2"><Image
                                width={20}
                                src={offerData.utilityType == 'gas' ? '/images/icons/gas.png' : '/images/icons/electric.png'}/></span>{offerData.utilityType}
                            </p>
                        </div>

                        {offerData.currentSupplierName && <div className="bg-white my-3 mx-4 text-left">
                            <p className="font-bold text-blue">Current Supplier:</p>
                            <p className="text-gray-700">{offerData.currentSupplierName}</p>
                        </div>}

                        <div className="bg-white my-3 mx-4 text-left">
                            <p className="font-bold text-blue">Contract End Date:</p>
                            <p className="text-gray-700">{offerData.contractEndDate ? format_date(offerData.contractEndDate, 'UK') : 'Contract Ended'}</p>
                        </div>
                    </>)}
                </div>
            </div>
        </div>

        <Modal show={showDetailModal} onHide={handleCloseModal} size="lg" centered>
            <Modal.Body className="p-5">
                <div className="flex">
                    <div className="w-1/2">
                        <h2 className="text-3xl font-semibold mb-2 text-left">
                            Plan Details
                        </h2>
                    </div>
                    <div className='w-50 text-right'>
                        {/* <Image style={{display: "initial"}} src="/partners/british-gas.png" width={130}/> */}
                        <img alt="" src={getLogo(selectedOffer?.Supplier)} style={{display: "initial"}} width={130}/>
                    </div>
                </div>

                <div className="grid grid-cols-2 gap-4 py-3 mt-4" style={{borderBottom: '1px solid grey'}}>
                    <div className="text-left">
                        <p className="">Supplier</p>
                    </div>
                    <div className="text-right">
                        <p className="">{selectedOffer?.Supplier}</p>
                    </div>
                </div>
                <div className="grid grid-cols-2 gap-4 py-3 " style={{borderBottom: '1px solid grey'}}>
                    <div className="text-left">
                        <p className="">Fixed Deal</p>
                    </div>
                    <div className="text-right">
                        <p className="">{selectedOffer?.FixedFee != null ? 'Fixed Rates' : 'Not Fixed Fees'}</p>
                    </div>
                </div>
                <div className="grid grid-cols-2 gap-4 py-3 " style={{borderBottom: '1px solid grey'}}>
                    <div className="text-left">
                        <p className="">Standing Charge</p>
                    </div>
                    <div className="text-right">
                        <p className="">{selectedOffer?.StandingCharge} Pence/Unit</p>
                    </div>
                </div>
                <div className="grid grid-cols-2 gap-4 py-3 " style={{borderBottom: '1px solid grey'}}>
                    <div className="text-left">
                        <p className="">Day Unit Rate</p>
                    </div>
                    <div className="text-right">
                        <p className="">{selectedOffer?.DayUnitrate} Pence/Unit</p>
                    </div>
                </div>
                <div className="grid grid-cols-2 gap-4 py-3 " style={{borderBottom: '1px solid grey'}}>
                    <div className="text-left">
                        <p className="">Est. Monthly</p>
                    </div>
                    <div className="text-right">
                        <p className="">£
                            {typeof selectedOffer?.RawBaseAnnualPrice
                                ? "" + (selectedOffer?.RawBaseAnnualPrice / 12).toFixed(2) + ""
                                : "N/A"}
                        </p>
                    </div>
                </div>
                <div className="grid grid-cols-2 gap-4 py-3 " style={{borderBottom: '1px solid grey'}}>
                    <div className="text-left">
                        <p className="">Est. Annual</p>
                    </div>
                    <div className="text-right">
                        <p className="">£{selectedOffer?.RawBaseAnnualPrice}</p>
                    </div>
                </div>
                <div className="grid grid-cols-2 gap-4 py-3 " style={{borderBottom: '1px solid grey'}}>
                    <div className="text-left">
                        <p className="">Propossed Switch Date</p>
                    </div>
                    <div className="text-right">
                        <p className="">{formattedContractRemewalDate}</p>
                    </div>
                </div>
                <div className="grid grid-cols-2 gap-4 py-3 ">
                    <div className="text-left">
                        <p className="">Proposed End Date</p>
                    </div>
                    <div className="text-right">
                        <p className="">{formattedEndDateDate}</p>
                    </div>
                </div>
            </Modal.Body>
        </Modal>

        {/* filter  */}
        <Modal show={showFilterModal} onHide={handleCloseModal1} size="lg" centered>
            <Modal.Body className="p-5">
                <div className="flex">
                    <div className="w-1/2">
                        <h2 className="text-3xl font-semibold mb-2 text-left">
                            Filter
                        </h2>
                    </div>
                </div>
                <h4 className="mt-4 text-lg text-semibold">Suppliers</h4>
                <select
                    value={offerData?.newSupplierName}
                    onChange={(e) => {
                        const name = e.target.value;
                        const id = getSupplierIdByName(suppliers, name);
                        setOfferData({
                            newSupplierName: name,
                            newSupplierId: id,
                        });
                    }}
                    className="w-full py-3 mt-2 mb-3"
                >
                    <option value="">Select {offerData.utilityType} Supplier</option>
                    {Object.keys(pricechange).length && Object.keys(pricechange).map((supplier) => (
                        supplier !== 'BES' && <option key={supplier} value={supplier}>
                            {supplier}
                        </option>
                    ))}
                </select>

                <button onClick={() => (getOffers(), handleCloseModal1())}
                        className="w-full bg-btn-grey text-white px-4 py-3 rounded-sm mb-2">
                    Filter
                </button>

                {/* <div className="container mx-auto px-4 py-8">
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            {boxesData.map((box, index) => (
                            <Box key={index} image={box.image} text={box.text} />
                            ))}
                        </div>
                    </div> */}
            </Modal.Body>
        </Modal>

        {/* Signup  */}
        <Modal show={showDetailModal2} onHide={handleCloseModal2} centered>
            <Modal.Body className="p-5 text-center">
                <div className="icon-container self-center">
                    <div className="mail-icon">
                        <i className="fas fa-envelope"></i>
                    </div>
                </div>
                <h2 className='mb-4'>You have not <b>verified</b> your <b>Account</b> Yet?</h2>
                <p>We have sent you an email at <span
                    className='text-blue underline font-medium'>{dealData.customer.email}</span>. <br/>Kindly verify
                    your email address and continue.</p>
            </Modal.Body>
        </Modal>

        <Modal show={showHalfHourlyModal} onHide={handleCloseHalfHourlyModal} className="text-center" size="lg"
               centered>
            <Modal.Body className="py-4 py-md-5 border-topcopper">
                <div className='px-2 px-md-5'>
                    <h2 className='mb-4'>Your Meter is Special and needs <b>special attention</b></h2>
                    <p className='mb-3'>We have noticed that your meter will need a bespoke quote.</p>
                    <p className='mb-4'>Fill the form Below so we can reach you out and our specialist will give you
                        a call shortly.</p>
                    <ContactFormMeter onSubmit={afterHalfHourlyFormSubmit} onCancel={() => {
                        setShowHalfHourlyModal(false)
                    }}/>
                    {/*<div className='d-md-flex gap-3 mt-4'>
                        <div className='d-none d-md-block'></div>
                        <button onClick={() => setShowHalfHourlyModal(false)}
                                className="w-full border-blue-btn px-4 py-3 rounded-sm mb-2">
                            Close
                        </button>
                        <button className="w-full btn-blue text-white text-bold px-4 py-3 rounded-sm mb-2"
                                form='meter00' type="submit">
                            Send
                        </button>
                        <div className='d-none d-md-block'></div>
                    </div>*/}
                </div>
            </Modal.Body>
        </Modal>
    </>);
};

interface OfferCardProps {
    rates: any;
    getLogo: (name: string) => string
    handlerDetailsClick: (item: any) => void
    handlerSwitchOnlineClick: (item: any) => void
}

export function OffersCard({rates, getLogo, handlerDetailsClick, handlerSwitchOnlineClick}: OfferCardProps) {
    return (
        <>
            {rates?.length && rates.map((rateItem: any, index: number) => (
                <div key={index} className={`md:w-1/2 p-3`}>
                    <div className="bg-white p-4 rounded-sm shadow-md">
                        <div className="flex">
                            <div className="w-1/2">
                                <h2 className="text-xl font-semibold mb-2 text-left">
                                    {getLogo(rateItem?.Supplier) ? (
                                        <img style={{display: "initial"}} title={rateItem?.Supplier}
                                             src={getLogo(rateItem?.Supplier)} width={130}/>
                                    ) : (
                                        rateItem?.Supplier
                                    )}
                                </h2>
                            </div>
                            <div className='w-50 text-right'>
                                {rateItem?.Preferred == 1 && (<div>
                                    <button
                                        className='bg-blue px-3 py-2 text-white text-sm rounded-2 mb-2 inline-flex items-center gap-2'>
                                        <Image src='/images/icons/like.png' width={15}/>Preferred
                                    </button>
                                </div>)}
                                {rateItem?.BestDeal == true && (<div>
                                    <span className='bg-copper px-3 py-2 text-white text-sm rounded-2'>Best Deal</span>
                                </div>)}
                            </div>
                        </div>
                        <div className="w-full text-gray-700 w-1/2 text-left text-sm">
                            <span className="text-gray-700 inline-block mr-2 text-right text-sm">
                                {rateItem?.PlanType ? rateItem?.PlanType : rateItem?.Pricebook}
                            </span>
                            {rateItem?.Term ? `${Math.floor(rateItem.Term / 12)} Year Plan` : ''}
                        </div>

                        <div className="grid grid-cols-2 grid-rows-1 mt-2" style={{borderTop: "1px solid lightgrey"}}>
                            {/* <div className="py-3 pr-2 text-left" style={{borderRight: "1px solid lightgrey", borderBottom: "1px solid lightgrey"}}>
                                <p className="text-sm font-bold text-blue">Rate Type</p>
                                <p className="text-sm text-gray-700">{rateItem?.StandingChargeType ? rateItem?.StandingChargeType : 'N/A'}</p>
                            </div> */}
                            <div className="py-2 pl-2 pr-1 text-left"
                                 style={{borderBottom: "1px solid lightgrey", borderRight: "1px solid lightgrey"}}>
                                <p className="text-sm font-bold text-blue">Standing Charge</p>
                                <p className="text-sm text-gray-700">{rateItem?.StandingCharge} Pence/Day</p>
                            </div>
                            <div className="py-2 pl-2 pr-1 text-left"
                                 style={{borderBottom: "1px solid lightgrey", borderRight: "0px solid lightgrey"}}>
                                <p className="text-sm font-bold text-blue">Day Unit Rate</p>
                                <p className="text-sm text-gray-700">{rateItem?.DayUnitrate} Pence/Day</p>
                            </div>
                            {rateItem?.NightUnitrate > 0 && <div className="py-2 pl-2 pr-1 text-left"
                                                                 style={{
                                                                     borderBottom: "1px solid lightgrey",
                                                                     borderRight: "1px solid lightgrey"
                                                                 }}>
                                <p className="text-sm font-bold text-blue">Night Unit Rate</p>
                                <p className="text-sm text-gray-700">{rateItem?.NightUnitrate} Pence/Day</p>
                            </div>}
                            {rateItem?.WendUnitrate > 0 && <div className="py-2 pl-2 pr-1 text-left"
                                                                style={{
                                                                    borderBottom: "1px solid lightgrey",
                                                                    borderRight: "1px solid lightgrey"
                                                                }}>
                                <p className="text-sm font-bold text-blue">Wend Unit Rate</p>
                                <p className="text-sm text-gray-700">{rateItem?.WendUnitrate} Pence/Day</p>
                            </div>}
                            {/* <div className="py-3 pr-2 text-left" style={{borderRight: "1px solid lightgrey", borderBottom: "1px solid lightgrey"}}>
                                <p className="text-sm font-bold text-blue">New Lower Rates</p>
                                <p className="text-sm text-gray-700">Text for Cell 1 goes here.</p>
                            </div> */}
                            <div className="py-2 pl-2 col-span-2 pr-1 text-left"
                                 style={{borderBottom: "1px solid lightgrey"}}>
                                <p className="text-sm font-bold text-blue">Total New Spend</p>
                                <p className="text-sm text-gray-700">£{rateItem?.RawBaseAnnualPrice
                                    ? "" + (rateItem?.RawBaseAnnualPrice / 12).toFixed(2) + " Per Month"
                                    : "N/A"}</p>
                                <p className="text-sm text-gray-700">{rateItem?.BaseAnnualPrice} Per Annum</p>
                            </div>
                        </div>
                        <div className="mt-4">
                            <button onClick={() => handlerDetailsClick(rateItem)}
                                    className="w-full bg-btn-grey text-white px-4 py-2 rounded-sm mb-2">
                                Supplier Details
                            </button>
                            <button onClick={() => handlerSwitchOnlineClick(rateItem)}
                                    className="w-full bg-blue hover-bg-copper text-white px-4 py-2 rounded-sm ">
                                Switch Online
                            </button>
                        </div>
                    </div>
                </div>
            ))}
        </>
    );
};
