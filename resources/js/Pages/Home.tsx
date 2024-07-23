import {HeroSection} from "../Components/HeroSection"
import {Button, Image} from 'react-bootstrap'
import {WhiteBorderButton} from "../Components/elements/WhiteBorderButton"
import {VideoSection} from "../Components/VideoSection"
import {WorkTogether} from "../Components/WorkTogether"
import {BlueButton} from "../Components/elements/BlueButton"
import Layout from "@/Layouts/Layout";

export default function Home() {
    return (
        <Layout>
            <HeroSection/>

            <div className="container py-100 pb-lg-1 gap-4">
                <div className="row">
                    <div className="col-sm-12 col-md-6">
                        <Image className="" src="/images/image-iojjf.jpg" width="80%"/>
                        <Image className="image-float" src="/images/american-public-power-association-Zy1mDOLhUB4-unsplash.png" width="50%"/>
                    </div>
                    <div className="col-sm-12 col-md-6 pt-50">
                        <h1>Ensuring <b>solutions</b><br/>to your <b>utility</b> need</h1>
                        <p className="pb-4">Electricity <span className="text-copper">/</span> Gas <span className="text-copper">/</span> UB Energy Solutions</p>
                        <BlueButton title="GET A DEAL!" link="/contact"/>
                    </div>
                </div>
            </div>

            <div className="bg-light-to-blue text-white pt-5 pb-5">
                <div className="container">
                    <div className="flex d-flex flex-column flex-md-row gap-5 align-items-md-center">
                        <div className="text-center text-md-start" style={{marginRight: 35}}>
                            <h2>We are here to<br/>serve <b>you Right</b></h2>
                            <Button className="bg-copper border-0 rounded-1 btn-copper text-semibold" style={{marginTop: 25}}>LEARN MORE</Button>
                        </div>

                        <div className="row gap-5">
                            <div className="col-12 col-md-auto">
                                <div className="flex d-flex gap-4 h-100 align-items-center">
                                    <div className="bg-white rounded-50">
                                        <Image src="/images/icons/stack.png" width="30"/>
                                    </div>
                                    <p className="mb-0">No hidden <br/><b>Commission</b></p>
                                </div>
                            </div>

                            <div className="col-12 col-md-auto">
                                <div className="flex d-flex gap-4 h-100 align-items-center">
                                    <div className="bg-white rounded-50">
                                        <Image src="/images/icons/pound.png" width="30"/>
                                    </div>
                                    <p className="mb-0">No hidden <br/><b>Cost</b></p>
                                </div>
                            </div>

                            <div className="col-12 col-md-auto">
                                <div className="flex d-flex gap-4 h-100 align-items-center">
                                    <div className="bg-white rounded-50">
                                        <Image src="/images/icons/cart.png" width="30"/>
                                    </div>
                                    <p className="mb-0">Smart<br/><b>Purchases</b></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div className="container pt-5 pb-5">
                <div className="row gap-5">
                    <div className="col-md-6 col-sm-12 align-self-center">
                        <h2 className="pb-5"><b>UtilityBox is</b> UK's First Company <br/>to offer <b>Utility Management</b><br/><b>Based Subscription Model</b></h2>
                        <BlueButton title="GET A DEAL!" link="/contact"/>
                    </div>
                    <div className="center-align col text-center">
                        <Image src="/images/home0a33.jpg" width="75%"/>
                    </div>
                </div>
            </div>

            <VideoSection/>

            <WorkTogether/>

            <div className="bg-grey pt-5 pb-5">
                <div className="container">
                    <h2 className="mb-5 text-center">Single Hook For All Your <br/>Business <b>Utility Needs!</b></h2>

                    <Image className="rounded-4" src="/images/UB-SERVICES-4.jpg" width="100%"/>
                </div>
            </div>

            <div className="bg-black text-white pt-5 pb-4">
                <div className="container">
                    <div className="row">
                        <div className="col-lg-9 col-sm-12">
                            <p className="lh-lg">Looking for a one-stop shop for all your utility needs? We offer services ranging from bill validations to change of tenancies. Check out all our energy consultancy services!</p>
                        </div>
                        <div className="col-lg-3 col-sm-12">
                            <WhiteBorderButton title="Learn More" link="/about" icon="null"/>
                        </div>
                    </div>
                </div>
            </div>

            <div className="container pt-5 pb-5">
                <div className="row gap-5">
                    <div className="center-align col text-center">
                        <Image src="/images/5.jpg" width="85%"/>
                    </div>
                    <div className="col-md-6 col-sm-12 align-self-center">
                        <h2 className="pb-5 lh-base">We Guarantee The <b>Best Rates Without Hidden Commissions</b> or Fees by Partnering with all Major <b>Energy Providers</b></h2>
                        <BlueButton title="Learn More" link="/about"/>
                    </div>
                </div>
            </div>
        </Layout>
    )
}
