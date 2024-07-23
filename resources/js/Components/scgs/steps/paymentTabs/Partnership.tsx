import React, {useState} from 'react';
import {dbDateFormat} from "@/utils/helper";
import DatePickerField from "@/Components/elements/DatePickerField";
import moment from "moment";

interface Props {
    dealData?: any,
    setData: (event: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => void,
    setDealData: (name: string, value: any) => void,
}

export default function Partnership({setData, dealData, setDealData}: Props) {
    const [partner1Dob, setPartner1Dob] = useState(null);
    const [partner2Dob, setPartner2Dob] = useState(null);

    return (
        <>
            {dealData.company.type === 'LimitedLiabilityPartnership' && <>
                <div className="grid grid-cols-3 gap-4 mb-4">
                    <div className={'w-full col-span-2 lg:col-span-1'}>
                        <label className='mb-2'>Partnership Name</label><br/>
                        <input
                            className="input-field"
                            type="text"
                            name="company.name"
                            title="Partnership Name*"
                            placeholder="Partnership Name*"
                            value={dealData.company?.name}
                            onChange={setData}
                        />
                    </div>
                </div>
                <div className={'w-full col-span-3 font-semibold mb-1'}>First Partner Details:</div>
                <div className="grid grid-cols-3 gap-4 mb-4">
                    <div className={'w-full col-span-3 lg:col-span-1'}>
                        <label className='mb-1'>First Name</label><br/>
                        <input
                            className="input-field-payment"
                            type="text"
                            required={true}
                            name="company.partner1.firstName"
                            title="First Name*"
                            placeholder="First Name*"
                            value={dealData.company?.partner1.firstName}
                            onChange={setData}
                        />
                    </div>
                    <div className={'w-full col-span-3 lg:col-span-1'}>
                        <label className='mb-1'>Last Name</label><br/>
                        <input
                            className="input-field-payment"
                            type="text"
                            required={true}
                            name="company.partner1.lastName"
                            title="First Name*"
                            placeholder="Last Name*"
                            value={dealData.company?.partner1.lastName}
                            onChange={setData}
                        />
                    </div>
                    <div className={'w-full col-span-3 lg:col-span-1 field-wrapper'}>
                        <label className='mb-1'>Date of Birth</label><br/>
                        <DatePickerField
                            className="input-field-payment"
                            value={dealData.company.partner1.dob}
                            placeholderText="Date of birth"
                            name="company.partner1.dob"
                            required={true}
                            maxDate={moment().subtract(18, 'years').toDate()}
                            onChange={(date: any) => {
                                setDealData('company.partner1.dob', dbDateFormat(date))
                            }}/>
                    </div>
                </div>
                <div className="grid md:grid-cols-2 mt-4 gap-4">
                    <div className={'w-full mb-1 field-wrapper'}>
                        <label className='mb-1'>Address Line 1</label><br/>
                        <input
                            className="input-field-payment"
                            type="text"
                            name="company.partner1.buildingNumber"
                            required={true}
                            placeholder="Enter Address Line 1"
                            value={dealData.company?.partner1?.buildingNumber}
                            onChange={setData}
                        />
                    </div>
                    <div className={'w-full mb-1 field-wrapper'}>
                        <label className='mb-1'>Address Line 2</label><br/>
                        <input
                            className="input-field-payment"
                            type="text"
                            name="company.partner1.buildingName"
                            required={true}
                            placeholder="Enter Address Line 2"
                            value={dealData.company?.partner1?.buildingName}
                            onChange={setData}
                        />
                    </div>
                    {/*<div className={'w-full mb-1 field-wrapper'}>
                        <label className='mb-1'>Thoroughfare Name</label><br/>
                        <input
                            className="input-field-payment"
                            type="text"
                            name="company.partner1.thoroughfareName"
                            title="Thoroughfare Name*"
                            required={true}
                            placeholder="Enter Thoroughfare Name"
                            value={dealData.company?.partner1?.thoroughfareName}
                            onChange={setData}
                        />
                    </div>*/}
                    <div className={'w-full mb-1 field-wrapper'}>
                        <label className='mb-1'>Post Town</label><br/>
                        <input
                            className="input-field-payment"
                            type="text"
                            name="company.partner1.postTown"
                            title="Post Town*"
                            placeholder="Enter Post Town"
                            value={dealData.company?.partner1?.postTown}
                            onChange={setData}
                        />
                    </div>
                    <div className={'w-full mb-1 field-wrapper'}>
                        <label className='mb-1'>County</label><br/>
                        <input
                            className="input-field-payment"
                            type="text"
                            name="company.partner1.county"
                            title="County*"
                            placeholder="Enter County"
                            required={true}
                            value={dealData.company?.partner1?.county}
                            onChange={setData}
                        />
                    </div>
                    <div className={'w-full mb-1 field-wrapper'}>
                        <label className='mb-1'>Post Code</label><br/>
                        <input
                            className="input-field-payment"
                            type="text"
                            name="company.partner1.postcode"
                            title="PostCode*"
                            required={true}
                            placeholder="Enter PostCode"
                            value={dealData.company?.partner1?.postcode}
                            onChange={setData}
                        />
                    </div>
                </div>
                <div className={'w-full col-span-3 font-semibold  mb-1'}>Second Partner Details:</div>
                <div className="grid grid-cols-3 gap-4 mb-4">
                    <div className={'w-full col-span-3 lg:col-span-1'}>
                        <label className='mb-1'>First Name</label><br/>
                        <input
                            className="input-field-payment"
                            type="text"
                            required={true}
                            name="company.partner2.firstName"
                            title="First Name*"
                            placeholder="First Name*"
                            value={dealData.company?.partner2.firstName}
                            onChange={setData}
                        />
                    </div>
                    <div className={'w-full col-span-3 lg:col-span-1'}>
                        <label className='mb-1'>Last Name</label><br/>
                        <input
                            className="input-field-payment"
                            type="text"
                            required={true}
                            name="company.partner2.lastName"
                            title="First Name*"
                            placeholder="Last Name*"
                            value={dealData.company?.partner2.lastName}
                            onChange={setData}
                        />
                    </div>
                    <div className={'w-full col-span-3 lg:col-span-1 field-wrapper'}>
                        <label className='mb-1'>Date of Birth</label><br/>
                        <DatePickerField
                            className="input-field-payment"
                            value={dealData.company.partner2.dob}
                            placeholderText="Date of birth"
                            name="company.partner2.dob"
                            required={true}
                            dateFormat="dd/MM/yyyy"
                            maxDate={moment().subtract(18, 'years').toDate()}
                            onChange={(date: any) => {
                                setDealData('company.partner2.dob', dbDateFormat(date))
                            }}/>
                    </div>
                </div>
                <div className="grid md:grid-cols-2 mt-4 gap-4">
                    <div className={'w-full mb-1 field-wrapper'}>
                        <label className='mb-1'>Address Line 1</label><br/>
                        <input
                            className="input-field-payment"
                            type="text"
                            name="company.partner2.buildingNumber"
                            required={true}
                            placeholder="Enter Address Line 1"
                            value={dealData.company?.partner2?.buildingNumber}
                            onChange={setData}
                        />
                    </div>
                    <div className={'w-full mb-1 field-wrapper'}>
                        <label className='mb-1'>Address Line 2</label><br/>
                        <input
                            className="input-field-payment"
                            type="text"
                            name="company.partner2.buildingName"
                            required={true}
                            placeholder="Enter Address Line 2"
                            value={dealData.company?.partner2?.buildingName}
                            onChange={setData}
                        />
                    </div>
                    {/*<div className={'w-full mb-1 field-wrapper'}>
                        <label className='mb-1'>Thoroughfare Name</label><br/>
                        <input
                            className="input-field-payment"
                            type="text"
                            name="company.partner2.thoroughfareName"
                            title="Thoroughfare Name*"
                            required={true}
                            placeholder="Enter Thoroughfare Name"
                            value={dealData.company?.partner2?.thoroughfareName}
                            onChange={setData}
                        />
                    </div>*/}
                    <div className={'w-full mb-1 field-wrapper'}>
                        <label className='mb-1'>Post Town</label><br/>
                        <input
                            className="input-field-payment"
                            type="text"
                            name="company.partner2.postTown"
                            placeholder="Enter Post Town"
                            value={dealData.company?.partner2?.postTown}
                            onChange={setData}
                        />
                    </div>
                    <div className={'w-full mb-1 field-wrapper'}>
                        <label className='mb-1'>County</label><br/>
                        <input
                            className="input-field-payment"
                            type="text"
                            name="company.partner2.county"
                            placeholder="Enter County"
                            required={true}
                            value={dealData.company?.partner2?.county}
                            onChange={setData}
                        />
                    </div>
                    <div className={'w-full mb-1 field-wrapper'}>
                        <label className='mb-1'>Post Code</label><br/>
                        <input
                            className="input-field-payment"
                            type="text"
                            name="company.partner2.postcode"
                            title="PostCode*"
                            required={true}
                            placeholder="Enter PostCode"
                            value={dealData.company?.partner2?.postcode}
                            onChange={setData}
                        />
                    </div>
                </div>
            </>}
        </>
    );
}
