import { Button, Image } from "react-bootstrap";
import { WorkTogether } from "@/Components/WorkTogether";
import { BlueButton } from "@/Components/elements/BlueButton";
import { VideoSection } from "@/Components/VideoSection";
import { Head } from "@inertiajs/react";
import Layout from "@/Layouts/Layout";
import { WhiteBorderButton } from "@/Components/elements/WhiteBorderButton";
import { Testimonials } from "@/Components/Testimonials";
import BlogCarousel from "@/Components/BlogCarousel";
import { HeroSection } from "@/Components/HeroSection";
import ClientSavingsSection from "../Components/ClientSavingsSection";
import { PageProps } from "@/types";
import { Link } from "@inertiajs/react";


export default function Landing({ latestPosts }: PageProps<{ latestPosts?: any }>) {

    const clients = [
        { companyName: 'Fruity Fresh (Western) Limited', companyLogo: '/images/freshfruit.png', supplyType: 'Electricity', savingAmount: '36,141.57' },
        { companyName: 'Steam Traction World Limited', companyLogo: '/images/steam.png', supplyType: 'Electricity', savingAmount: '3,717.37' },
        { companyName: 'Teme Valley Brewery', companyLogo: '/images/teme-valley-logo.png', supplyType: 'Electricity', savingAmount: '5,307.33' },
        { companyName: 'Saach Interiors Ltd', companyLogo: '/images/SAACH.png', supplyType: 'Electricity', savingAmount: '3,973.52' }
    ];
    console.log(latestPosts)
    return (
        <Layout>
            <Head>
                <title>UtilityBox: Your One-Stop Solution for Utility Management</title>
                <meta name="robots" content="noindex" />
                <meta
                    name="description"
                    content="UMaaS: Redefining utility management for businesses. Optimize energy accounts, audits & supplier ties effortlessly. Unburden operations, boost success."
                />
            </Head>
            <HeroSection />

            <div>
                <div className="container py-5">
                    <p className="text-center">
                        We compare a vetted panel of trusted UK business energy suppliers
                    </p>
                    <div className="slider">
                        <div className="slide-track">
                            <div className="slide">
                                <img src="/images/energy-carousel.2d69c827.png" />
                            </div>
                            <div className="slide">
                                <img src="/images/energy-carousel.2d69c827.png" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {/* Steps Section */}
            <div className="container mt-5 text-center">
                <h2>
                    <b>Switch Your Energy Supplier in 3 steps</b>
                </h2>
            </div>

            <WorkTogether />

            {/* Business Section */}
            <div className="container py-16">
                <div className="row gap-4 justify-evenly">
                    <h2 className="text-center text-semibold mb-4">
                        Business Energy Comparison
                    </h2>

                    <div className="bg-grey border col-12 col-lg-5 p-4 rounded feature-container">
                        <div className="row gap-3 text-center feature-box">
                            <div className="col-12">
                                <i
                                    className="p-3 fa-solid fa-bolt"
                                    style={{ fontSize: 28 }}
                                ></i>
                            </div>
                            <div className="col-12">
                                <h4 className="text-blue font-medium">Business Electricity</h4>
                            </div>
                            <p>
                                Let us help you significantly reduce your business electricity
                                bills, we can get you the best business electricity quotes
                                within the market.
                            </p>
                        </div>
                    </div>

                    <div className="bg-grey border col-12 col-lg-5 p-4 rounded feature-container">
                        <div className="row gap-3 text-center feature-box">
                            <div className="col-12">
                                <i
                                    className="p-3 fa-solid fa-fire-flame-simple"
                                    style={{ fontSize: 28 }}
                                ></i>
                            </div>
                            <div className="col-12">
                                <h4 className="text-blue font-medium">Business Gas</h4>
                            </div>
                            <p>
                                Access live business gas prices in the market in a few simple
                                steps. We can save you up to 45% on your business gas bills.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            {/* Our Team Section */}
            <div className="container mx-auto py-16 pt-5 pb-5 my-5">
                <div className="row gap-5 justify-center">
                    <div className="col-12 col-lg-5">
                        <h3 className="text-[32px] md:text-[40px] mb-4">
                            <b>Our team</b> of experts
                            <br /> will take care of
                            <br /> every aspect
                        </h3>
                        <p>
                            Break free from Brokers! Say goodbye to utility brokers and
                            embrace something truly revolutionary. Complete management of your
                            utility contracts. Experience the difference now!
                        </p>
                    </div>
                    <div className="col-12 col-lg-6">
                        <div className="container">
                            <div className="row gap-4">
                                <div className="bg-blue col-12 col-lg-5 p-4 rounded feature-container">
                                    <div className="row gap-1 feature-box">
                                        <div className="col-3">
                                            <i
                                                className="p-3 fa-solid fa-file-signature text-white"
                                                style={{ fontSize: 28 }}
                                            ></i>
                                        </div>
                                        <div className="col-8">
                                            <h6 className="text-white">
                                                Negotiating contracts with suppliers
                                            </h6>
                                        </div>
                                    </div>
                                </div>

                                <div className="bg-blue col-12 col-lg-5 p-4 rounded feature-container">
                                    <div className="row gap-1 feature-box">
                                        <div className="col-3">
                                            <i
                                                className="p-3 fa-solid fa-file-invoice text-white"
                                                style={{ fontSize: 28 }}
                                            ></i>
                                        </div>
                                        <div className="col-8">
                                            <h6 className="text-white">Billing and invoicing</h6>
                                        </div>
                                    </div>
                                </div>

                                <div className="bg-blue col-12 col-lg-5 p-4 rounded feature-container">
                                    <div className="row gap-1 feature-box">
                                        <div className="col-3">
                                            <i
                                                className="p-3 fa-solid fa-calculator text-white"
                                                style={{ fontSize: 28 }}
                                            ></i>
                                        </div>
                                        <div className="col-8">
                                            <h6 className="text-white">Analysing utility usage</h6>
                                        </div>
                                    </div>
                                </div>

                                <div className="bg-blue col-12 col-lg-5 p-4 rounded feature-container">
                                    <div className="row gap-1 feature-box">
                                        <div className="col-3">
                                            <i
                                                className="p-3 fa-solid fa-money-bill text-white"
                                                style={{ fontSize: 28 }}
                                            ></i>
                                        </div>
                                        <div className="col-8">
                                            <h6 className="text-white">Supplier Management</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {/* We Are Here */}
            <div className="bg-light-to-blue text-white pt-5 pb-5">
                <div className="container mx-auto">
                    <div className="md:flex items-center justify-center space-y-5 md:space-y-0">
                        <div
                            className="text-center text-md-start md:mr-8"
                        >
                            <h2>
                                We are here to
                                <br />
                                serve <b>you Right</b>
                            </h2>
                            <Link href={'/contact'}>
                                <Button
                                    className="bg-copper border-0 rounded-1 btn-copper text-semibold"
                                    style={{ marginTop: 25 }}
                                >
                                    LEARN MORE
                                </Button>
                            </Link>
                        </div>
                        <div className="flex flex-wrap items-center justify-start gap-4">
                            <div className="col-12 col-md-auto">
                                <div className="flex d-flex gap-4 h-100 align-items-center">
                                    <div className="bg-white rounded-50">
                                        <Image src="/images/icons/stack.png" width="30" />
                                    </div>
                                    <p className="mb-0">
                                        No hidden <br />
                                        <b>Commission</b>
                                    </p>
                                </div>
                            </div>

                            <div className="col-12 col-md-auto">
                                <div className="flex d-flex gap-4 h-100 align-items-center">
                                    <div className="bg-white rounded-50">
                                        <Image src="/images/icons/pound.png" width="30" />
                                    </div>
                                    <p className="mb-0">
                                        No hidden <br />
                                        <b>Cost</b>
                                    </p>
                                </div>
                            </div>

                            <div className="col-12 col-md-auto">
                                <div className="flex d-flex gap-4 h-100 align-items-center">
                                    <div className="bg-white rounded-50">
                                        <Image src="/images/icons/cart.png" width="30" />
                                    </div>
                                    <p className="mb-0">
                                        Smart
                                        <br />
                                        <b>Purchases</b>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {/* Ensuring Section */}
            <div className="d-none container pt-5 pb-5">
                <div className="row gap-5">
                    <div className="col-md-6 col-sm-12 align-self-center">
                        <h2 className="pb-5">
                            <b>Ensuring smooth sailing</b> through accurate and{" "}
                            <b>transparent billing</b>.
                        </h2>
                        <BlueButton title="GET A DEAL!" link="/compare" />
                    </div>
                    <div className="center-align col text-center">
                        <Image src="/images/home0a33.jpg" width="75%" />
                    </div>
                </div>
            </div>
            <div className="container mx-auto py-5">
                <div className="row gap-5">
                    <div className="center-align col text-center">
                        <Image src="/images/dedication.png" />
                    </div>
                    <div className="col-md-6 col-sm-12 align-self-center">
                        <h2 className="pb-5 lh-base">
                            Why have a broker when you can have a dedicated{" "}
                            <strong>Utility Manager</strong>
                        </h2>
                        <BlueButton title="Learn More" link="/about" />
                    </div>
                </div>
            </div>
            {/* Video Section */}
            <VideoSection />
            {/*Client Savings Section */}
            <ClientSavingsSection />
            <div className="bg-black text-white pt-5 pb-4">
                <div className="container">
                    <div className="row">
                        <div className="col-lg-9 col-sm-12">
                            <p className="lh-lg">
                                Looking for a one-stop shop for all your utility needs? We offer
                                services ranging from bill validations to change of tenancies.
                                Check out all our energy consultancy services!
                            </p>
                        </div>
                        <div className="col-lg-3 col-sm-12">
                            <WhiteBorderButton title="Learn More" link="/about" icon="null" />
                        </div>
                    </div>
                </div>
            </div>
            {/* Testimonials */}
            <Testimonials />
            {/* Blogs Section */}
            <BlogCarousel latestPosts={latestPosts} />
        </Layout>
    );
}
