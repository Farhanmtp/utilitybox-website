import React from 'react';

interface Props {
    dealData?: any,
    setData: (event: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => void,
}


export default function SupplyDetails({setData, dealData}: Props) {

    return (
        <>
            <div className="grid col-span-2 lg:col-span-1 gap-4">
                <div className={'w-full col-span-2 lg:col-span-1  mb-1'}>
                    <label className='mb-2'>Meter Number</label><br/>
                    <input
                        className="input-field"
                        type="text"
                        name="meterNumber"
                        title="Meter Number*"
                        placeholder="Enter Meter Number"
                        value={dealData.smeDetails.meterNumber}
                        onChange={setData}
                    />
                </div>
                {/*<div className={'w-full col-span-2 lg:col-span-1  mb-1'}>
                    <label className='mb-2'>Site Name</label><br/>
                    <input
                        className="input-field"
                        type="text"
                        name="site.name"
                        title="Site Name*"
                        placeholder="Enter Site name"
                        value={dealData.site?.name}
                        onChange={setData}
                    />
                </div>*/}
                <div className={'col-span-2 font-bold'}>Site Address:</div>
                <div className={'w-full col-span-2 lg:col-span-1  mb-1'}>
                    <label className='mb-2'>Address Line 1</label><br/>
                    <input
                        className="input-field"
                        type="text"
                        name="site.buildingNumber"
                        placeholder="Enter Address Line 1"
                        value={dealData.site?.buildingNumber}
                        onChange={setData}
                    />
                </div>
                <div className={'w-full col-span-2 lg:col-span-1  mb-1'}>
                    <label className='mb-2'>Address Line 2</label><br/>
                    <input
                        className="input-field"
                        type="text"
                        name="site.buildingName"
                        placeholder="Enter Address Line 2"
                        value={dealData.site?.buildingName}
                        onChange={setData}
                    />
                </div>
                <div className={'w-full col-span-2 lg:col-span-1 mb-1'}>
                    <label className='mb-2'>Post Town</label><br/>
                    <input
                        className="input-field"
                        type="text"
                        name="site.postTown"
                        title="Post Town*"
                        placeholder="Enter Post Town"
                        value={dealData.site?.postTown}
                        onChange={setData}
                    />
                </div>
                <div className={'w-full col-span-2 lg:col-span-1  mb-1'}>
                    <label className='mb-2'>County</label><br/>
                    <input
                        className="input-field"
                        type="text"
                        name="site.county"
                        title="County*"
                        placeholder="Enter County"
                        value={dealData.site?.county}
                        onChange={setData}
                    />
                </div>
                <div className={'w-full col-span-2 lg:col-span-1 mb-1'}>
                    <label className='mb-2'>Post Code</label><br/>
                    <input
                        className="input-field"
                        type="text"
                        name="site.postcode"
                        title="PostCode*"
                        placeholder="Enter Post Code"
                        value={dealData.site?.postcode}
                        onChange={setData}
                    />
                </div>
            </div>
        </>
    );
};
