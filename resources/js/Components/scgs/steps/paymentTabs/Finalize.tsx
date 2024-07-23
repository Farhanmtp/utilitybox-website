import React from 'react';

interface Props {
    dealData: any,
    setData: (event: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => void,
}

export default function Finalize({setData, dealData}: Props) {

    return (
        <>
            <div className="">
                <h4 className='mb-3 text-semibold'>Correspondence with You</h4>
                <p className='mb-4'>We will need to get in touch with you regarding your supply application.
                    By proceeding with your supply application and advancing from this page
                    by selecting the 'Save & eSign Contract' button below, you are consenting
                    to be contacted by post, email, or phone. Please be aware that the
                    default method for contacting you will be via email.
                </p>
                <p className='mb-2 text-semibold'>To finalise your supply application, you hereby agree to the following:</p>
                <ul className='mb-4'>
                    <li className='mb-2 flex items-center'><input required={true} type="checkbox" className='mr-2' onChange={setData} checked={dealData.consents?.authorised == 1} name='consents.authorised' value='1'/>You are authorised by your company to submit this supply application.</li>
                    <li className='mb-2 flex items-center'><input required={true} type="checkbox" className='mr-2' onChange={setData} checked={dealData.consents?.terms == 1} name='consents.terms' value='1'/>You have perused, comprehended, and accepted the <a className='ml-1 mr-1 text-medium text-blue' href='/terms-and-conditions'> Terms & Conditions </a> of Utility Box.</li>
                    <li className='mb-2 flex items-center'><input required={true} type="checkbox" className='mr-2' onChange={setData} checked={dealData.consents?.data == 1} name='consents.data' value='1'/>You give consent to Utility Box to store the data you have provided, and for that data to be retained for a reasonable period.</li>
                </ul>

                <p className='mb-2 text-semibold'>Your Marketing Consent (Optional)</p>
                <ul className='mb-4'>
                    <li className='mb-2 flex items-center'><input type="checkbox" className='mr-2' onChange={setData} checked={dealData.consents?.communication == 1} name='consents.communication' value='1'/>Utility Box may wish to get in touch with you with information about other business services that could benefit your company. Kindly confirm by ticking this box that you are content to receive marketing communications from Utility Box and/or approved third parties Screen</li>
                </ul>
            </div>
        </>
    );
};
