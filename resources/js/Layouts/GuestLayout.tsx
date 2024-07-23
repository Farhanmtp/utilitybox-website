import BroadcastBanner from '@/Components/BroadcastBanner';
import {Footer} from '@/Components/Footer';
import {Navbar} from '@/Components/Navbar';
import BackToTopButton from '@/Components/elements/BackToTop';
import {CTA} from '@/Components/elements/CTA';
import React from 'react';

type GuestLayoutProps = {
    children: React.ReactNode;
};

const GuestLayout: React.FC<GuestLayoutProps> = ({children}) => {

    return (
        <div className="guest-layout">
            <BroadcastBanner/>
            <Navbar/>
            {children}
            <CTA/>
            <BackToTopButton/>
            <Footer/>
        </div>
    );
};

export default GuestLayout;
