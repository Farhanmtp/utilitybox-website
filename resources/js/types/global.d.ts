import { AxiosInstance } from 'axios';
import ziggyRoute,  { Config as ZiggyConfig ,route as routeFn } from 'ziggy-js';

declare global {
    interface Window {
        axios: AxiosInstance;
    }

    var route: typeof routeFn;
    var Ziggy: ZiggyConfig;
}
