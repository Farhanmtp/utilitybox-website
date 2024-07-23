export interface UserProps {
    id: number;
    name: string;
    first_name: string;
    last_name: string;
    email: string;
    gender: string;
    phone: string;
    date_of_birth: string;
    address: string;
    address2: string;
    city: string;
    state: string;
    country_code: string;
    zipcode: string;
    avatar: string;
    avatar_url: string;
    email_verified_at: string;
}

export interface AppPops {
    logo: string,
    name: string,
    email: string,
    phone: string,
    address: string,
    socialLinks: {
        facebook: string,
        instagram: string,
        twitter: string,
        linkedin: string,
    }
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    app: AppPops
    user: UserProps;
};

// export interface MyFormData {
//     utilityType: string;
//     meterNumber: string;
//     supplierId: string;
//     customer: {
//         title: string;
//         firstName: string;
//         lastName: string;
//         email: string;
//         phone: string;
//         buildingName: string;
//         subBuildingName: string;
//         buildingNumber: string;
//         county: string;
//         postTown: string;
//         postcode: string;
//         poBox: string;
//     };
//     customerCompany: {
//         name: string;
//         type: string;
//         number: string;
//         buildingName: string;
//         subBuildingName: string;
//         buildingNumber: string;
//         county: string;
//         postTown: string;
//         postcode: string;
//         poBox: string;
//     };
//     site: {
//         name: string;
//         buildingName: string;
//         subBuildingName: string;
//         buildingNumber: string;
//         county: string;
//         postTown: string;
//         postcode: string;
//         poBox: string;
//     };
//     contract: {
//         currentSupplier: string;
//         currentEndDate: string;
//         startDate: string;
//         endDate: string;
//     };
// }

export interface MyFormData {
    utilityType: string;
    meterNumber: string;
    quoteReference: string;
    currentSupplierName: string;
    newSupplierName: string;
    contractRenewalDate: string;
    contractEndDate: string;
    newContractEndDate: string;
    curentSupplier: string;
    consumption: {
        amount: number;
        day: number;
        night: number;
        wend: number;
        kva: number;
        kvarh: number;
    };
    plans: {
        uplift: number;
        duration: number;
        type: string;
        standingChargeUplift: number;
        id: number;
    };
    postCode: string;
    renewal: string;
    cot: string;
    cotDate: string;
    outOfContract: string;
    uplift: string;
    standingChargeUplift: string;
    paymentMethod: string;
    sortByCommission: string;
    businessType: string;
    MPANTop: string;

}


export interface QuoteForm {
    dealId: string;
    quotationType: string;
}
