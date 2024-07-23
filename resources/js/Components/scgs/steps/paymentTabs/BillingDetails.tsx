import React from 'react';

interface Props {
    dealData?: any,
    setData: (event: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => void,
    addressPreference: string
    preferenceClickHandler: (value: string) => void
}

export default function BillingDetails({setData, dealData, addressPreference, preferenceClickHandler}: Props) {

    return (
        <div className="grid grid-cols-2 gap-4">
            <div className={'col-span-2'}>
                <div className={'w-full col-span-2 lg:col-span-1 mb-1'}>
                    <label className={`btn-radio ${addressPreference == 'company' ? 'active' : ''}`}>
                        <input
                            className='ml-0' type="radio" name="billingAddressPreference"
                            value="company"
                            checked={addressPreference === 'company'}
                            onChange={(e) => preferenceClickHandler(e.target.value)}
                        /> Registered Business Address </label>

                    <label className={`btn-radio ${addressPreference == 'site' ? 'active' : ''}`}>
                        <input
                            className='ml-0' type="radio" name="billingAddressPreference"
                            value="site"
                            checked={addressPreference === 'site'}
                            onChange={(e) => preferenceClickHandler(e.target.value)}
                        /> Site Address </label>

                    <label className={`btn-radio ${addressPreference == '' ? 'active' : ''}`}>
                        <input
                            className='ml-0' type="radio" name="billingAddressPreference"
                            value=""
                            checked={addressPreference === ''} onChange={(e) => preferenceClickHandler(e.target.value)}
                        /> Other Address Preferences </label>
                </div>
            </div>
            <div className={'w-full col-span-2 lg:col-span-1 mb-1'}>
                <label className='mb-2'>Address Line 1</label><br/>
                <input
                    className="input-field"
                    type="text"
                    name="billingAddress.buildingNumber"
                    placeholder="Enter Address Line 1"
                    value={dealData.billingAddress?.buildingNumber}
                    onChange={setData}
                />
            </div>
            <div className={'w-full col-span-2 lg:col-span-1 mb-1'}>
                <label className='mb-2'>Address Line 2</label><br/>
                <input
                    className="input-field"
                    type="text"
                    name="billingAddress.buildingName"
                    placeholder="Enter Address Line 2"
                    value={dealData.billingAddress?.buildingName}
                    onChange={setData}
                />
            </div>
            {/*<div className={'w-full col-span-2 lg:col-span-1 mb-1'}>
                <label className='mb-2'>Thoroughfare Name</label><br/>
                <input
                    className="input-field"
                    type="text"
                    name="billingAddress.thoroughfareName"
                    title="Thoroughfare Name*"
                    placeholder="Enter Thoroughfare Name"
                    value={dealData.billingAddress?.thoroughfareName}
                    onChange={setData}
                />
            </div>*/}
            <div className={'w-full col-span-2 lg:col-span-1 mb-1'}>
                <label className='mb-2'>Post Town</label><br/>
                <input
                    className="input-field"
                    type="text"
                    name="billingAddress.postTown"
                    placeholder="Enter Post Town"
                    value={dealData.billingAddress?.postTown}
                    onChange={setData}
                />
            </div>
            <div className={'w-full col-span-2 lg:col-span-1 mb-1'}>
                <label className='mb-2'>County</label><br/>
                <input
                    className="input-field"
                    type="text"
                    name="billingAddress.county"
                    placeholder="Enter County"
                    value={dealData.billingAddress?.county}
                    onChange={setData}
                />
            </div>
            <div className={'w-full col-span-2 lg:col-span-1 mb-1'}>
                <label className='mb-2'>Post Code</label><br/>
                <input
                    className="input-field"
                    type="text"
                    name="billingAddress.postcode"
                    placeholder="Enter Post Code"
                    value={dealData.billingAddress?.postcode}
                    onChange={setData}
                />
            </div>
        </div>
    );
};
