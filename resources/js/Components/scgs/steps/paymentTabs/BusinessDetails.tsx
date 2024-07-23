import React, {useEffect, useState} from 'react';
import Tooltip from "@/Components/elements/Tooltip";
import {format_date} from "@/utils/helper";
import Partnership from "@/Components/scgs/steps/paymentTabs/Partnership";
import moment from "moment";
import DatePickerField from "@/Components/elements/DatePickerField";

interface Props {
    dealData?: any,
    setData: (event: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => void,
    setDealData: (name: string, value: any) => void,
    setOfferData: (name: string, value: any) => void,
    setCompanyAddress: (company: any) => void,
    dobRequired: (check_value?: boolean) => boolean,
}


export default function BusinessDetails({setData, dealData, setDealData, setOfferData, dobRequired, setCompanyAddress}: Props) {
    const [companies, setCompanies] = useState([]);
    const [isLoading, setIsLoading] = useState(false);
    const [showDropdown, setShowDropdown] = useState(false);
    const [companyName, setCompanyName] = useState('');

    const [dateOfBirth, setDateOfBirth] = useState(null);

    const handleCompanyTypeClick = (event: React.ChangeEvent<HTMLInputElement>) => {
        let value = event.target.value;
        setIsLoading(false)
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

    function companyClickHandler(company: any) {
        setCompanyAddress(company)
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
                    console.log(resp.data);
                    let _companies = resp.data || [];
                    setCompanies(_companies);
                    setShowDropdown(true);
                    setIsLoading(false);
                })
                .catch(error => {
                    setIsLoading(false);
                    console.error('error:', error);
                });
        }
    };
    useEffect(() => {
        const timer = setTimeout(() => {
            searchCompany(companyName);
        }, 1000);
        return () => clearTimeout(timer);
    }, [companyName]);

    useEffect(() => {
        if (dealData?.customer?.dateOfBirth) {
            const dob: any = new Date(dealData?.customer?.dateOfBirth);
            setDateOfBirth(dob);
        } else {
            setDateOfBirth(null);
        }
    }, []);

    return (
        <>
            <div className="grid grid-cols-2 gap-4 mb-4">
                <div className={'col-span-2'}>
                    <label className='mb-2'>Company Type</label><br/>
                    <div className={'w-full mb-1'}>
                        <label className={`btn-radio ${dealData.company.type == 'Limited' ? 'active' : ''}`}>
                            <input
                                className='ml-0' type="radio" name="company.type"
                                value="Limited"
                                checked={dealData.company.type === 'Limited'} onChange={handleCompanyTypeClick}
                            /> Private Limited </label>

                        <label className={`btn-radio ${dealData.company.type == 'SoleTrader' ? 'active' : ''}`}>
                            <input
                                className='ml-0' type="radio" name="company.type"
                                value="SoleTrader"
                                checked={dealData.company.type === 'SoleTrader'} onChange={handleCompanyTypeClick}
                            /> Sole Traders </label>

                        <label className={`btn-radio ${dealData.company.type == 'LimitedLiabilityPartnership' ? 'active' : ''}`}>
                            <input
                                className='ml-0' type="radio" name="company.type"
                                value="LimitedLiabilityPartnership"
                                checked={dealData.company.type === 'LimitedLiabilityPartnership'} onChange={handleCompanyTypeClick}
                            /> LLP </label>
                    </div>
                </div>
            </div>
            {dealData.company.type === 'LimitedLiabilityPartnership' ? (
                <Partnership setData={setData} dealData={dealData} setDealData={setDealData}/>
            ) : (<>
                <div className="grid grid-cols-2 gap-4 mb-4">
                    <div className={'w-full col-span-2 lg:col-span-1'}>
                        <label className='mb-2'>Company Name</label><br/>
                        {dealData.company.type !== 'Limited' ? (<input
                            className="input-field"
                            type="text"
                            name="company.name"
                            title="Company Name*"
                            placeholder="Company Name*"
                            value={dealData.company?.name}
                            onChange={setData}
                        />) : (
                            <div className={'block relative'}>
                                <input
                                    className="input-field"
                                    type="text"
                                    name="company.name"
                                    title="Company Name*"
                                    placeholder="Company Name*"
                                    value={dealData.company?.name}
                                    onChange={(e) => {
                                        setData(e);
                                        setCompanyName(e.target.value);
                                    }}
                                />
                                {(showDropdown || isLoading || companies?.length > 0) &&
                                    <div className="bg-white border absolute right-0 left-0 z-10 lead top-100 max-h-[300px] overflow-auto"
                                         id="dropdown">
                                        {isLoading && <div className="text-left p-3">
                                            <div className="loader-inline"></div>
                                            Searching
                                        </div>}
                                        {showDropdown && companies?.length > 0 && (
                                            companies?.map((company: any, index) => {
                                                return (
                                                    <div title={company?.title} key={index}
                                                         onClick={() => companyClickHandler(company)}
                                                         className="py-1 custom-option text-left px-3"
                                                         style={{maxWidth: "24rem", borderBottom: "1px solid gray", backgroundColor: "white"}}
                                                         placeholder={`Enter postcode.`}>
                                                        {company?.title}
                                                    </div>
                                                );
                                            })
                                        )}
                                    </div>}
                            </div>
                        )}
                    </div>
                    {dealData.company.type == 'Limited' && <>
                        <div className={'w-full col-span-2 lg:col-span-1'}>
                            <label className='mb-2'>Registration Number</label><br/>
                            <input
                                className="input-field"
                                type="text"
                                name="company.number"
                                title="Registration Number*"
                                placeholder="Registration Number*"
                                value={dealData.company?.number}
                                onChange={setData}
                            />
                        </div>
                        <div className={'w-full col-span-2 d-flex'}>
                            <label className={`checkbox my-2.5`}>
                                <input
                                    className={`mr-2`}
                                    type="checkbox"
                                    name="company.isMicroBusiness"
                                    value='1'
                                    checked={dealData.company.isMicroBusiness as boolean}
                                    onChange={setData}
                                />
                                Is your business a Micro Business?
                            </label>
                            <Tooltip title='Microbusinesses Statement'/>
                        </div>
                    </>}
                </div>
                <div className="grid grid-cols-2 gap-4 mb-4">
                    {(dealData.company.type == 'SoleTrader' || dobRequired(false)) &&
                        <div className={'w-full col-span-2 lg:col-span-1 field-wrapper'}>
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
                        </div>}

                    {dealData.company.type == 'SoleTrader' &&
                        <div className={'w-full col-span-2 lg:col-span-1'}>
                            <label className='mb-1'>Number Year at Address</label><br/>
                            <input
                                className="input-field-payment"
                                type="number"
                                name="company.numberOfYears"
                                title="Number Year at Address*"
                                placeholder="Enter Address Line 1"
                                value={dealData.company?.numberOfYears}
                                onChange={setData}
                            />
                        </div>}
                </div>

                <hr className="mb-3 border-dashed"/>
                <div className={'mb-2 font-semibold'}>Company Address:</div>
                <div className="grid grid-cols-2 gap-4 mb-4">
                    <div className={'w-full col-span-2 lg:col-span-1'}>
                        <label className='mb-2'>Address Line 1</label><br/>
                        <input
                            className="input-field"
                            type="text"
                            name="company.buildingNumber"
                            title="Address Line 1"
                            placeholder="Enter Address Line 1"
                            value={dealData.company?.buildingNumber}
                            onChange={setData}
                        />
                    </div>
                    <div className={'w-full col-span-2 lg:col-span-1'}>
                        <label className='mb-2'>Address Line 2</label><br/>
                        <input
                            className="input-field"
                            type="text"
                            name="company.buildingName"
                            placeholder="Enter Address Line 2*"
                            value={dealData.company?.buildingName}
                            onChange={setData}
                        />
                    </div>
                    <div className={'w-full col-span-2 lg:col-span-1'}>
                        <label className='mb-2'>Post Town</label><br/>
                        <input
                            className="input-field"
                            type="text"
                            name="company.postTown"
                            placeholder="Enter Post Town*"
                            value={dealData.company?.postTown}
                            onChange={setData}
                        />
                    </div>
                    <div className={'w-full col-span-2 lg:col-span-1'}>
                        <label className='mb-2'>County</label><br/>
                        <input
                            className="input-field"
                            type="text"
                            name="company.county"
                            placeholder="Enter County*"
                            value={dealData.company?.county}
                            onChange={setData}
                        />
                    </div>
                    <div className={'w-full col-span-2 lg:col-span-1'}>
                        <label className='mb-2'>Post Code</label><br/>
                        <input
                            className="input-field"
                            type="text"
                            name="company.postcode"
                            title="PostCode*"
                            placeholder="Enter PostCode*"
                            value={dealData.company?.postcode}
                            onChange={setData}
                        />
                    </div>
                </div>
            </>)}
        </>
    );
};
