import React from 'react';

interface Props {
    name: string;
    label?: string;
    id?: string;
    type?: string;
    className?: string;
    wrapperClass?: string;
    error?: string;

}

export default function InputField({label, type, name, id, className, wrapperClass, error, ...props}: Props) {
    return (
        <div className={`block max-w-full relative mb-3 ${wrapperClass || ''} ${error ? 'has-error' : ''}`}>
            {label && (<label className="control-label" htmlFor={name}>{label}:</label>)}
            <input
                id={id ?? name}
                name={name}
                type={type ? type : 'text'}
                {...props}
                className={`form-input w-full ${className || ''} ${error ? 'error invalid' : ''}`}
            />
            {error && <div className="invalid-feedback">{error}</div>}
        </div>
    );
};
