import {Image} from "react-bootstrap";
import {WhiteBorderButton} from "../../Components/elements/WhiteBorderButton";
import {ServiceBox} from "../../Components/elements/ServiceBox";
import {WorkTogether} from "../../Components/WorkTogether";
import {Testimonials} from "../../Components/Testimonials";
import {UmaasPartners} from "../../Components/UmaasPartners";
import Layout from "@/Layouts/Layout";
import { Link } from "@inertiajs/react";

export default function Gas() {
    return (
        <Layout>
            <div className="container-fluid p-3 hero-open-box position-sticky" style={{top: "100px"}}>
                <Link href="/open-the-box/electricity"><span className="position-absolute text-grey d-flex" style={{right: 40, top: 40}}>Electricity <Image style={{marginLeft: 15, width: "20px"}} src="../images/icons/Polygon 6.svg"/></span></Link>
                <div className="py-100 text-center" style={{backgroundImage: `url(/images/gasbg.jpg)`, backgroundSize: "cover", height: "75vh"}}>
                    <h1 className="text-center text-grey mb-4"><b>Gas</b></h1>
                    <p className="text-grey mb-5">Secure Your Business Gas Contract,<br/>Redefined by Our Utility Management Service</p>
                    <div className="text-center d-flex gap-3 justify-content-center pt-5">
                        <WhiteBorderButton title="Learn More" link="/compare" icon="null"/>
                        <WhiteBorderButton title="Get a Deal!" link="/contact" icon="null"/>
                    </div>
                </div>
            </div>

            <div className="bg-white border-top-copper position-relative mt-5 pt-5">
                <div className="container">
                    <h4 className="text-center">You have hit the right space if you want great value deals <br/>for your business energy
                        by UK's first Utility Management Service.<br/> Generate your own contract online!</h4>

                    <hr className="mb-5 mt-5"/>
                </div>

                <div className="container pt-5 pb-5">
                    <h2 className="text-center mb-5"><b>UMaaS</b> Services & Features</h2>
                </div>

                <div className="fluid-container p-5">
                    <div className="row">
                        <div className="col-lg-4 col-md-6 col-sm-12 pb-4">
                            <ServiceBox title="INVOICE VERIFICATION" content="Our team conducts thorough checks to validate the accuracy of your energy invoices, identifying and resolving any billing errors or discrepancies." imgUrl="/images/invoice.jpg"/>
                        </div>
                        <div className="col-lg-4 col-md-6 col-sm-12 pb-4">
                            <ServiceBox imgUrl="/images/energy-consumption.jpg" title="ENERGY CONSUMPTION ANALYSIS" content="We perform detailed audits of your energy usage to assess patterns, identify inefficiencies, and offer recommendations for optimising energy consumption."/>
                        </div>
                        <div className="col-lg-4 col-md-6 col-sm-12 pb-4">
                            <ServiceBox imgUrl="/images/meter-reading.jpg" title="METER READING REPORTING" content="We collect and analyse gas and electricity meter readings, providing regular reports to track usage and monitor trends."/>
                        </div>
                        <div className="col-lg-4 col-md-6 col-sm-12 pb-4">
                            <ServiceBox imgUrl="/images/contract-assistance.jpg" title="TENDERING & CONTRACT ASSISTANCE" content="We assist in the tendering and contracting process for energy supplies, utilising our industry expertise to secure competitive pricing and favourable contract terms."/>
                        </div>
                        <div className="col-lg-4 col-md-6 col-sm-12 pb-4">
                            <ServiceBox imgUrl="/images/supplier-management.jpg" title="SUPPLIER MANAGEMENT" content="We manage relationships with energy suppliers on your behalf, ensuring effective communication, issue resolution, and overall optimisation of the supplier relationship."/>
                        </div>
                        <div className="col-lg-4 col-md-6 col-sm-12 pb-4">
                            <ServiceBox imgUrl="/images/competitive-prices.jpg" title="COMPETITIVE PRICES" content="Utility Box offer competitive prices on electric contracts from the market so you can be confident you are getting a good price. We help you save every penny to allow you to focus on your business!"/>
                        </div>
                    </div>
                </div>

                <div className="bg-black text-white pt-5 pb-5">
                    <div className="container">
                        <div className="row">
                            <div className="col-lg-9 col-sm-12">
                                <p className="lh-lg">Got a question? Maybe we have already read your mind and answered in our FAQs.</p>
                            </div>
                            <div className="col-lg-3 col-sm-12">
                                <WhiteBorderButton title="Contact Us" link="/contact" icon="null"/>
                            </div>
                        </div>
                    </div>
                </div>

                <UmaasPartners/>

                <WorkTogether/>

                <div className="container">
                    <div className="row mb-5">
                        <div className="col-sm-12 col-md-6">
                            <Image className="mb-4" src="../images/grow.png"/>
                            <h4 className="mb-4 text-semibold">RIGHT CONTRACT</h4>
                            <p>Finding the right contract for your business gas can save you thousands of Pounds, an opportunity not to be missed.</p>
                        </div>

                        <div className="col-sm-12 col-md-6 md:mt-0 mt-[30px]">
                            <Image className="mb-4" src="../images/bag.png"/>
                            <h4 className="mb-4 text-semibold">RIGHT DEAL</h4>
                            <p>Whether you are small start-up or large business industry, Utility Box will help you find the right business gas deal as your trusted business gas adviser and procurement expert to help you manage your gas supply cost!</p>
                        </div>
                    </div>
                </div>

                <div className="bg-grey pt-5 pb-5">
                    <div className="container">
                        <div className="row">
                            <div className="col-lg-3">
                                <h2>Testimonials</h2>
                            </div>
                            <div className="col-lg-9 test-slider">
                                <Testimonials/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </Layout>
    )
}
