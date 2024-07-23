import React from 'react';
import { Image } from "react-bootstrap";
import { Link } from '@inertiajs/react';


interface PopupProps {
    trigger: boolean;
    setTrigger: React.Dispatch<React.SetStateAction<boolean>>;
}

const Popup: React.FC<PopupProps> = (props) => {
    const handleClosePopup = () => {
        console.log("Its not working Sir!!!");
        props.setTrigger(false);
    };

    return (
        <div className={'popup'}>
            <div className='popup-inner'>
                <button className='close-btn' onClick={handleClosePopup}>&times;</button>

                <div className='popup-container'>
                    <Image src="/images/logo-white.png" />
                    <p>With energy prices on the decline post-crisis, now is the perfect time to revisit and renegotiate your energy contracts. Seize this opportunity to lock in lower rates and enjoy substantial savings. Don't let this chance slip by—refresh your energy terms today and power up your savings!</p>
                    <Link href="/contact" onClick={handleClosePopup} >
                        <button className="btn-blue" >
                            Book a Free Consultation Call
                        </button>
                    </Link>
                </div>
            </div>
        </div>
    );
};

export default Popup;

