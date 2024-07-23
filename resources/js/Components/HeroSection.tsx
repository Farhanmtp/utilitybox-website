import {Button} from "react-bootstrap";
import ConsultationForm from "./elements/ConsultationForm";

export function HeroSection() {
    return (
        <div className="herosection" style={{backgroundImage: `url(/images/hero-bg.webp)`}}>
            <div className="overlay-grad">
                <div className="container md:p-50">
                    <div className="row">
                        <div className="col col-12 col-md-6 text-white align-self-center">
                            <h1 className="mb-4 text-45 text-52 fw-bold">UK's 1st Utility Management Provider</h1>
                            <Button className="bg-copper border-0 rounded-0 btn-copper text-semibold" style={{marginTop: 25}}>Compare Energy Deals</Button>
                        </div>
                        <div className="col col-12 col-md-6 flex text-white justify-content-center">
                            <ConsultationForm headingTitle="Get your Free Energy Quote" btnTitle="Get a Quote"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}
