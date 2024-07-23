import React, {useEffect, useState} from 'react';
import DatePicker, {ReactDatePickerProps} from "react-datepicker";

interface Props extends ReactDatePickerProps {
    name: string,
    label?: string,
    value?: any,
    id?: string,
    placeholder?: string,
    className?: string,
    wrapperClass?: string,
    error?: string,
    onChange: (
        date: Date | null | any,
        event: React.SyntheticEvent<any> | undefined,
    ) => void,
}

export default function DatePickerField({name, label, value, id, placeholder, className, wrapperClass, onChange, error, ...props}: Props) {
    const [selectedDate, setSelectedDate] = useState(null);

    useEffect(() => {
        if (value) {
            const date: any = new Date(value);
            setSelectedDate(date);
        } else {
            setSelectedDate(null);
        }
    }, [value]);
    return (
        <div className={`block max-w-full relative mb-3 ${wrapperClass || ''} ${error ? 'has-error' : ''}`}>
            {label && (<label className="control-label mb-1" htmlFor={name}>{label}:</label>)}
            <DatePicker
                showIcon
                name={name}
                id={id ?? name}
                className={`form-input w-full ${className || ''} ${error ? 'error invalid' : ''}`}
                selected={selectedDate}
                onChange={onChange}
                onSelect={(date: any) => {
                    setSelectedDate(date);
                }}
                placeholderText={placeholder || label || ''}
                shouldCloseOnSelect={true}
                showYearDropdown={true}
                showMonthDropdown={true}
                {...props}
            />
            {error && <div className="invalid-feedback">{error}</div>}
        </div>
    );
};
