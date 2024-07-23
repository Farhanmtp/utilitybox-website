import React, { useState } from 'react';

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

export function NewsletterForm(){
    const [formData, setFormData] = useState<ContactFormState>(initialFormState);

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    const { name, value } = e.target;
    setFormData((prevData) => ({ ...prevData, [name]: value }));
  };

  const handleSubmit = (e: React.FormEvent<HTMLFormElement>) => {
    e.preventDefault();
    // Handle form submission here
    console.log(formData);
    // Reset form after submission
    setFormData(initialFormState);
  };

  return (
    <form className="newsletter" onSubmit={handleSubmit}>
      <div className="d-flex gap-3">
        <input
          type="text"
          id="firstName"
          name="firstName"
          value={formData.firstName}
          onChange={handleChange}
          placeholder="First Name"
          required
          className="news-form w-50"
        />

        <input
          type="text"
          id="lastName"
          name="lastName"
          value={formData.lastName}
          onChange={handleChange}
          placeholder="Second Name"
          required
          className="news-form w-50"
        />  
      </div>


      <div className="d-flex gap-3">
        <input
          type="email"
          id="email"
          name="email"
          value={formData.email}
          onChange={handleChange}
          placeholder="Email"
          required
          className="news-form w-50"
        />

        <input
          type="tel"
          id="phoneNumber"
          name="phoneNumber"
          value={formData.phoneNumber}
          onChange={handleChange}
          placeholder="Phone Number"
          required
          className="news-form w-50"
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
          className="news-form w-100"
        />
      </div>
      <button className="btn-newsletter-submit text-semibold md:w-auto w-full" type="submit">SUBMIT</button>
    </form>
  );
}