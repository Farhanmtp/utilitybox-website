import React, {useEffect, useState} from 'react';
import {format_date} from "@/utils/helper";
import moment from "moment";
import DatePickerField from "@/Components/elements/DatePickerField";

interface Props {
    dealData?: any,
    setData: (event: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => void,
    skipPayment: number
    setSkipPayment: (skipPayment: number) => void,
    setDealData: (name: string, value: any) => void,
}

export default function PaymentDetails({setData, dealData, setSkipPayment, skipPayment, setDealData}: Props) {
    const [showCheckbox, setShowCheckbox] = useState(false);
    const [needPayment, setNeedPayment] = useState<number>(1);
    const [addressRequired, setAddressRequired] = useState<boolean>(false);

    const ddSuppliers = [
        'Drax',
        'Opus',
        'SEFE Energy',
        'Scottish And Southern',
        'TotalEnergies',
        'Yorkshire Gas and Power',
        'Yu Energy',
    ]

    useEffect(() => {
        console.log(dealData.quoteDetails.Supplier);
        if (dealData.quoteDetails.Supplier && ddSuppliers.indexOf(dealData.quoteDetails.Supplier) != -1) {
            setAddressRequired(true);
        } else {
            setAddressRequired(false)
        }
    }, []);

    useEffect(() => {
        if (
            dealData.quoteDetails.Supplier === "British Gas" ||
            dealData.quoteDetails.Supplier === "British Gas Lite" ||
            dealData.quoteDetails.Supplier === "Scottish And Southern" ||
            dealData.quoteDetails.Supplier === "Smartest Energy" ||
            dealData.quoteDetails.Supplier === "Scottish Power" ||
            dealData.quoteDetails.Supplier === "POZITIVE-001-S" ||
            dealData.quoteDetails.Supplier === "EDF Energy"
        ) {
            if (dealData.contract.currentSupplier === dealData.quoteDetails.Supplier) {
                setShowCheckbox(true);
            }
        }
    }, [dealData.quoteDetails.Supplier, dealData.contract.currentSupplier]);

    const [dateOfBirth, setDateOfBirth] = useState(null);

    const handleDDStart = (date: any) => {
        setDateOfBirth(date);
        // const value = date ? dbDateFormat(date) : '';
        // setDealData('customer.dateOfBirth', value || '');
    };

    return (
        <>
            <div className="grid grid-cols-2 gap-4">
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

                {showCheckbox && (
                    <div className={'col-span-2 font-bold'}>
                        <label>
                            <input
                                type="checkbox"
                                checked={dealData.paymentDetail.usePreviousDirectDebitDetails}
                                onChange={(e) => {
                                    const checked = e.target.checked ? 1 : 0;
                                    setNeedPayment(checked ? 0 : 1);
                                    setSkipPayment(checked ? 0 : 1);
                                    setDealData('paymentDetail.usePreviousDirectDebitDetails', (checked ? 1 : 0))
                                }}
                                className='mx-2'
                            />
                            By Agreeing to this we will be using your previous contract direct debit details.
                        </label>
                    </div>
                )}
                {dealData.quoteDetails.Supplier == 'EDF Energy' && <>
                    <div className={'w-full mb-1'}>
                        <label className='mb-2'>Direct Debit Start Date</label><br/>
                        <DatePickerField
                            className="input-field-payment"
                            value={dealData.paymentDetail?.directDebitDayOfMonth}
                            placeholderText="Direct Debit Start Date"
                            name="customer.dateOfBirth"
                            required={true}
                            dateFormat="dd/MM/yyyy"
                            minDate={moment().startOf('month').toDate()}
                            onChange={(date: any) => {
                                const value = date ? format_date(date, 'db') : '';
                                setDealData('paymentDetail.directDebitDayOfMonth', value);
                            }}
                        />
                    </div>
                </>}

                {!dealData.paymentDetail.usePreviousDirectDebitDetails && (
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
                                //required={addressRequired}
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
                                //onChange={setData}
                                onChange={(e) => {
                                    e.target.value = e.target.value.slice(0, 6)
                                    setData(e)
                                }}
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
                                //onChange={setData}
                                onChange={(e) => {
                                    e.target.value = e.target.value.slice(0, 8)
                                    setData(e)
                                }}
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
                        required={addressRequired}
                        placeholder="Enter Post Code"
                        value={dealData.bankAddress?.postcode}
                        onChange={setData}
                    />
                </div>


            </div>
        </>
    );
};
