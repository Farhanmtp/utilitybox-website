
function ServicesBoxes() {
  return (
    <>
    <div className="container pt-5 pb-5">
    <h2 className="text-center mb-5">Our <b>Services</b></h2>
            <div className="row gap-4">
                <div className="col p-5 rounded-2 btn-white shadow-hover lwt-box" style={{boxShadow:"0px 8px 32px #2323"}}>
                    <div className="bg-blue rounded-2 w-fit number-box mb-4">
                        <i className="p-3 fa-solid fa-user text-white" style={{fontSize:24}}></i>
                    </div>
                    <h5 className="text-bold">Tendering & Contract Assistance</h5>
                    <p className="">We assist in the tendering and contracting process for energy supplies, utilising our industry expertise to secure competitive pricing and favorable contract terms.</p>
                </div>

                <div className="col p-5 rounded-2 btn-white shadow-hover lwt-box" style={{boxShadow:"0px 8px 32px #2323"}}>
                    <div className="bg-blue rounded-2 w-fit number-box mb-4">
                        <i className="p-3 fa-solid fa-file-invoice text-white" style={{fontSize:24}}></i>
                    </div>
                    <h5 className="text-bold">Invoice Verification</h5>
                    <p className="">Our team conducts thorough checks to validate the accuracy of your energy invoices, identifying and resolving any billing errors or discrepancies.</p>
                </div>

                <div className="col p-5 rounded-2 btn-white shadow-hover lwt-box" style={{boxShadow:"0px 8px 32px #2323"}}>
                    <div className="bg-blue rounded-2 w-fit number-box mb-4">
                        <i className="p-3 fa-solid fa-bolt text-white" style={{fontSize:24}}></i>
                    </div>
                    <h5 className="text-bold">Energy Consumption Analysis</h5>
                    <p className="">We perform detailed audits of your energy usage to assess patterns, identify inefficiencies, and offer recommendations for optimising energy consumption.</p>
                </div>

            </div>

            <div className="row gap-4" style={{marginTop:20}}>
                <div className="col p-5 rounded-2 btn-white shadow-hover lwt-box" style={{boxShadow:"0px 8px 32px #2323"}}>
                    <div className="bg-blue rounded-2 w-fit number-box mb-4">
                        <i className="p-3 fa-solid fa-stopwatch text-white" style={{fontSize:24}}></i>
                    </div>
                    <h5 className="text-bold">Meter Reading Reporting</h5>
                    <p className="">We collect and analyse gas and electricity meter readings, providing regular reports to track usage and monitor trends.</p>
                </div>

                <div className="col p-5 rounded-2 btn-white shadow-hover lwt-box">
                    <div className="bg-blue rounded-2 w-fit number-box mb-4">
                        <i className="p-3 fa-solid fa-check-to-slot text-white" style={{fontSize:24}}></i>
                    </div>
                    <h5 className="text-bold">Simplified Energy Account Management</h5>
                    <p className="">Let us handle the management and administration of your energy accounts, ensuring accurate billing and efficient account maintenance.</p>
                </div>

                <div className="col p-5 rounded-2 btn-white shadow-hover lwt-box" style={{boxShadow:"0px 8px 32px #2323"}}>
                    <div className="bg-blue rounded-2 w-fit number-box mb-4">
                        <i className="p-3 fa-solid fa-truck-field text-white" style={{fontSize:24}}></i>
                    </div>
                    <h5 className="text-bold">Supplier Management</h5>
                    <p className="">We manage relationships with energy suppliers on your behalf, ensuring effective communication, issue resolution, and overall optimisation of the supplier relationship.</p>
                </div>

            </div>
        </div>
    </>
  )
}

export default ServicesBoxes
