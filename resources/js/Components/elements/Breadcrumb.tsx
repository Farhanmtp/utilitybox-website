import { useGlobalState } from '@/Layouts/elements/PopupContext';
import { PageProps } from '@/types';
import { usePage } from '@inertiajs/react';
import React, { useState } from 'react';
import { Image } from 'react-bootstrap';

interface BreadcrumbItem {
    label: string;
    active?: boolean;
}

interface BreadcrumbProps {
    items: BreadcrumbItem[];
    activeCount: number;
}

const Breadcrumb: React.FC<BreadcrumbProps> = ({items, activeCount}) => {
    // Calculate the active stage
    let activeStage = activeCount;
    if (activeStage === 3) {
        activeStage = 2; // Treat step 3 as step 2
    } else if (activeStage === 4) {
        activeStage = 3; // Treat step 3 as step 2
    }

    let isValidIndex = true;
    if(activeStage === 1){
        isValidIndex=true;
    } else if(activeStage > 1) {
        isValidIndex=false;
    }

    const { showModal, setShowModal } = useGlobalState();

    const toggleModal = () => {
        setShowModal(true);
    };
    const isLoggedIn = usePage<PageProps>().props.loggedin;

    

    return (<>
    {!isLoggedIn && isValidIndex==true ? (
        <div className='justify-end d-none d-lg-flex mb-5'>
        <a
            className="self-center hover:font-medium hover:text-black mx-5 cursor-pointer"
            onClick={toggleModal}
        >
            <button className='btn-blue border-none rounded-0 px-5 py-2 text-white'>
            Log In
            </button>
            
        </a>
        {/* <BlueButton title='Sign Up' link='/register' /> */}
        </div>
    ) : (``)}
        <nav id="breadcrumb" className="d-flex justify-content-center mb-5 rounded-md md-d-none" aria-label="Breadcrumb">
            <ol className="breadcrumb">
                {items.map((item, index) => (
                    <React.Fragment key={index}>
                        <li className={`breadcrumb-item ${item.active ? 'active' : ''}`}>
                            {item.active ? (
                                <span className="font-semibold text-blue">{item.label}</span>
                            ) : index < activeCount && index < activeStage ? (
                                <span className="d-flex items-center"><Image className="mr-3" src="/images/icons/check.png" width={`20px`} style={{height:`20px`}} />{item.label}</span>
                            ) : (
                                <span>{item.label}</span>
                            )}
                        </li>
                        {index !== items.length - 1 && (
                            <span className="breadcrumb-separator"><Image src="/images/icons/arrow-small-right.svg" width={25}/></span>
                        )}
                        
                    </React.Fragment>
                ))}
                
            </ol>
        </nav>
        </>
    );
};

export default Breadcrumb;
