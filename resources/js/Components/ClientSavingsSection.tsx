const ClientSavingsSection = () => {
    // Dummy data for demonstration
    const clients = [
        { companyName: 'Fruity Fresh (Western) Limited', companyLogo: '/images/freshfruit.png', supplyType: 'Electricity', savingAmount: '36,141.57' },
        { companyName: 'Steam Traction World Limited', companyLogo: '/images/steam.png', supplyType: 'Electricity', savingAmount: '3,717.37' },
        { companyName: 'Teme Valley Brewery', companyLogo: '/images/teme-valley-logo.png', supplyType: 'Electricity', savingAmount: '5,307.33' },
        { companyName: 'Saach Interiors Ltd', companyLogo: '/images/SAACH.png', supplyType: 'Electricity', savingAmount: '3,973.52' }
    ];

    return (
        <section className="container text-center py-50">
            <h3 className="text-3xl font-semibold mb-8">How Much Did <b>We Save</b> Our Clients?</h3>
            <div className="flex flex-wrap md:flex-row justify-content-center item-center gap-4">
                {clients.map((client, index) => (
                    <div key={index} className="bg-grey border col-12 col-md-5 p-4 rounded feature-container flex  flex-column justify-content-center align-items-center">
                        <img className="cardLogo mb-4 rounded-full" src={client.companyLogo} />
                        <h3 className="text-capitalize text-blue textsize-24 font-semibold mb-2">{client.companyName}</h3>
                        <p className="text-lightgrey text-lg mb-2">Supply Type<br /><span className="text-black font-semibold textsize-24">{client.supplyType}</span></p>
                        <p className="text-lightgrey text-18">Saving Amount<br /><span className="text-black font-bold textsize-24">£{client.savingAmount}</span></p>
                    </div>
                ))}
            </div>
        </section>
    );
};

export default ClientSavingsSection;
