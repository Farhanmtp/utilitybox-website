import {validatePostcode} from "@/utils/helper";


type OnBefore = (input: any) => void
type OnSuccess = (resp: any, data?: any) => void
type OnError = (resp: any) => void

export function isInt(value: any) {
    return value.replace(/[^0-9]+/g, '') == value;
}

export function getPostCodes(input: string, before: OnBefore, success?: OnSuccess, error?: OnError) {
    before(input);
    fetch(`/api/powwr/postcode?q=${input}`)
        .then(response => response.json())
        .then(resp => {
            let postcodes = resp.data ?? [];

            if (typeof success == "function") {
                success(resp, postcodes)
            }
        })
        .catch(err => {
            if (typeof error == "function") {
                error(err);
            }
        });
};

export function getMeterLookup(utilityType: string, postCode: string, before: OnBefore, success: OnSuccess, error: OnError) {
    before(postCode);
    if (validatePostcode(postCode) || isInt(postCode)) {
        const requestOptions = {
            method: 'POST',
            headers: {'Content-Type': 'application/json', Accept: 'application/json'},
            body: JSON.stringify({
                postCode: postCode,
                utilityType: utilityType,
            })
        };
        fetch('/api/powwr/meter-lookup', requestOptions)
            .then(response => response.json())
            .then(resp => {
                let addresses = resp?.data?.addresses || [];
                success(addresses);
            })
            .catch(error => {
                console.error('Create deal error:', error);
                error(error);
            });
    }
};
