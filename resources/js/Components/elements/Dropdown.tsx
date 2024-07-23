import React, {useEffect, useRef, useState} from 'react';

interface DropdownProps {
    buttonText: string;
    options: any;
    setValue?: (value: any) => void;
}

const Dropdown: React.FC<DropdownProps> = ({buttonText, options, setValue}) => {
    const [isOpen, setIsOpen] = useState(false);
    const dropdownRef = useRef<HTMLDivElement | null>(null);
    const documentClickRef = useRef<HTMLDivElement | null>(null);

    const toggleDropdown = () => {
        setIsOpen(!isOpen);
    };

    const closeDropdown = () => {
        setIsOpen(false);
    };

    const handleClickOutside = (event: MouseEvent) => {
        if (
            dropdownRef.current &&
            documentClickRef.current &&
            !dropdownRef.current.contains(event.target as Node) &&
            !documentClickRef.current.contains(event.target as Node)
        ) {

            closeDropdown();
        }
    };

    function optionClickHandler(value?: any) {
        if (typeof setValue == "function") {
            setValue(value);
        }
        closeDropdown();
    }

    useEffect(() => {
        document.addEventListener('mousedown', handleClickOutside);
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, []);

    return (
        <div
            className={`relative d-flex inline-block text-center ${isOpen ? 'active' : ''}`}
            ref={dropdownRef}
        >
            <button className="dropdown font-bold py-2 px-5 rounded" onClick={toggleDropdown}>
                {buttonText}
            </button>
            {isOpen && (
                <div className="absolute right-0 top-100 mt-1 w-auto bg-white text-left border border-gray-300 rounded-lg shadow-lg">
                    {Array.isArray(options) ? (
                        options.map((option, index) => (
                            <span
                                key={index}
                                className="block px-5 w-100 py-2 hover:bg-blue-100"
                                onClick={() => optionClickHandler(option)} // Close the dropdown when an option is clicked
                            >{option}</span>
                        ))
                    ) : (
                        Object.keys(options).map((key) => (
                            <span
                                key={key}
                                className="block px-5 w-100  py-2 hover:bg-blue-100"
                                onClick={() => optionClickHandler(key)} // Close the dropdown when an option is clicked
                            >{options[key]}</span>
                        ))
                    )}
                </div>
            )}
            <div ref={documentClickRef}></div>
        </div>
    );
};

export default Dropdown;
