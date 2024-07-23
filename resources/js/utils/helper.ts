import moment from "moment/moment";
import {AES, enc, lib} from 'crypto-js';

let CryptoJSAesConfig = {
    stringify: function (cipherParams: any) {
        let j = {b: cipherParams.ciphertext.toString(enc.Base64), i: null, s: null};
        if (cipherParams.iv) j.i = cipherParams.iv.toString();
        if (cipherParams.salt) j.s = cipherParams.salt.toString();
        return JSON.stringify(j);
    },
    parse: function (jsonStr: any) {
        const j = JSON.parse(jsonStr);
        let cipherParams = lib.CipherParams.create({ciphertext: enc.Base64.parse(j.b)});
        if (j.i) cipherParams.iv = enc.Hex.parse(j.i)
        if (j.s) cipherParams.salt = enc.Hex.parse(j.s)
        return cipherParams;
    }
}

export function encryptAes(decrypted: any) {
    let password = (new Date()).getFullYear().toString();
    var encrypted = AES.encrypt(JSON.stringify(decrypted), password, {format: CryptoJSAesConfig});
    return encrypted.toString();
}

export function decryptAes(encrypted: any) {
    let password = (new Date()).getFullYear().toString();
    let decrypted = AES.decrypt(encrypted, password, {format: CryptoJSAesConfig}).toString(enc.Utf8);
    try {
        return JSON.parse(decrypted);
    } catch (e) {
        return decrypted;
    }
}

export function setMask(string: any, visible = 3, position = 'left'): any {
    const length = string.length;
    const mask = '*';

    if (length <= (visible * 2)) {
        return string;
    }

    const _prefix = position != 'left' ? string.substring(0, visible) : '';
    const _suffix = position != 'right' ? string.substring(length - visible) : '';

    const _mask = mask.repeat(length - (position == 'center' ? visible * 2 : visible));

    return `${_prefix}${_mask}${_suffix}`;
};

// Transforms key/value pairs to FormData() object
export function toFormData(values: any = {}, method = "POST") {
    const formData = new FormData();
    for (const field of Object.keys(values)) {
        formData.append(field, values[field]);
    }

    // NOTE: When working with Laravel PUT/PATCH requests and FormData
    // you SHOULD send POST request and fake the PUT request like this.
    // More info: http://stackoverflow.com/q/50691938
    if (method.toUpperCase() === "PUT") {
        formData.append("_method", "PUT");
    }

    return formData;
}

export function isInt(value: any) {
    return value.replace(/[^0-9]+/g, '') == value;
}

export function validateEmail(email: string) {
    const regExp = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!regExp.test(email)) {
        return false;
    }
    return true;
};

export function validateMobile(phone: string) {
    const regExp = /^(07)\d{9}$/;
    if (!regExp.test(phone)) {
        return false;
    }
    return true;
};

export function validatePhone(phone: string) {
    const regExp = /^(0)+(?!0|7)\d{10,}$/;
    if (!regExp.test(phone)) {
        return false;
    }
    return true;
};

export function validatePostcode(postcode: string) {
    postcode = postcode.toUpperCase();
    const regExp1 = /[^0-9a-z]/gi;
    const regExp2 = /^([A-Z]{1,2}\d{1,2}[A-Z]?)\s*(\d[A-Z]{2})$/;

    if (!regExp1.test(postcode) || !regExp2.test(postcode)) {
        return false;
    }
    return true;
}

export function ucfirst(str: string) {
    return str.charAt(0).toUpperCase() + str.slice(1);
};

/**
 * @param item
 */
export function isObject(item: any) {
    return (item && typeof item === 'object' && !Array.isArray(item));
}

/**
 * @param item
 */
export function isArray(item: any) {
    return (item && Array.isArray(item));
}

export function dbDateFormat(date: any) {
    return moment(date).format('YYYY/MM/DD');
}


/**
 * Get formatted date
 *
 * Month:         M    1 to 12
 *                Mo    1st 2nd ... 11th 12th
 *                MM    01 to 12
 *                MMM    Jan Feb ... Nov Dec
 *                MMMM    January February ... November December
 *
 * Day of Month:  D    1 to 31
 *                DD    01 to 31
 *                Do    1st 2nd ... 30th 31st
 *
 * Week:          dd    Su Mo ... Fr Sa
 *                ddd   Sun Mon ... Fri Sat
 *                dddd  Sunday Monday ... Friday Saturday
 *
 * Year:          YY    70 71 ... 29 30
 *                YYYY  1970 1971 ... 2029
 *                Y      1970 1971 ... 9999
 *
 * Hour:          H     0 1 ... 22 23
 *                HH    00 01 ... 22 23
 *                h     1 2 ... 11 12
 *                hh    01 02 ... 11 12
 *
 * Minute:        m     0 1 ... 58 59
 *                mm    00 01 ... 58 59
 *
 * Second:        s     0 1 ... 58 59
 *                ss    00 01 ... 58 59
 *
 * AM/PM:         A    AM PM
 *                a    am pm
 *
 * [check more format](https://momentjs.com/docs/#/displaying/format/)
 *
 * @param date
 * @param {string} format
 * @param {boolean} keepLocalTime
 * @returns {string}
 */
export const format_date = (date: any, format?: string, keepLocalTime = true) => {
    if (!date) {
        return '';
    }
    if (format) {
        if (format.toLowerCase() == 'uk') {
            format = 'DD/MM/YYYY';
        }
        if (format.toLowerCase() == 'db') {
            format = 'YYYY/MM/DD';
        }
        return moment(date).utc(keepLocalTime).format(format);
    }

    return moment(date).utc(keepLocalTime).format('YYYY/MM/DD');
};

export const validate_field = (field: any, value?: any) => {

    if (typeof field == "string") {
        let _valid = true;
        let fields;
        if (field.startsWith('.') || field.startsWith('#')) {
            fields = document.querySelectorAll(field);
        } else {
            fields = document.querySelectorAll(`[name="${field}"]`);
        }
        fields.forEach((elm: any) => {
            if (!validate_field(elm)) {
                _valid = false;
            }
        });
        return _valid;
    } else {
        let _type = field.type, _name = field.name, _valid = true;
        value = value || field.value;

        if (field.required && (!value.length || (['number', 'string'].indexOf(typeof value) != -1 && value.replace(/ /g, "") == ''))) {
            _valid = false;
        } else if (_type == 'email' && !validateEmail(value)) {
            _valid = false;
        } else if (_type == 'number') {
            if (field.minLength > 0 && value.length < parseInt(field.minLength)) {
                _valid = false;
            }
            if (field.maxLength > 0 && value.length > parseInt(field.maxLength)) {
                _valid = false;
            }
            if (field.min > -1 && parseFloat(value) < parseInt(field.min)) {
                _valid = false;
            }
            if (field.max > 0 && parseFloat(value) > parseInt(field.max)) {
                _valid = false;
            }
        }

        if (_valid) {
            field.classList.remove('error');
        } else {
            field.classList.add('error');
        }
        return _valid;
    }
}

export const updateState = (prevState: any, source: any, value: any = '') => {
    if (isObject(source)) {
        if (Object.keys(source).length) {
            let output = Object.assign({}, prevState);
            Object.keys(source).forEach(key => {
                let source_value = source[key];
                if (isObject(source_value)) {
                    if (!(key in prevState)) {
                        Object.assign(output, {[key]: source_value});
                    } else {
                        output[key] = updateState(prevState[key], source_value);
                    }
                } else {
                    if (!(isArray(source_value) && source_value.length == 0 && isObject(output[key]))) {
                        Object.assign(output, {[key]: source_value});
                    }
                }
            });
            return output;
        } else {
            return source;
        }
    } else if (typeof value == 'object' && Object.keys(value).length) {
        return {...prevState, [source]: {...prevState[source], ...value}};
    } else {
        if (typeof value == "undefined") {
            throw "value parameter is required."
        } else {
            if (source) {
                if (source.indexOf('.') > 0) {
                    let parts = source.split('.');
                    var [p1, p2, p3] = parts;
                    if (parts.length == 3) {
                        return {
                            ...prevState, [p1]: {...prevState[p1], [p2]: {...prevState[p1][p2], [p3]: value}}
                        };
                    } else {
                        return {
                            ...prevState, [p1]: {...prevState[p1], [p2]: value}
                        };
                    }
                } else {
                    if (isArray(value) && value.length == 0 && isObject(prevState[source])) {
                        return prevState;
                    } else {
                        return {...prevState, [source]: value};
                    }
                }
            } else {
                return prevState;
            }
        }
    }
};

/**
 *
 * @param key
 * @param value
 * @param ttl in seconds, default is 300 second. set -1 for lifetime
 */
export function setLocalStorage(key: string, value: any, ttl: number = 300) {
    const now = new Date()

    // `item` is an object which contains the original value
    // as well as the time when it's supposed to expire
    const item = {
        value: value,
        expiry: ttl > 0 ? now.getTime() + (ttl * 1000) : -1,
    }
    localStorage.setItem(key, JSON.stringify(item))
}

export function getLocalStorage(key: string) {
    const itemStr = localStorage.getItem(key)
    // if the item doesn't exist, return null
    if (!itemStr) {
        return null
    }
    const item = JSON.parse(itemStr)
    const now = new Date()
    // compare the expiry time of the item with the current time
    if (item.expiry > 0 && now.getTime() > item.expiry) {
        // If the item is expired, delete the item from storage
        // and return null
        localStorage.removeItem(key)
        return null
    }
    return item.value
}

export function range(start: number, end: number): any {
    return Array.from({length: end - start + 1}, (_, i) => start + i)
}

export function getSupplierIdByName(suppliers: any, name: string) {
    let name2 = name;
    if (name == 'British Gas') {
        name2 = 'British Gas Business';
    }
    if (name == 'TotalEnergies') {
        name2 = 'Total Energy';
    }
    if (name == 'Scottish And Southern') {
        name2 = 'Southern Electric';
    }
    let supplier = suppliers.filter((item: any) => (item.name === name || item.name === name2 || item.name.indexOf(name) != -1)
    )
    return supplier[0]?.powwr_id ?? '';
}

export function getSupplierNameById(suppliers: any, id: string) {
    let supplier = suppliers.filter((item: any) => item.powwr_id === id
    )
    return supplier[0]?.name ?? '';
}

