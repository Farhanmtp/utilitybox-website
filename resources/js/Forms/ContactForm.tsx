import React, {useState} from 'react';

interface ContactFormState {
    firstName: string;
    lastName: string;
    email: string;
    phoneNumber: string;
    message: string;
}

const initialFormState: ContactFormState = {
    firstName: '',
    lastName: '',
    email: '',
    phoneNumber: '',
    message: '',
};

export function ContactForm() {
    const [formData, setFormData] = useState<ContactFormState>(initialFormState);

    const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
        const {name, value} = e.target;
        setFormData((prevData) => ({...prevData, [name]: value}));
    };

    const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
        e.preventDefault();
        // Handle form submission here
        // console.log(formData);
        // Reset form after submission
        setFormData(initialFormState);
        
    };

    return (
        <form onSubmit={handleSubmit} id="meter00">
            <div className="d-md-flex gap-3">
                <input
                    type="text"
                    id="firstName"
                    name="firstName"
                    value={formData.firstName}
                    onChange={handleChange}
                    placeholder="First Name"
                    required
                    className="btn-form"
                />

                <input
                    type="text"
                    id="lastName"
                    name="lastName"
                    value={formData.lastName}
                    onChange={handleChange}
                    placeholder="Second Name"
                    required
                    className="btn-form"
                />
            </div>

            <div>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value={formData.email}
                    onChange={handleChange}
                    placeholder="Email"
                    required
                    className="btn-form"
                />
            </div>
            <div>
                <input
                    type="tel"
                    id="phoneNumber"
                    name="phoneNumber"
                    value={formData.phoneNumber}
                    onChange={handleChange}
                    placeholder="Phone Number"
                    required
                    className="btn-form"
                />
            </div>
            <div className="mb-4">
                <textarea
                    id="message"
                    name="message"
                    value={formData.message}
                    onChange={handleChange}
                    placeholder="Message"
                    required
                    className="btn-form"
                />
            </div>
            <button className="btn-form-submit" type="submit">Send</button>
        </form>
    );
}
