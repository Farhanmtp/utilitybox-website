import React, { useState } from 'react';
import { useForm } from "@inertiajs/react";
import axios from "axios";

interface Props {
    btnTitle: string;
    headingTitle: string;
}

const ConsultationForm = ({ btnTitle, headingTitle }: Props) => {
    const { data, setData, put } = useForm({
        name: '',
        business_name: '',
        email: '',
        phone: '',
    });
    const [isItLoading, setIsItLoading] = useState(false);

    const [successMessage, setSuccessMessage] = useState(null);
    const [errors, setErrors] = useState<{ [key: string]: string }>({});

    const validateForm = () => {
        const newErrors: { [key: string]: string } = {};
        let isValid = true;

        if (!data.name) {
            newErrors.name = 'Please enter your name';
            isValid = false;
        }

        if (!data.business_name) {
            newErrors.business_name = 'Please enter your business name';
            isValid = false;
        }

        const emailInput = data.email;
        if (!emailInput || !/\S+@\S+\.\S+/.test(emailInput)) {
            newErrors.email = 'Please enter a valid email';
            isValid = false;
        }

        const phoneInput = data.phone;
        if (!phoneInput /*|| !/^\d{10}$/.test(phoneInput)*/) {
            newErrors.phone = 'Please enter a valid phone number';
            isValid = false;
        }
        setErrors(newErrors);
        return isValid;
    };

    const sendEmail = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        if (!validateForm()) {
            return;
        }
        //return calback ? calback() : false;
        setIsItLoading(true)
        axios.post("/book-now", data).then(resp => {
            setIsItLoading(false)
            if (resp.data.success) {
                setSuccessMessage(resp.data.message);
                setData({ name: '', business_name: '', email: '', phone: '' })
            }
            setTimeout(() => {
                setSuccessMessage(null);
            }, 8000);
        }).catch(error => {
            setIsItLoading(false);
            console.log(error);
        })
    };

    return (
        <div className="bg-form p-3 py-4 p-lg-5 mt-4 mt-md-0 rounded-5 max-w-[500px]">
            <h2 className="text-center text-white text-bold mb-3">{headingTitle}</h2>
            <form onSubmit={sendEmail} name='Consultation Form' className="form flex justify-center items-center  flex-wrap">
                {successMessage && <div className="text-success w-full mb-1 p-0 text-center">{successMessage}</div>}
                <div className="w-full">
                    <input
                        type="text"
                        name='name'
                        onChange={(e) => setData('name', e.target.value)}
                        placeholder="Enter your name"
                        className={`w-100 md:w-50 lg:w-75 py-3 px-3 text-black border-0 rounded-5 focus:outline-none focus:border-0 focus:ring-offset-0 my-2 ${errors.name && 'border-red-500'}`}
                    />
                    {errors.name && <span className="text-red-500">{errors.name}</span>}
                </div>
                <div className="w-full">
                    <input
                        type="text"
                        name='business_name'
                        onChange={(e) => setData('business_name', e.target.value)}
                        placeholder="Enter your business name"
                        className={`w-full md:w-50 lg:w-75 py-3 px-3 text-black border-0 rounded-5 focus:outline-none focus:border-0 my-2 ${errors.business_name && 'border-red-500'}`}
                    />
                    {errors.business_name && <span className="text-red-500">{errors.business_name}</span>}
                </div>
                <div className="w-full">
                    <input
                        type="email"
                        name='email'
                        onChange={(e) => setData('email', e.target.value)}
                        placeholder="Enter your email"
                        className={`w-full md:w-50 lg:w-75 py-3 px-3 text-black border-0 rounded-5 focus:outline-none focus:border-0 my-2 ${errors.email && 'border-red-500'}`}
                    />
                    {errors.email && <span className="text-red-500">{errors.email}</span>}
                </div>
                <div className="w-full">
                    <input
                        type="tel"
                        name='phone'
                        onChange={(e) => setData('phone', e.target.value)}
                        placeholder="Enter your contact number"
                        className={`w-full md:w-50 lg:w-75 py-3 px-3 text-black border-0 rounded-5 focus:outline-none focus:border-0 my-2 ${errors.phone && 'border-red-500'}`}
                    />
                    {errors.phone && <span className="text-red-500">{errors.phone}</span>}
                </div>
                <div className="w-full">
                    <button type="submit" className="w-100 md:w-50 lg:w-75 mt-4 text-white py-3 px-5 mx-2 btn-news">{isItLoading ? 'Sending...' : btnTitle}</button>
                </div>
            </form>
        </div>
    );
};

export default ConsultationForm;
