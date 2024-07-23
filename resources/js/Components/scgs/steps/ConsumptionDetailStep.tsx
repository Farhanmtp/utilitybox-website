import React, {useEffect, useState} from 'react';
import {BlackButton} from '../../elements/BlackButton';
import {dbDateFormat, ucfirst, validate_field} from "@/utils/helper";
import {Button, Modal} from 'react-bootstrap';
import DatePicker from "react-datepicker";
// import Select from 'react-select';
import CreatableSelect from 'react-select/creatable';


import "react-datepicker/dist/react-datepicker.css";
import moment from "moment";
import Tooltip from "@/Components/elements/Tooltip";

interface ConsumptionDetailProps {
    onNext: () => void;
    offerData?: any,
    setOfferData: (name: string, value: any) => void,
    dealData?: any,
    setDealData: (name: string, value: any) => void,
    saveDeal: (calback: any, failed?: any) => void,
    pricechange: any;
}

export default function ConsumptionDetailStep({
                                                  onNext,
                                                  offerData,
                                                  setOfferData,
                                                  dealData,
                                                  setDealData,
                                                  saveDeal,
                                                  pricechange
                                              }: ConsumptionDetailProps) {
    const [contractEndDate, setContractEndDate] = useState(null);
    const [contractStartDate, setContractStartDate] = useState(null);
    const [selectedSupplier, setSelectedSupplier] = useState<any>();
    const [showModal, setShowModal] = useState(false);
    const [prompts, setPrompts] = useState<any>([]);

    function _updateEndDate(date: any) {
        const value = date ? dbDateFormat(date) : '';
        setOfferData('contractEndDate', value);
        setDealData('contract.currentEndDate', value);
    }

    function _updateContractEnded(status: boolean) {
        setOfferData('contractEnded', status);
        setDealData('contract.ended', status);
    }

    function _updateStartDate(date: any) {
        const value = date ? dbDateFormat(date) : '';
        setOfferData('contractRenewalDate', value);
        setDealData('contract.startDate', value);
    }

    function handleCurrentSupplier(item: any) {
        setSelectedSupplier(item);
        const name = item?.value
        setOfferData('currentSupplier', name);
        setDealData('contract.currentSupplier', name);

        if (offerData.contractRenewalDate || dealData.contract.startDate) {
            const endDate = contractEndDate ? new Date(contractEndDate) : new Date();
            if (name == 'Scottish Power' && moment(endDate).date() != 1) {
                const dt = moment(endDate).add(1, 'month').startOf('month').toDate();
                handleNewContractStart(dt)
            }
        }
    }

    const minContractStartDate = () => {
        const currentDate = new Date();
        const endDate = contractEndDate ? new Date(contractEndDate) : new Date();

        if (offerData.currentSupplier == 'Scottish Power' && moment(endDate).date() != 1) {
            return moment(endDate).add(1, 'month').startOf('month').toDate();
        }

        if (endDate <= currentDate) {
            endDate.setDate(endDate.getDate() + 6); // Add 6 days
        }

        return endDate;
    };

    const handleContractEndDate = (e: any) => {
        setContractEndDate(e);

        const isScottish = offerData.currentSupplier == 'Scottish Power';

        let selectedDate = new Date(e);
        let currentDate = new Date();

        // @ts-ignore
        let newDate = moment(selectedDate < currentDate ? currentDate : selectedDate);

        if (selectedDate < currentDate) {
            selectedDate = currentDate;

            setContractEndDate(null);

            _updateContractEnded(true);
            _updateEndDate(null);

            newDate = newDate.add(6, 'day');
        } else {
            _updateContractEnded(false);
            _updateEndDate(selectedDate);
            newDate = newDate.add(1, 'day');
        }

        if (isScottish && moment(selectedDate).date() != 1) {
            newDate = moment(selectedDate).add(1, 'month').startOf('month');
        }

        // @ts-ignore
        handleNewContractStart(newDate.toDate());

        setOfferData('consumption.amount', '')
        setOfferData('consumption.day', '')
        setDealData('usage.day', '')
    };
    const handleContractEnded = (event: React.ChangeEvent<HTMLInputElement>) => {
        const isEnded = event.target.checked;
        setOfferData('contractEnded', isEnded);
        setDealData('contract.ended', isEnded);

        setContractEndDate(null);
        if (isEnded) {
            _updateEndDate(null);

            let currentDate = new Date();

            currentDate.setDate(currentDate.getDate() + 6);
            if (offerData.currentSupplier == 'Scottish Power' && moment().date() != 1) {
                currentDate = moment().add(1, 'month').startOf('month').toDate();
            }
            // @ts-ignore
            handleNewContractStart(currentDate);
        }
    };

    const handleNewContractStart = (e: any) => {
        setContractStartDate(e);
        _updateStartDate(e)
    };

    const handleCreate = (inputValue: string) => {
        const newOption = {
            label: inputValue,
            value: inputValue,
        };
        setSelectedSupplier(newOption);
        handleCurrentSupplier(newOption);
    }

    const handleDayConsumptionChange = (e: any) => {
        validate_field(e.target);
        const value = e.target.value;
        setOfferData('consumption.amount', value)
        setOfferData('consumption.day', value)
        setDealData('usage', {
            unit: value,
            day: value,
        })
    };

    const handleNightConsumptionChange = (e: any) => {
        validate_field(e.target);
        const value = e.target.value;
        setOfferData('consumption.night', value)
        setDealData('usage.night', value)
    };
    const handleWeekendConsumptionChange = (e: any) => {
        validate_field(e.target);
        const value = e.target.value;
        setOfferData('consumption.wend', value)
        setDealData('usage.weekend', value)
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

        return offerData.currentSupplier;
    }

    const getPrompt = () => {
        if (offerData.meterNumber && offerData.mpanTop) {
            fetch('/api/powwr/get-prompt', {
                method: 'POST',
                headers: {'Content-Type': 'application/json', Accept: 'application/json'},
                body: JSON.stringify({
                    meterNumber: offerData.meterNumber,
                    mpanTop: offerData.mpanTop,
                })
            })
                .then(response => response.json())
                .then(resp => {
                    let _prompts = resp.data.ThePrompts || [];
                    setOfferData('prompts', _prompts);
                    setPrompts(_prompts);
                })
                .catch(error => {
                    console.error('Create deal error:', error);
                });
        }
    };

    useEffect(() => {
        if (offerData.contractEndDate) {
            // @ts-ignore
            const currentEndDate = offerData?.contractEndDate ? new Date(offerData?.contractEndDate) : null;
            // @ts-ignore
            setContractEndDate(currentEndDate);
        }
        if (offerData.contractRenewalDate) {
            // @ts-ignore
            const newStartDate = offerData?.contractRenewalDate ? new Date(offerData?.contractRenewalDate) : null;
            // @ts-ignore
            setContractStartDate(newStartDate);
        }
        if (offerData.currentSupplier) {
            setSelectedSupplier({
                label: offerData.currentSupplier,
                value: offerData.currentSupplier,
            });
        }
    }, []);

    useEffect(() => {
        if (offerData.meterNumber && offerData.mpanTop) {
            getPrompt();
        }
    }, [offerData.meterNumber, offerData.mpanTop]);

    console.log(prompts);

    // @ts-ignore
    return (
        <div>
            <div className={`d-block mb-4 md:mb-5 `}>
                <h3 className="mb-3">
                    Who is your current <b className="text-semibold">{offerData.utilityType.toUpperCase()} SUPPLIER?</b>
                </h3>
                <CreatableSelect
                    isClearable
                    value={selectedSupplier}
                    name="currentSupplier"
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

            <div className={`d-block mb-4 md:mb-5`}>
                <h3 className="mb-3">When will your <b className="text-semibold">CONTRACT END?</b></h3>
                <div>
                    {offerData.contractEnded ? (
                        <input
                            type="text"
                            className="input-field disabled"
                            placeholder="Contract Ended"
                            disabled
                            value="Contract Ended"
                        />
                    ) : (
                        <div className="inline-block">
                            <DatePicker
                                className="input-field disabled"
                                selected={contractEndDate}
                                onChange={(date) => handleContractEndDate(date)}
                                placeholderText="Select date"
                                dateFormat="d/MM/y"
                                showMonthDropdown={true}
                                showYearDropdown={true}
                            />
                        </div>
                    )}
                    <br/>
                    <label className="mt-2">
                        <input
                            className="mr-1"
                            type="checkbox"
                            checked={offerData.contractEnded}
                            onChange={handleContractEnded}
                        />
                        My contract has already ended
                    </label>
                </div>
            </div>

            {(offerData.contractEnded || offerData.contractEndDate) && (
                <div className={`d-block mb-4 md:mb-5`}>
                    <h3 className="mb-3 mt-3">
                        When should your new <b className="text-semibold">Contract Start?</b>
                        {offerData.currentSupplier == 'Scottish Power' &&
                            <Tooltip>Scottish Power contract will start on 1st date of month</Tooltip>}
                    </h3>
                    <div className="mb-3">
                        <div className="inline-block">
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

                        </div>
                    </div>
                </div>
            )}
            {offerData.contractRenewalDate && (
                <>
                    <div className={`d-block mb-0`}>
                        <h3 className="mb-3">
                            Roughly, how much <b>{offerData.utilityType ? "electric" && 'Electricity' : 'Gas'}</b> do
                            you use?
                        </h3>

                        <input
                            type="number"
                            className="input-field"
                            name='consumption.amount'
                            placeholder={`Enter ${prompts.length ? 'day' : ''} usage in ${offerData.utilityType === 'electric' ? 'kWh' : 'units'}`}
                            title={`Enter ${prompts.length ? 'day' : ''} usage in ${offerData.utilityType === 'electric' ? 'kWh' : 'units'}`}
                            value={offerData.consumption.amount}
                            onChange={handleDayConsumptionChange}
                            required={true}
                            min={10}
                            max={999999}
                        />
                        {(prompts.length && prompts.indexOf('Night') != -1) ? <>
                            <br/><input
                            type="number"
                            className="input-field"
                            name='consumption.night'
                            placeholder={`Enter night usage in ${offerData.utilityType === 'electric' ? 'kWh' : 'units'}`}
                            title={`Enter night usage in ${offerData.utilityType === 'electric' ? 'kWh' : 'units'}`}
                            value={offerData.consumption.night}
                            onChange={handleNightConsumptionChange}
                            min={0}
                            max={999999}
                        />
                        </> : ''}
                        {(prompts.length && prompts.indexOf('Weekend') != -1) ? <>
                            <br/>
                            <input
                                type="number"
                                className="input-field"
                                name='consumption.wend'
                                placeholder={`Enter weekend usage in ${offerData.utilityType === 'electric' ? 'kWh' : 'units'}`}
                                title={`Enter weekend usage in ${offerData.utilityType === 'electric' ? 'kWh' : 'units'}`}
                                value={offerData.consumption.wend}
                                onChange={handleWeekendConsumptionChange}
                                min={0}
                                max={999999}
                            />
                        </> : ''}

                        {(offerData.utilityType === 'electric' && offerData.halfHourly) && <><br/><input
                            type="number"
                            className="input-field mt-2"
                            name='consumption.kva'
                            placeholder={`Enter your kVA capacity `}
                            value={offerData.consumption.kva}
                            onChange={handleKvaChange}
                            required={true}
                            min={0}
                            max={999999}
                        /></>}

                        {/* {!isValidElectricSupplier && (
                        <p className="text-danger">Please enter a valid usage value between 10 and 10000.</p>
                      )} */}
                    </div>

                    {validateStep() && (
                        <div className={`d-block mt-3`}>
                            <span onClick={handleNextClick}>
                                <BlackButton title="Continue"/>
                            </span>
                        </div>
                    )}
                    <Modal show={showModal} onHide={handleCloseModal} size="lg" centered>
                        <Modal.Body className="p-5 text-center">
                            <h2 className="mb-4">Authorisation</h2>
                            <p className="mb-5">
                                In order to prepare your offers, we must gather your industry held consumption data and
                                meter number. We also need to conduct a credit search on your legal entity. All the
                                quotes are subject to suppliers' credit acceptance policy.<br/><br/> By agreeing to
                                proceed, you are authorising Utility Box to access your data and prepare quotes based on
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
