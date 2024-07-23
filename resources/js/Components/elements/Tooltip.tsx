import React, {useState} from 'react';
import {Modal} from 'react-bootstrap';

interface TooltipProps {
    title?: string;
    desc?: string;
    children?: React.ReactNode;
}

const tooltipData: Record<string, string> = {
    title1: 'This is the tooltip content for title1.',
    title2: 'This is the tooltip content for title2.',
    micro: `<ul><li><b>1.</b> employ fewer than 10 employees (or their full time equivalent) and has an annual turnover or balance sheet no greater than €2 million; or</li>
    <li><b>2.</b> uses no more than 100,000 kWh of electricity per year; or</li>
    <li><b>3.</b> uses no more than 293,000 kWh of gas per year.</li></ul>`
    // Add more titles and their corresponding content here.
};

export default function Tooltip({title = '', children = null, desc}: TooltipProps) {
    const [showModal, setShowModal] = useState(false);
    const toggleModal = () => {
        setShowModal(!showModal);
    };
    return (
        <>
      <span className='mx-2 cursor-pointer' onClick={toggleModal}>
        <i className="fa fa-circle-info text-copper"></i>
      </span>
            <Modal show={showModal} onHide={toggleModal} size="lg" centered className="custom-modal sm:p-5">
                <div className='mx-2 cursor-pointer position-absolute top-[15px] right-[15px]' onClick={toggleModal}>
                    <i className="fa fa-times-circle text-copper"></i>
                </div>
                {title && <Modal.Title className="text-center"><i className="fa fa-circle-info text-blue text-[3.5rem]"></i><br/> {title}</Modal.Title>}
                <Modal.Body>

                    <div className='text-center sm:p-3'>
                        {!title && <i className="fa fa-circle-info text-blue text-[3.5rem] pb-4"></i>}
                        <div className="mb-1">{children || desc}</div>
                        {title == 'Microbusinesses Statement' && (<ul>
                            <li><b>1.</b> employ fewer than 10 employees (or their full time equivalent) and has an annual turnover or balance sheet no greater than €2 million; or</li>
                            <li><b>2.</b> uses no more than 100,000 kWh of electricity per year; or</li>
                            <li><b>3.</b> uses no more than 293,000 kWh of gas per year.</li></ul>)}
                        {/* <div dangerouslySetInnerHTML={{ __html: desc }} /> */}
                    </div>
                </Modal.Body>
            </Modal>
        </>
    );
};
