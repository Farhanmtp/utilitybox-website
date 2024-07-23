import React from 'react';
import {SelectButtonSCGS} from '../../elements/SelectButtonSCGS';

interface ServiceStepProps {
    onNext: () => void;
    offerData?: any,
    setOfferData: (name: string, value: any) => void,
    onSelectService: (service: string) => void;
}

const ServiceStep: React.FC<ServiceStepProps> = ({onNext, offerData, setOfferData, onSelectService}) => {
    return (
        <div className="mb-5">
            <h1>What <b>service</b> are you looking for?</h1>
            <label htmlFor="electricity" onClick={() => {
                setOfferData('utilityType', 'electric');
                onSelectService('electric');
                onNext();
            }}>
                <SelectButtonSCGS title="Electricity"/>
            </label>
            <label htmlFor="gas" onClick={() => {
                setOfferData('utilityType', 'gas');
                onSelectService('gas');
                onNext();
            }}>
                <SelectButtonSCGS title="Gas"/>
            </label>
        </div>
    );
};

export default ServiceStep;
