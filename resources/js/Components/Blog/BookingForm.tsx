import React, {useEffect, useState} from 'react';
import {useForm} from "@inertiajs/react";
import axios from "axios";

interface Props {
    title?: string;
    url?: string;
}

const BookingForm = ({title = '', url = ''}: Props) => {
    const {data, setData, put} = useForm({
        name: '',
        business_name: '',
        email: '',
        phone: '',
        url: '',
        subject: '',
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

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
        const {name, value} = e.target;
        setData((p) => ({...p, [name]: value}));
        if (Object.keys(errors).length) {
            validateForm();
        }
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
                setData({name: '', business_name: '', email: '', phone: '', url: '', subject: ''})
            }
            setTimeout(() => {
                setSuccessMessage(null);
            }, 8000);
        }).catch(error => {
            setIsItLoading(false);
            console.log(error);
        })
    };

    useEffect(function () {
        setData('url', location.href)
        setData('subject', (title ? title : 'Book a free consultation'))
    }, [])

    return (
        <>
            <div className="container-fuild bg-grey">
                <div className="container mx-auto py-5">
                    <div className="row">
                        <div className='col col-12'>
                            <h2 className="text-dark text-bold mb-3 text-center text-capitalize">
                                {title ? title : 'Book a free consultation'}
                            </h2>
                            <form onSubmit={sendEmail} className="items-center form text-left container">
                                <style>
                                    {` input:focus, input:active { box-shadow: rgba(0, 0, 0, 0.16) 0px 1px 4px; outline: none; } `}
                                </style>
                                {successMessage && <div className="text-success w-full mb-1 p-0 text-center">{successMessage}</div>}

                                <div className="form-group row">
                                    <div className="col-md-6 col-sm-12 mb-3">
                                        <input type="text"
                                               name='name'
                                               value={data.name}
                                               onChange={handleChange}
                                               placeholder="Enter your name"
                                               className="form-control w-100 py-3 px-3 border-0 rounded focus:outline-none focus:border-0"/>
                                        {errors.name && <span className="text-red-500">{errors.name}</span>}
                                    </div>
                                    <div className="col-md-6 col-sm-12 mb-3">
                                        <input type="text"
                                               name='business_name'
                                               value={data.business_name}
                                               onChange={handleChange}
                                               placeholder="Enter your business name"
                                               className="form-control w-100 py-3 px-3 border-0 rounded focus:outline-none focus:border-0"/>
                                        {errors.business_name && <span className="text-red-500">{errors.business_name}</span>}
                                    </div>
                                </div>
                                <div className="form-group row">
                                    <div className="col-md-6 col-sm-12 mb-3">
                                        <input type="email"
                                               name='email'
                                               value={data.email}
                                               onChange={handleChange}
                                               placeholder="Enter your email"
                                               className="form-control w-100 py-3 px-3 border-0 rounded focus:outline-none focus:border-0"/>
                                        {errors.email && <span className="text-red-500">{errors.email}</span>}
                                    </div>
                                    <div className="col-md-6 col-sm-12 mb-3">
                                        <input type="tel"
                                               name='phone'
                                               value={data.phone}
                                               onChange={handleChange}
                                               placeholder="Enter your contact number"
                                               className="form-control w-100 py-3 px-3 border-0 rounded focus:outline-none focus:border-0"/>
                                        {errors.phone && <span className="text-red-500">{errors.phone}</span>}
                                    </div>
                                </div>
                                <div className="text-center">
                                    <button type="submit" className="mt-4 text-white py-3 px-5 btn-news">
                                        Book A Call {isItLoading ? <small>( <span className="italic">Sending...</span> )</small> : ''}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
};

export default BookingForm;
