import React from 'react';

interface Props {
    name: string;
    label?: string;
    id?: string;
    className?: string;
    wrapperClass?: string;
    children?: any;
    error?: string;
    options?: string[];
}

export default function SelectField({label, name, id, className, wrapperClass, children, error, options, ...props}: Props) {
    return (
        <div className={`block max-w-full mb-3 relative ${wrapperClass || ''}${error ? ' has-error' : ''}`}>
            {label && (<label className="control-label" htmlFor={name}>{label}:</label>)}
            <select
                id={id ?? name}
                name={name}
                {...props}
                className={`form-select w-full ${className || ''} ${error ? 'error invalid' : ''}`}
            >
                <option value="">Choose {label || name}</option>
                {options ? (<>
                    {Array.isArray(options) ? (
                        options.map((option, index) => (
                            <option key={index} value={option}>{option}</option>
                        ))
                    ) : (
                        Object.keys(options).map((key) => (
                            <option key={key} value={key}>{options[key]}</option>
                        ))
                    )}
                </>) : children}
            </select>
            {error && <div className="invalid-feedback">{error}</div>}
        </div>
    );
};
