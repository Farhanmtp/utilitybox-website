import React, {useEffect, useState} from 'react';
import {BlackButton} from '../../elements/BlackButton';
import {dbDateFormat, getSupplierIdByName, ucfirst, validate_field} from "@/utils/helper";
import {Button, Modal} from 'react-bootstrap';
import DatePicker from "react-datepicker";
// import Select from 'react-select';
import CreatableSelect from 'react-select/creatable';


import "react-datepicker/dist/react-datepicker.css";

interface Supplier {
    name: string;
    powwr_id: string;
}

interface ConsumptionDetailStepProps {
    onNext: () => void;
    offerData?: any,
    setOfferData: (name: string, value: any) => void,
    dealData?: any,
    setDealData: (name: string, value: any) => void,
    saveDeal: (calback: any, failed?: any) => void,
    suppliers: Supplier[];
    pricechange: any;
}

const ConsumptionDetailStep: React.FC<ConsumptionDetailStepProps> = ({
                                                                         onNext,
                                                                         offerData,
                                                                         setOfferData,
                                                                         dealData,
                                                                         setDealData,
                                                                         saveDeal,
                                                                         suppliers,
                                                                         pricechange
                                                                     }) => {
    const [contractEndDate, setContractEndDate] = useState(null);
    const [contractStartDate, setContractStartDate] = useState(null);
    const [contractEnded, setContractEnded] = useState(false);
    const [selectedSupplier, setSelectedSupplier] = useState<any>();
    const [isDateValid, setIsDateValid] = useState(true);
    const currentDate = new Date();
    const [showModal, setShowModal] = useState(false);
    const [prompts, setPrompts] = useState(false);


    const minContractStartDate = () => {
        const currentDate = new Date();
        const endDate = contractEndDate ? new Date(contractEndDate) : new Date();
        if (endDate <= currentDate) {
            endDate.setDate(endDate.getDate() + 6); // Add 6 days
        }
        return endDate;
    };

    const handleDateChange = (e: any) => {
        setContractEndDate(e);

        const selectedDate = new Date(e);
        const currentDate = new Date();

        if (selectedDate < currentDate) {
            setContractEnded(true);
            setContractEndDate(null);

            setOfferData('contractEndDate', 'Contract Ended');
            setDealData('contract.currentEndDate', dbDateFormat(currentDate));
            // @ts-ignore
            handleNewContractStart(currentDate.setDate(currentDate.getDate() + 6));
        } else {
            setContractEnded(false);
            const value = dbDateFormat(selectedDate);
            setOfferData('contractEndDate', value);
            setDealData('contract.currentEndDate', value);
            // @ts-ignore
            handleNewContractStart(selectedDate.setDate(selectedDate.getDate() + 1));
        }

        setOfferData('consumption.amount', '')
        setOfferData('consumption.day', '')
        setDealData('usage.day', '')

        setSelectedSupplier(null);
    };
    const handleContractEndedChange = () => {
        setContractEnded(!contractEnded);
        setContractEndDate(null);
        setSelectedSupplier(null);
        const currentDate = new Date();
        // @ts-ignore
        handleNewContractStart(currentDate.setDate(currentDate.getDate() + 6));
    };

    const handleNewContractStart = (e: any) => {
        setContractStartDate(e);

        const selectedDate = new Date(e);
        const currentDate = new Date();

        // Calculate the minimum allowed date (currentDate + 5 days)
        const minAllowedDate = contractEndDate ? new Date(contractEndDate) : new Date();
        if (minAllowedDate <= currentDate) {
            minAllowedDate.setDate(currentDate.getDate() + 6);
        }

        if (selectedDate < minAllowedDate) {
            setIsDateValid(false);
        } else {
            setIsDateValid(true);
            const value = dbDateFormat(selectedDate);
            setOfferData('contractRenewalDate', value);
            setDealData('contract.startDate', value);
        }
    };

    function handleCurrentSupplier(item: any) {
        const name = item?.value
        const id = getSupplierIdByName(suppliers, name);
        console.log(item);
        setOfferData('curentSupplier', name);
        setOfferData('currentSupplierName', name);
        setDealData('supplierId', id);
        setDealData('contract.currentSupplier', id);
        setDealData('contract.currentSupplierName', name);
        setSelectedSupplier(item);//customercare@
    }

    const handleCreate = (inputValue: string) => {
        const newOption = {
            label: inputValue,
            value: inputValue,
        };
        setSelectedSupplier(newOption);
        handleCurrentSupplier(newOption);
    }

    const handleConsumptionChange = (e: any) => {
        validate_field(e.target);
        const value = e.target.value;
        setOfferData('consumption.amount', value)
        setOfferData('consumption.day', value)
        setDealData('usage', {
            unit: value,
            day: value,
        })
    };
    const handleKvaChange = (e: any) => {
        validate_field(e.target);
        const value = e.target.value;
        setOfferData('consumption.kva', value)
        setDealData('usage.kva', value)
    };

    const handleCloseModal = () => {
        setShowModal(false);
    };

    const handleNextClick = () => {
        if (validateStep()) {
            saveDeal(function (resp: any) {
                setShowModal(true);
            })
        } else {
            alert('Fill all required fields with valid data.');
        }
    };

    function validateStep() {
        if (!validate_field('consumption.amount')) {
            return false;
        }

        if (!validate_field('consumption.kva')) {
            return false;
        }

        return offerData.currentSupplierName;
    }

    const getPrompt = () => {
        if (dealData.smeDetails.meterNumber && dealData.smeDetails.mpanTop) {
            const requestOptions = {
                method: 'POST',
                headers: {'Content-Type': 'application/json', Accept: 'application/json'},
                body: JSON.stringify({
                    meterNumber: dealData.smeDetails.meterNumber,
                    mpanTop: dealData.smeDetails.mpanTop,
                })
            };
            fetch('/api/powwr/get-prompt', requestOptions)
                .then(response => response.json())
                .then(resp => {
                    console.log(resp);
                    let _prompts = resp.data.ThePrompts || [];
                    setPrompts(_prompts);
                })
                .catch(error => {
                    console.error('Create deal error:', error);
                });
        }
    };

    useEffect(() => {
        if (dealData.contract.startDate && !dealData.contract.currentEndDate) {
            setContractEnded(true);
        }
        if (dealData.contract.currentEndDate) {
            // @ts-ignore
            const currentEndDate = dealData?.contract?.currentEndDate ? new Date(dealData?.contract?.currentEndDate) : null;
            // @ts-ignore
            setContractEndDate(currentEndDate);
        }
        if (dealData.contract.startDate) {
            // @ts-ignore
            const newStartDate = dealData?.contract?.startDate ? new Date(dealData?.contract?.startDate) : null;
            // @ts-ignore
            setContractStartDate(newStartDate);
        }
        if (offerData.currentSupplierName) {
            setSelectedSupplier({label: offerData.currentSupplierName, value: offerData.currentSupplierName});
        }
        if (dealData.smeDetails.meterNumber && dealData.smeDetails.mpanTop) {
            getPrompt();
        }
    }, []);

    // @ts-ignore
    return (
        <div>
            <div className={`d-block mb-4 md:mb-5`}>
                <h3 className="mb-3">When will your <b className="text-semibold">CONTRACT END?</b>
                    {/* <Tooltip title='title1'/> */}
                </h3>
                <div>
                    {contractEnded ? (
                        <label>
                            <input
                                type="text"
                                className="input-field disabled"
                                placeholder="Contract Ended"
                                disabled
                                value="Contract Ended"
                                onChange={handleDateChange}
                            />
                        </label>
                    ) : (
                        <label>
                            <DatePicker
                                className="input-field disabled"
                                selected={contractEndDate}
                                onChange={(date) => handleDateChange(date)}
                                placeholderText="Select date"
                                dateFormat="d/MM/y"
                                showMonthDropdown={true}
                                showYearDropdown={true}
                            />
                        </label>
                    )}
                    <br/>
                    <label className="mt-2">
                        <input
                            className="mr-1"
                            type="checkbox"
                            checked={contractEnded}
                            onChange={handleContractEndedChange}
                        />
                        My contract has already ended
                    </label>
                </div>
            </div>

            {(contractEnded || dealData.contract.currentEndDate) && (
                <div className={`d-block mb-4 md:mb-5`}>
                    <h3 className="mb-3 mt-3">
                        When should your new <b className="text-semibold">Contract Start?</b>
                    </h3>
                    <div className="mb-3">
                        <label>
                            <DatePicker
                                className="input-field disabled"
                                selected={contractStartDate}
                                onChange={(date) => handleNewContractStart(date)}
                                placeholderText="Select date"
                                dateFormat="d/MM/y"
                                shouldCloseOnSelect={true}
                                minDate={minContractStartDate()}
                                showMonthDropdown={true}
                                showYearDropdown={true}
                            />
                        </label>
                    </div>

                </div>
            )}

            {dealData.contract.startDate && (
                <div className={`d-block mb-4 md:mb-5 `}>
                    <h3 className="mb-3">
                        Who is your current <b className="text-semibold">{offerData.utilityType.toUpperCase()} SUPPLIER?</b>
                    </h3>
                    <CreatableSelect
                        isClearable
                        value={selectedSupplier}
                        name="currentSupplierName"
                        onChange={handleCurrentSupplier}
                        options={Object.keys(pricechange).map(supplier => ({
                            value: supplier,
                            label: supplier,
                        }))}
                        formatCreateLabel={(e) => {
                            return e;
                        }}
                        onCreateOption={handleCreate}
                        placeholder={`Select ${ucfirst(offerData.utilityType)} Supplier`}
                        className='custom-search-field text-left'
                    />
                </div>
            )}

            {offerData.currentSupplierName && (
                <>
                    <div className={`d-block mb-0`}>
                        <h3 className="mb-3">
                            Roughly, how much <b>{offerData.utilityType ? "electric" && 'Electricity' : 'Gas'}</b> do you use?
                        </h3>
                        <input
                            type="number"
                            className="input-field"
                            name='consumption.amount'
                            placeholder={`Enter usage in ${offerData.utilityType === 'electric' ? 'kWh' : 'units'}`}
                            value={offerData.consumption.amount}
                            onChange={handleConsumptionChange}
                            required={true}
                            min={10}
                            max={999999}
                        />

                        {(offerData.utilityType === 'electric' && offerData.halfHourly) && <><br/><input
                            type="number"
                            className="input-field mt-2"
                            name='consumption.kva'
                            placeholder={`Enter your kVA capacity `}
                            value={offerData.consumption.kva}
                            onChange={handleKvaChange}
                            required={true}
                            min={10}
                            max={999999}
                        /></>}

                        {/* {!isValidElectricSupplier && (
                        <p className="text-danger">Please enter a valid usage value between 10 and 10000.</p>
                      )} */}
                    </div>

                    {validateStep() && (
                        <div className={`d-block mt-3`} onClick={handleNextClick}>
                            <BlackButton title="Continue"/>
                        </div>
                    )}
                    <Modal show={showModal} onHide={handleCloseModal} size="lg" centered>
                        <Modal.Body className="p-5 text-center">
                            <h2 className="mb-4">Authorisation</h2>
                            <p className="mb-5">
                                In order to prepare your offers, we must gather your industry held consumption data and
                                meter number. We also need to conduct a credit search on your legal entity. All the
                                quotes are subject to suppliers' credit acceptance policy.<br/><br/> By agreeing to
                                proceed, you are authorizing Utility Box to access your data and prepare quotes based on
                                the information above. Can we proceed?
                            </p>
                            <p className="text-bold mb-4">Can we proceed?</p>

                            <div className="d-flex justify-content-center gap-3">
                                <Button onClick={() => setShowModal(false)} className="btn-blue border-none rounded-0"
                                        style={{paddingLeft: 45, paddingRight: 45, paddingTop: 12, paddingBottom: 12}}>
                                    No
                                </Button>
                                <Button
                                    onClick={onNext}
                                    className="btn-blue border-none rounded-0"
                                    style={{paddingLeft: 45, paddingRight: 45, paddingTop: 12, paddingBottom: 12}}
                                >
                                    Yes
                                </Button>
                            </div>
                        </Modal.Body>
                    </Modal>
                </>
            )}
        </div>
    );
};

export default ConsumptionDetailStep;
