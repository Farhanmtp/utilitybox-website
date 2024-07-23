import { Image } from "react-bootstrap";

export function WorkTogether() {
    return (
        <>
            <div className="container py-100 px-4">
                <div className="flex flex-col gap-4 lg:flex-row">
                    <div className="col p-5 rounded-2 bg-white shadow text-center d-grid item-align-center">
                        <div className="align-self-center d-grid" >
                            <h5 className="align-self-center text-semibold mb-4 text-xl">Let's work <br />together</h5>
                            <Image style={{ placeSelf: "center" }} src="/images/icons/angle-right.svg" width={40} />
                        </div>
                    </div>

                    <div className="col p-5 rounded-2 btn-blue shadow-hover lwt-box">
                        <div className="bg-white rounded-2 w-fit number-box">
                            <h1 className="p-2 mb-4 text-blue font-medium text-[40px]">01</h1>
                        </div>
                        <h5 className="text-white text-bold text-xl">Click & Choose</h5>
                        <p className="text-white">Compare and choose your tariff</p>
                    </div>

                    <div className="col p-5 rounded-2 btn-blue shadow-hover lwt-box">
                        <div className="bg-white rounded-2 w-fit number-box">
                            <h1 className="p-2 mb-4 text-blue font-medium text-[40px]">02</h1>
                        </div>
                        <h5 className="text-white text-bold text-xl">Subscribe & Switch</h5>
                        <p className="text-white">Get your online quotation, sign up & subscribe – All at one place</p>
                    </div>

                    <div className="col p-5 rounded-2 btn-blue shadow-hover lwt-box">
                        <div className="bg-white rounded-2 w-fit number-box">
                            <h1 className="p-2 mb-4 text-blue font-medium text-[40px]">03</h1>
                        </div>
                        <h5 className="text-white text-bold text-xl">Start Saving Money!</h5>
                        <p className="text-white">Sit back and let us walk a mile in your shoes to save every last one of your penny!</p>
                    </div>
                </div>
            </div>
        </>
    )
}
