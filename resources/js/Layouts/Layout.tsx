import BroadcastBanner from '@/Components/BroadcastBanner';
import {Footer} from '@/Components/Footer';
import {Navbar} from '@/Components/Navbar';
import BackToTopButton from '@/Components/elements/BackToTop';
import {CTA} from '@/Components/elements/CTA';
import React from 'react';
import {useSelector} from "react-redux";
import GlobalLoader from "@/Layouts/elements/GlobalLoader";
import { GlobalStateProvider } from './elements/PopupContext';
import { LoginPopUp } from '@/Components/LoginPopUp';

type LayoutProps = {
    children: React.ReactNode; // Include the children prop
};

export default function Layout({children}: LayoutProps) {
    const isLoading = useSelector((state: any) => state.loading);
    return (
        <div className="layout">
            <GlobalStateProvider>
            <GlobalLoader isLoading={isLoading}/>
            <BroadcastBanner/>
            <Navbar/>
            {children}
            <CTA/>
            <BackToTopButton/>
            <Footer/>
            <LoginPopUp canResetPassword={true} />
            </GlobalStateProvider>
        </div>
    );
};
