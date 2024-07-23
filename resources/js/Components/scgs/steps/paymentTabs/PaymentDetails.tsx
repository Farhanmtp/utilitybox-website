import React, {useEffect, useState} from 'react';
import {range} from "@/utils/helper";
import DatePicker from "react-datepicker";

interface Props {
    dealData?: any,
    setData: (event: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => void,
    skipPayment: number
    setSkipPayment: (skipPayment: number) => void
    // setDealData: (name: string, value: any) => void,
}

export default function PaymentDetails({setData, dealData, setSkipPayment, skipPayment}: Props) {
    const [hideCheckbox, setHideCheckbox] = useState<number>(0);
    const [skipValid, setSkipValid] = useState<number>(0);
    const [needPayment, setNeedPayment] = useState<number>(1);

    useEffect(() => {
        if (
            dealData.contract.newSupplier === "BRITISHG-001-S" ||
            dealData.contract.newSupplier === "BRITISHG-002-S" ||
            dealData.contract.newSupplier === "SSE-001-S" ||
            dealData.contract.newSupplier === "SMARTEST-001-S" ||
            dealData.contract.newSupplier === "SCOTTISH-001-S" ||
            dealData.contract.newSupplier === "POZITIVE-001-S" ||
            dealData.contract.newSupplier === "EON-001-S"
        ) {
            if (dealData.contract.currentSupplier === dealData.contract.newSupplier) {
                setSkipValid(0);
                setHideCheckbox(1);
                // setSkipPayment(1);
            } else {
                // setSkipValid(0);
                // setSkipPayment(0);
            }
        } else {
            // setSkipValid(0);
            // setSkipPayment(0);
        }
    }, [dealData.contract.newSupplier, dealData.contract.currentSupplier]);

    const [dateOfBirth, setDateOfBirth] = useState(null);

    const handleDDStart = (date: any) => {
        setDateOfBirth(date);
        // const value = date ? dbDateFormat(date) : '';
        // setDealData('customer.dateOfBirth', value || '');
    };


    return (
        <>
            <div className="grid grid-cols-2 gap-4">
                {/* <div className={'col-span-2'}>
                    <label>Do you wnat to skip this</label>
                    <div className={'w-full mb-1'}>
                        <label className={`btn-radio ${skipPayment == 1 ? 'active' : ''}`}>
                            <input
                                className='ml-0' type="radio" name="skipPayment"
                                value="1"
                                checked={skipPayment === 1} onChange={() => {
                                setSkipPayment(1)
                            }}
                            /> Yes </label>

                        <label className={`btn-radio ${skipPayment == 0 ? 'active' : ''}`}>
                            <input
                                className='ml-0' type="radio" name="skipPayment"
                                value="0"
                                checked={skipPayment === 0} onChange={() => {
                                setSkipPayment(0)
                            }}
                            /> No </label>
                    </div>
                </div> */}
                {/*<div className={'col-span-2'}>
                    <div className={'w-full mb-1'}>
                        <label
                            className={`btn-radio ${dealData.paymentDetail.method == 'MonthlyDirectDebit' ? 'active' : ''}`}>
                            <input
                                className='ml-0' type="radio" name="paymentDetail.method"
                                value="MonthlyDirectDebit"
                                checked={dealData.paymentDetail.method === 'MonthlyDirectDebit'}
                                onChange={setData}
                            /> Monthly Direct Debit </label>

                        <label
                            className={`btn-radio ${dealData.paymentDetail.method == 'FixedDirectDebit' ? 'active' : ''}`}>
                            <input
                                className='ml-0' type="radio" name="paymentDetail.method"
                                value="FixedDirectDebit"
                                checked={dealData.paymentDetail.method === 'FixedDirectDebit'}
                                onChange={setData}
                            /> Fixed Direct Debit </label>

                        <label
                            className={`btn-radio ${dealData.paymentDetail.method == 'VariableDirectDebit' ? 'active' : ''}`}>
                            <input
                                className='ml-0' type="radio" name="paymentDetail.method"
                                value="VariableDirectDebit"
                                checked={dealData.paymentDetail.method === 'VariableDirectDebit'} onChange={setData}
                            /> Variable Direct Debit </label>
                    </div>
                </div>*/}

                {hideCheckbox === 1 && (
                    <label>
                        <input
                            type="checkbox"
                            checked={skipValid === 1}
                            onChange={(e) => {
                                setSkipValid(e.target.checked ? 1 : 0);
                                setNeedPayment(e.target.checked ? 0 : 1);
                                setSkipPayment(e.target.checked ? 0 : 1);
                            }}
                            className='mx-2'
                        />
                        By Agreeing to this we will be using your previous contract direct debit details.
                    </label>
                )}

                {dealData.supplierId == 'EDF-001-S' && <>
                    <div className={'w-full mb-1'}>
                        <select
                            value={dealData.paymentDetail?.directDebitDayOfMonth}
                            onChange={setData}
                            required={true}
                            name="paymentDetail.directDebitDayOfMonth"
                            className="input-field"
                        >
                            <option value="">Direct Debit DayOfMonth</option>
                            {range(1, 31).map((item: number) => (
                                <option value={item}>{item}</option>
                            ))}
                        </select>
                    </div>

                </>}

                {needPayment === 1 && (
                    <>
                        <div className={'col-span-2 font-bold'}>Account Details:</div>

                        <div className={'w-full mb-1 col-span-2 md:col-span-1 '}>
                            <label className='mb-2'>Bank Name</label><br/>
                            <input
                                className="input-field"
                                type="text"
                                required={!skipPayment}
                                name="bankDetails.name"
                                title="Bank Name*"
                                placeholder="Enter Bank Name"
                                value={dealData.bankDetails?.name}
                                onChange={setData}
                            />
                        </div>

                        <div className={'w-full mb-1 col-span-2 md:col-span-1 '}>
                            <label className='mb-2'>Branch Name</label><br/>
                            <input
                                className="input-field"
                                type="text"
                                name="bankDetails.branchName"
                                title="Branch Name*"
                                placeholder="Enter Branch Name"
                                value={dealData.bankDetails?.branchName}
                                onChange={setData}
                            />
                        </div>

                        <div className={'w-full mb-1 col-span-2 md:col-span-1 '}>
                            <label className='mb-2'>Sort Code</label><br/>
                            <input
                                className="input-field"
                                type="text"
                                required={!skipPayment}
                                name="bankDetails.sortCode"
                                title="Sort Code*"
                                placeholder="Enter Sort Code"
                                value={dealData.bankDetails?.sortCode}
                                onChange={setData}
                                maxLength={6}
                            />
                        </div>
                        <div className={'w-full mb-1 col-span-2 md:col-span-1 '}>
                            <label className='mb-2'>Account Number</label><br/>
                            <input
                                className="input-field"
                                type="text"
                                required={!skipPayment}
                                name="bankDetails.accountNumber"
                                title="Account Number*"
                                placeholder="Enter Account Number"
                                value={dealData.bankDetails?.accountNumber}
                                onChange={setData}
                                maxLength={8}
                            />
                        </div>
                        <div className={'w-full mb-1 col-span-2 md:col-span-1 '}>
                            <label className='mb-2'>Account Name</label><br/>
                            <input
                                className="input-field"
                                type="text"
                                required={!skipPayment}
                                name="bankDetails.accountName"
                                title="Account Name*"
                                placeholder="Enter Account Name"
                                value={dealData.bankDetails?.accountName}
                                onChange={setData}
                            />
                        </div>
                    </>
                )}


                <div className={'col-span-2 font-bold'}>Bank Address:</div>
                <div className={'w-full mb-1 col-span-2 md:col-span-1'}>
                    <label className='mb-2'>Address Line 1</label><br/>
                    <input
                        className="input-field"
                        type="text"
                        name="bankAddress.buildingNumber"
                        placeholder="Enter Address Line 1"
                        value={dealData.bankAddress?.buildingNumber}
                        onChange={setData}
                    />
                </div>
                <div className={'w-full mb-1 col-span-2 md:col-span-1'}>
                    <label className='mb-2'>Address Line 2</label><br/>
                    <input
                        className="input-field"
                        type="text"
                        name="bankAddress.buildingName"
                        placeholder="Enter Address Line 2"
                        value={dealData.bankAddress?.buildingName}
                        onChange={setData}
                    />
                </div>
                <div className={'w-full mb-1 col-span-2 md:col-span-1'}>
                    <label className='mb-2'>Post Town</label><br/>
                    <input
                        className="input-field"
                        type="text"
                        name="bankAddress.postTown"
                        placeholder="Enter Post Town"
                        value={dealData.bankAddress?.postTown}
                        onChange={setData}
                    />
                </div>
                <div className={'w-full mb-1 col-span-2 md:col-span-1'}>
                    <label className='mb-2'>County</label><br/>
                    <input
                        className="input-field"
                        type="text"
                        name="bankAddress.county"
                        placeholder="Enter County"
                        value={dealData.bankAddress?.county}
                        onChange={setData}
                    />
                </div>
                <div className={'w-full mb-1 col-span-2 md:col-span-1'}>
                    <label className='mb-2'>Post Code</label><br/>
                    <input
                        className="input-field"
                        type="text"
                        name="bankAddress.postcode"
                        title="PostCode*"
                        placeholder="Enter Post Code"
                        value={dealData.bankAddress?.postcode}
                        onChange={setData}
                    />
                </div>

                {dealData?.contract?.currentSupplier == "EDF-001-S" && (
                    <div className={'w-full mb-1'}>
                        <label className='mb-2'>Direct Debit Start Date</label><br/>
                        <DatePicker
                            className="input-field disabled"
                            // selected={directDebitStart}
                            onChange={(date) => handleDDStart(date)}
                            // required={dobRequired()}
                            title="Date of birth"
                            placeholderText="Date of birth"
                            dateFormat="dd/MM/yyyy"
                            shouldCloseOnSelect={true}
                        />
                    </div>
                )}


            </div>
        </>
    );
};
