import React, {useEffect, useState} from 'react';
import DatePicker from "react-datepicker";
import {dbDateFormat, format_date} from "@/utils/helper";
import Tooltip from '@/Components/elements/Tooltip';
import moment from "moment/moment";
import DatePickerField from "@/Components/elements/DatePickerField";

interface Props {
    dealData?: any,
    isNewCompany: () => boolean,
    dobRequired: (check_value?: boolean) => boolean,
    setData: (event: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => void,
    setDealData: (name: string, value: any) => void,
}


export default function ContactDetails({setData, dealData, isNewCompany, dobRequired, setDealData}: Props) {

    const [moveInDate, setMoveInDate] = useState(null);

    const jobTitles = [
        'Executive Board',
        'Head of Purchasing',
        'Head of Sales',
        'Head of Personnel',
        'Fin. Accounting Manager',
        'Marketing Manager',
        'Marketing Manager',
        'Proprietor / Owner',
        'Partner',
        'Director',
        'Other',
    ];
    const contactPrefs = [
        'Email',
        'Phone',
        'SMS',
        'Post'
    ];

    const setContactPreference = (event: React.ChangeEvent<HTMLInputElement>) => {
        const checked = event.target.checked;
        const value = event.target.value;
        let contactPreference = dealData.customer.contactPreference ? dealData.customer.contactPreference.split(',') : [];
        const index = contactPreference.indexOf(value);
        if (checked) {
            if (index == -1) {
                contactPreference.push(value);
            }
        } else {
            if (index != -1) {
                contactPreference.splice(index, 1);
            }
        }
        setDealData('customer.contactPreference', contactPreference.join(','));
    }

    const handleDobChange = (date: any) => {
        const value = date ? format_date(date, 'db') : '';
        setDealData('customer.dateOfBirth', value);
    };
    const handleMoveInDateChange = (date: any) => {
        setMoveInDate(date);
        const value = date ? dbDateFormat(date) : '';
        setDealData('customer.moveInDate', value || '');
    };

    function moveInDateRequired() {
        if (!dealData?.customer?.moveInDate && (dealData?.company?.type !== 'Limited' || isNewCompany())) {
            return true;
        }
        return false;
    }

    function addressRequired() {
        if (dealData?.company?.type !== 'Limited' || isNewCompany()) {
            return true;
        }
        return false;
    }

    useEffect(() => {
        if (dealData?.customer?.moveInDate) {
            const mid: any = new Date(dealData?.customer?.moveInDate);
            setMoveInDate(mid);
        } else {
            setMoveInDate(null);
        }
    }, []);
    return (
        <>
            <div className="grid md:grid-cols-2 gap-4">
                <div className={'col-span-2'}>
                    <label className='mb-1'>Title</label><br/>
                    <div className={'w-full mb-1 field-wrapper'}>
                        <label className={`btn-radio ${dealData.customer.title == 'Mr' ? 'active' : ''}`}>
                            <input
                                className='ml-0' type="radio" name="customer.title"
                                value="Mr"
                                checked={dealData.customer.title === 'Mr'} onChange={setData}
                            /> Mr </label>

                        <label className={`btn-radio ${dealData.customer.title == 'Mrs' ? 'active' : ''}`}>
                            <input
                                className='ml-0' type="radio" name="customer.title"
                                value="Mrs"
                                checked={dealData.customer.title === 'Mrs'} onChange={setData}
                            /> Mrs </label>

                        <label className={`btn-radio ${dealData.customer.title == 'Miss' ? 'active' : ''}`}>
                            <input
                                className='ml-0' type="radio" name="customer.title"
                                value="Miss"
                                checked={dealData.customer.title === 'Miss'} onChange={setData}
                            /> Miss </label>

                        <label className={`btn-radio ${dealData.customer.title == 'Dr' ? 'active' : ''}`}>
                            <input
                                className='ml-0' type="radio" name="customer.title"
                                value="Dr"
                                checked={dealData.customer.title === 'Dr'} onChange={setData}
                            /> Dr </label>
                    </div>
                </div>

                <div className={'w-full col-span-2 lg:col-span-1 mb-1 field-wrapper'}>
                    <label className='mb-1'>Job Title</label><br/>
                    <select
                        value={dealData.customer?.jobTitle}
                        onChange={setData}
                        required={true}
                        name="customer.jobTitle"
                        className="input-field-payment"
                    >
                        <option value="">Select Job Title*</option>
                        {jobTitles.map((title) => (
                            <option value={title}>{title}</option>
                        ))}
                    </select>
                </div>
                <div className={'w-full col-span-2 lg:col-span-1  mb-1'}>
                    <label className='mb-1'>Contact Preference</label><br/>
                    <div className={'w-full mb-1 field-wrapper'}>
                        {contactPrefs.map((pref) => (
                            <label className={`checkbox mr-5 my-2.5`}>
                                <input
                                    className={`mr-2`}
                                    type="checkbox"
                                    name="customer.contactPreference"
                                    value={pref}
                                    checked={dealData.customer.contactPreference.indexOf(pref) != -1}
                                    onChange={setContactPreference}
                                />
                                {pref}
                            </label>
                        ))}
                    </div>
                </div>
                <div className={'w-full col-span-2 lg:col-span-1 mb-1 field-wrapper'}>
                    <label className='mb-1'>First Name</label><br/>
                    <input
                        className="input-field-payment"
                        type="text"
                        required={true}
                        name="customer.firstName"
                        title="First Name*"
                        placeholder="First name"
                        value={dealData.customer?.firstName}
                        onChange={setData}
                    />
                </div>
                <div className={'w-full col-span-2 lg:col-span-1 mb-1 field-wrapper'}>
                    <label className='mb-1'>Last Name</label><br/>
                    <input
                        className="input-field-payment"
                        type="text"
                        name="customer.lastName"
                        title="Last Name*"
                        required={true}
                        placeholder="Last name"
                        value={dealData.customer?.lastName}
                        onChange={setData}
                    />
                </div>
                <div className={'w-full mb-1 col-span-2 lg:col-span-1 field-wrapper'}>
                    <label className='mb-1'>Date of Birth
                        {dobRequired(false) && <Tooltip title='Why Provide Date of Birth?'>You are required to provide this due to your company being registered below 2 years</Tooltip>}
                    </label><br/>
                    <DatePickerField
                        className="input-field-payment"
                        value={dealData.customer.dateOfBirth}
                        placeholderText="Date of birth"
                        name="customer.dateOfBirth"
                        required={dobRequired()}
                        dateFormat="dd/MM/yyyy"
                        maxDate={moment().subtract(18, 'years').toDate()}
                        onChange={(date: any) => {
                            const value = date ? format_date(date, 'db') : '';
                            setDealData('customer.dateOfBirth', value);
                        }}
                    />
                </div>
                <div className={'w-full mb-1 col-span-2 lg:col-span-1 field-wrapper'}>
                    <label className='mb-1'>Email Address</label><br/>
                    <input
                        className="input-field-payment"
                        type="email"
                        required={true}
                        name="customer.email"
                        title="Email*"
                        placeholder="Enter Email"
                        value={dealData.customer?.email}
                        onChange={setData}
                    />
                </div>
                <div className={'w-full mb-1 col-span-2 lg:col-span-1 field-wrapper'}>
                    <label className='mb-1'>Landline</label><br/>
                    <input
                        className="input-field-payment"
                        type="tel"
                        name="customer.landline"
                        title="Telephone*"
                        placeholder="Enter Landline Number"
                        value={dealData.customer?.landline}
                        onChange={setData}
                    />
                </div>
                <div className={'w-full mb-1 col-span-2 lg:col-span-1 field-wrapper'}>
                    <label className='mb-1'>Mobile Number</label><br/>
                    <input
                        className="input-field-payment"
                        type="tel"
                        name="customer.mobile"
                        placeholder="Enter Mobile Number"
                        value={dealData.customer?.mobile}
                        onChange={setData}
                    />
                </div>
            </div>
            <hr className="my-4"/>
            <div className={'mb-2 font-semibold'}>Home Address: <Tooltip title='Why Provide Home Address?'>You are required to provide this due to your company being registered below 2 years</Tooltip></div>
            <div className="grid md:grid-cols-2 mt-4 gap-4">
                <div className={'w-full mb-1 field-wrapper'}>
                    <label className='mb-1'>Address Line 1</label><br/>
                    <input
                        className="input-field-payment"
                        type="text"
                        name="customer.buildingNumber"
                        required={addressRequired()}
                        placeholder="Enter Address Line 1"
                        value={dealData.customer?.buildingNumber}
                        onChange={setData}
                    />
                </div>
                <div className={'w-full mb-1 field-wrapper'}>
                    <label className='mb-1'>Address Line 2</label><br/>
                    <input
                        className="input-field-payment"
                        type="text"
                        name="customer.buildingName"
                        required={addressRequired()}
                        placeholder="Enter Address Line 2"
                        value={dealData.customer?.buildingName}
                        onChange={setData}
                    />
                </div>
                {/*<div className={'w-full mb-1 field-wrapper'}>
                    <label className='mb-1'>Thoroughfare Name</label><br/>
                    <input
                        className="input-field-payment"
                        type="text"
                        name="customer.thoroughfareName"
                        title="Thoroughfare Name*"
                        required={addressRequired()}
                        placeholder="Enter Thoroughfare Name"
                        value={dealData.customer?.thoroughfareName}
                        onChange={setData}
                    />
                </div>*/}
                <div className={'w-full mb-1 field-wrapper'}>
                    <label className='mb-1'>Post Town</label><br/>
                    <input
                        className="input-field-payment"
                        type="text"
                        name="customer.postTown"
                        title="Post Town*"
                        placeholder="Enter Post Town"
                        value={dealData.customer?.postTown}
                        onChange={setData}
                    />
                </div>
                <div className={'w-full mb-1 field-wrapper'}>
                    <label className='mb-1'>County</label><br/>
                    <input
                        className="input-field-payment"
                        type="text"
                        name="customer.county"
                        title="County*"
                        placeholder="Enter County"
                        required={addressRequired()}
                        value={dealData.customer?.county}
                        onChange={setData}
                    />
                </div>
                <div className={'w-full mb-1 field-wrapper'}>
                    <label className='mb-1'>Post Code</label><br/>
                    <input
                        className="input-field-payment"
                        type="text"
                        name="customer.postcode"
                        title="PostCode*"
                        required={addressRequired()}
                        placeholder="Enter PostCode"
                        value={dealData.customer?.postcode}
                        onChange={setData}
                    />
                </div>

                <div className={'w-full mb-1 field-wrapper'}>
                    <div className='flex gap-0'><label className='mb-1' htmlFor="move-in-date">How long have you been living at this address?</label><Tooltip title='You are required to provide this due to your company being registered below 2 years'/></div>
                    <DatePicker
                        className="input-field-payment disabled"
                        selected={moveInDate}
                        onChange={(date) => handleMoveInDateChange(date)}
                        required={moveInDateRequired()}
                        title="Moved in date"
                        placeholderText="Moved in date"
                        maxDate={moment().toDate()}
                        dateFormat="dd/MM/yyyy"
                        shouldCloseOnSelect={true}
                        id='move-in-date'
                        showYearDropdown={true}
                        showMonthDropdown={true}
                    />
                </div>

                {/*{dealData?.customer.moveInDate && <div className={'w-full mb-1 field-wrapper'}>
                    <label className='mb-1'>Previous Address</label><br/>
                    <input
                        className="input-field-payment"
                        type="text"
                        name="customer.previousAddress"
                        title="PostCode*"
                        required={dealData?.customer.moveInDate}
                        placeholder="Enter Previous Address"
                        value={dealData.customer?.previousAddress}
                        onChange={setData}
                    />
                </div>}*/}
            </div>
        </>
    );
};
