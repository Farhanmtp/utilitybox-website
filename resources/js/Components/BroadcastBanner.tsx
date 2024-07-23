import {usePage} from '@inertiajs/react';
import React from 'react';
import {Image} from 'react-bootstrap';
import {PageProps} from "@/types";

const BroadcastBanner: React.FC = () => {
    const phone = usePage<PageProps>().props.app.phone;
    return (
        <div className="bg-blue-to-light d-none d-lg-block">
            <div className="container">
                <div className="d-flex flex-column flex-md-row">
                    <div className="flex-grow-1 px-4 bg-blue pt-2 container-broadcast" style={{textAlign: 'right'}}>
                        <p className="text-grey text-thin content-broadcast">Experience hassle-free online assistance for all your needs.</p>
                    </div>
                    {phone &&
                        <div className="bg-light-blue pt-2 mt-3 mt-md-0" style={{paddingLeft: "50px"}}>
                            <a href={"tel:" + phone}>
                                <p className="text-grey text-thin flex gap-2">
                                    <Image className="mb-3 w-5" src="/images/icons/callw.png" alt="Call Icon"/> {phone}
                                </p>
                            </a>
                        </div>
                    }
                </div>
            </div>
        </div>
    );
};

export default BroadcastBanner;
