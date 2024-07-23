import React, {useState} from 'react';
import {useForm} from "@inertiajs/react";
import axios from "axios";

export function ContactForm() {
    const {data, setData, put} = useForm({
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        message: '',
    });
    const [isItLoading, setIsItLoading] = useState(false);
    const [successMessage, setSuccessMessage] = useState(null);
    const [errors, setErrors] = useState<{ [key: string]: string }>({});

    const validateForm = () => {
        const newErrors: { [key: string]: string } = {};
        let isValid = true;

        if (!data.first_name) {
            newErrors.first_name = 'Please enter your first name';
            isValid = false;
        }
        if (!data.last_name) {
            newErrors.last_name = 'Please enter your last name';
            isValid = false;
        }

        const emailInput = data.email;
        if (!emailInput || !/\S+@\S+\.\S+/.test(emailInput)) {
            newErrors.email = 'Please enter a valid email';
            isValid = false;
        }

        const phoneInput = data.phone;
        if (phoneInput && !/^\d{10}$/.test(phoneInput)) {
            newErrors.phone = 'Please enter a valid phone number';
            isValid = false;
        }
        if (!data.message) {
            newErrors.message = 'Please enter your message';
            isValid = false;
        }
        setErrors(newErrors);
        return isValid;
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
        const {name, value} = e.target;
        setData((p) => ({...p, [name]: value}));
    };

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        if (!validateForm()) {
            return;
        }
        setIsItLoading(true);
        axios.post("/contact-form", data).then(resp => {
            setIsItLoading(false);
            if (resp.data.success) {
                setSuccessMessage(resp.data.message);
                setData({first_name: '', last_name: '', email: '', phone: '', message: ''});
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
        <form onSubmit={handleSubmit} id="meter00">
            <div className="d-md-flex gap-3">
                <div className="w-full">
                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        value={data.first_name}
                        onChange={handleChange}
                        placeholder="First Name"
                        required
                        className="btn-form mb-3 rounded"
                    />
                    {errors.first_name && <span className="text-red-500">{errors.first_name}</span>}
                </div>
                <div className="w-full">
                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        value={data.last_name}
                        onChange={handleChange}
                        placeholder="Second Name"
                        required
                        className="btn-form mb-3 rounded"
                    />
                    {errors.last_name && <span className="text-red-500">{errors.last_name}</span>}
                </div>
            </div>
            <div className="w-full">
                <input
                    type="email"
                    id="email"
                    name="email"
                    value={data.email}
                    onChange={handleChange}
                    placeholder="Email"
                    required
                    className="btn-form mb-3 rounded"
                />
                {errors.email && <span className="text-red-500">{errors.email}</span>}
            </div>
            <div className="w-full">
                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    value={data.phone}
                    onChange={handleChange}
                    placeholder="Phone Number"
                    required
                    className="btn-form mb-3 rounded"
                />
                {errors.phone && <span className="text-red-500">{errors.phone}</span>}
            </div>
            <div className="w-full">
                <textarea
                    id="message"
                    name="message"
                    value={data.message}
                    onChange={handleChange}
                    placeholder="Message"
                    required
                    className="btn-form mb-3 rounded"
                />
                {errors.message && <span className="text-red-500">{errors.message}</span>}
            </div>
            <div className="mb-4">
                <button className="btn-form-submit" type="submit">{isItLoading ? 'Sending...' : 'Send'}</button>
            </div>
            {successMessage && <div className="text-success w-full mb-1 p-0 text-center">{successMessage}</div>}
        </form>
    );
}
