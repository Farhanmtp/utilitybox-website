import BroadcastBanner from '@/Components/BroadcastBanner';
import { Footer } from '@/Components/Footer';
import { Navbar } from '@/Components/Navbar';
import BackToTopButton from '@/Components/elements/BackToTop';
import { CTA } from '@/Components/elements/CTA';
import React, { useEffect, useState } from 'react';
import { useSelector } from "react-redux";
import GlobalLoader from "@/Layouts/elements/GlobalLoader";
import { GlobalStateProvider } from './elements/PopupContext';
import { LoginPopUp } from '@/Components/LoginPopUp';
import Popup from '@/Components/Popup';

type LayoutProps = {
    children: React.ReactNode; // Include the children prop
};

// The Layout component is a wrapper for the entire application. It includes the navbar, footer, and other common components.

export default function Layout({ children }: LayoutProps) {
    const isLoading = useSelector((state: any) => state.loading);
    const [trigger, setTrigger] = useState(false);
    // Get the current URL path
    const currentPath = window.location.pathname;

    // Check if the URL path includes the specified string
    const isStringIncluded = currentPath.includes('swift-contract-generation-system') || currentPath.includes('reset-password');
    // console.log(isStringIncluded, "and the type is", typeof (isStringIncluded));

    useEffect(() => {
        // Check if the popup has already been triggered
        const popupTriggered = sessionStorage.getItem('popupTriggered');

        // If popup has not been triggered, set the trigger to true after 3 seconds
        if (!popupTriggered) {
            const timer = setTimeout(() => {
                setTrigger(true);
                // Set a flag in localStorage to indicate that the popup has been triggered
                sessionStorage.setItem('popupTriggered', 'true');
            }, 3000); // 3000 milliseconds = 3 seconds

            return () => clearTimeout(timer);
        }
    }, []);

    return (
        <div className="layout">
            {isStringIncluded == false && trigger ? <Popup trigger={trigger} setTrigger={setTrigger} /> : ""}

            <GlobalStateProvider>
                <GlobalLoader isLoading={isLoading} />
                <BroadcastBanner />
                <Navbar />
                {children}
                <CTA />
                <BackToTopButton />
                <Footer />
                <LoginPopUp canResetPassword={true} />
            </GlobalStateProvider>
        </div>
    );
};
