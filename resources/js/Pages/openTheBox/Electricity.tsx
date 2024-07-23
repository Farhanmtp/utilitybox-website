import { Image } from "react-bootstrap";
import { WhiteBorderButton } from "../../Components/elements/WhiteBorderButton";
import { ServiceBox } from "../../Components/elements/ServiceBox";
import { WorkTogether } from "../../Components/WorkTogether";
import { Testimonials } from "../../Components/Testimonials";
import { UmaasPartners } from "../../Components/UmaasPartners";
import { Head, Link } from "@inertiajs/react";
import Layout from "@/Layouts/Layout";

export default function Electricity() {
    return (
        <Layout>
            <Head>
                <title>Electricity</title>
            </Head>
            <div className="container-fluid p-3 hero-open-box position-sticky" style={{ top: "100px" }}>
                <Link href="/open-the-box/gas"><span className="position-absolute text-grey d-flex" style={{ right: 40, top: 40 }}>Gas <Image style={{ marginLeft: 15, width: "20px" }} src="../images/icons/Polygon 6.svg" /></span></Link>
                <div className="py-100 text-center" style={{ backgroundImage: `url(/images/electricbg.jpg)`, backgroundSize: "cover", height: "75vh" }}>
                    <h1 className="text-center text-grey mb-4"><b>Electricity</b></h1>
                    <p className="text-grey mb-5">Secure Your Business Electricity Contract,<br />Redefined by Our Utility Management Service*</p>
                    <div className="text-center d-flex gap-3 justify-content-center pt-5">
                        <WhiteBorderButton title="Learn More" link={route('about')} icon="null" />
                        <WhiteBorderButton title="Get a Deal!" link="/contact" icon="null" />
                    </div>
                </div>
            </div>

            <div className="bg-white border-top-copper position-relative mt-5 pt-5">
                <div className="container">
                    <h4 className="text-center">You have hit the right space if you want great value deals for your business energy<br /> by UK's first Utility Management Service. Generate your own contract online!</h4>

                    <hr className="mb-5 mt-5" />
                </div>

                <div className="container pt-5 pb-5">
                    <h2 className="text-center md:mb-5"><b>UMaaS</b> Services & Features</h2>
                </div>

                <div className="container p-5">
                    <div className="row">
                        <div className="col-lg-4 col-md-6 col-sm-12 pb-4">
                            <Link style={{ textDecoration: "none" }} href="/invoice-verification">
                                <ServiceBox title="INVOICE VERIFICATION" content="Our team conducts thorough checks to validate the accuracy of your energy invoices, identifying and resolving any billing errors or discrepancies." imgUrl="/images/icons/bills.png" />
                            </Link>
                        </div>
                        <div className="col-lg-4 col-md-6 col-sm-12 pb-4">
                            <Link style={{ textDecoration: "none" }} href="/energy-consumption-analysis">
                                <ServiceBox imgUrl="/images/icons/energy-consumption.png" title="ENERGY CONSUMPTION ANALYSIS" content="We perform detailed audits of your energy usage to assess patterns, identify inefficiencies, and offer recommendations for optimising energy consumption." />
                            </Link>
                        </div>
                        <div className="col-lg-4 col-md-6 col-sm-12 pb-4">
                            <Link style={{ textDecoration: "none" }} href="/meter-reading-reporting">
                                <ServiceBox imgUrl="/images/icons/meter.png" title="METER READING REPORTING" content="We collect and analyse gas and electricity meter readings, providing regular reports to track usage and monitor trends." />
                            </Link>
                        </div>
                        <div className="col-lg-4 col-md-6 col-sm-12 pb-4">
                            <Link style={{ textDecoration: "none" }} href="/tendering-and-contract-assistance">
                                <ServiceBox imgUrl="/images/icons/bills.png" title="TENDERING & CONTRACT ASSISTANCE" content="We assist in the tendering and contracting process for energy supplies, utilising our industry expertise to secure competitive pricing and favourable contract terms." />
                            </Link>
                        </div>
                        <div className="col-lg-4 col-md-6 col-sm-12 pb-4">
                            <Link style={{ textDecoration: "none" }} href="/supplier-management">
                                <ServiceBox imgUrl="/images/icons/supplier-management.png" title="SUPPLIER MANAGEMENT" content="We manage relationships with energy suppliers on your behalf, ensuring effective communication, issue resolution, and overall optimisation of the supplier relationship." />
                            </Link>
                        </div>
                        <div className="col-lg-4 col-md-6 col-sm-12 pb-4">
                            <ServiceBox imgUrl="/images/icons/cost.png" title="COMPETITIVE PRICES" content="Utility Box offer competitive prices on electric contracts from the market so you can be confident you are getting a good price. We help you save every penny to allow you to focus on your business!" />
                        </div>
                    </div>
                </div>

                <div className="bg-blue-to-light pt-5 pb-5">
                    <div className="container">
                        <h2 className="pb-5 text-center  text-white"><b>UMaaS</b> - Your Smart Solution</h2>

                        <div className="row">
                            <div className="col-lg-3 col-md-6 mb-4 md:mb-0">
                                <h5 className="mb-4 text-white text-xl font-medium">Best Electricity Deals for Hospitality Industry & More.</h5>
                                <p className="text-grey">At Utility Box, we provide great business electricity deals and services to businesses of all sizes across all industries in UK.</p>
                            </div>

                            <div className="col-lg-3 col-md-6 mb-4 md:mb-0">
                                <h5 className="mb-4 text-white text-xl font-medium">Never worry about losing time over billing charge mistake</h5>
                                <p className="text-grey">No draining of your time and energy over supplier mistakes on energy bills that should be spent focusing on your business.</p>
                            </div>

                            <div className="col-lg-3 col-md-6 mb-4 md:mb-0">
                                <h5 className="mb-4 text-white text-xl font-medium">Experienced Consultants</h5>
                                <p className="text-grey">Just leave it to the experts with 20+ years of experience to be certain you are getting a great deal on your business electricity in UK.</p>
                            </div>

                            <div className="col-lg-3 col-md-6">
                                <h5 className="mb-4 text-white text-xl font-medium">No overpaying of bills, forever</h5>
                                <p className="text-grey">Don't worry about overpaying of your monthly bills ever again! We will audit your electricity bills, every month.</p>
                            </div>
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
                                <WhiteBorderButton title="Contact Us" link="/contact" icon="null" />
                            </div>
                        </div>
                    </div>
                </div>

                <UmaasPartners />

                <WorkTogether />

                <div className="container">
                    <div className="row mb-5">
                        <div className="col-sm-12 col-md-6">
                            <Image className="mb-4" src="../images/grow.png" />
                            <h4 className="mb-4 text-semibold">Energy Expert</h4>
                            <p>Utility Box know that your business needs an energy expert, who you can trust. With our fully fixed, flexible, and blend & extend purchasing, we will find the perfect match of electricity supplier for your business and give you your purchasing power back!</p>
                        </div>

                        <div className="col-sm-12 col-md-6">
                            <Image className="mb-4" src="../images/bag.png" />
                            <h4 className="mb-4 text-semibold">Energy Procurement Expert</h4>
                            <p>Utility Box is UK based Energy Procurement Expert (EPE), which will help you monitor the market, so that your business can get the most from its energy purchasing strategy!</p>
                        </div>
                    </div>
                </div>

                <div className="bg-grey pt-5 pb-5">
                    <div className="container">
                        <div className="row">
                            <div className="col-lg-3">
                                <h2 className="font-medium">Testimonials</h2>
                            </div>
                            <div className="col-lg-9 test-slider">
                                <Testimonials />
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </Layout>
    )
}
