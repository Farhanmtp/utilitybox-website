import {PageProps} from '@/types';
import {SelectButton} from '@/Components/elements/SelectButton';
import {BlueButton} from '@/Components/elements/BlueButton';
import {Image} from 'react-bootstrap';
import ServicesBoxes from '@/Components/ServicesBoxes';
import {WhiteBorderButton} from '@/Components/elements/WhiteBorderButton';
import {VideoSection} from '@/Components/VideoSection';
import {WorkTogether} from '@/Components/WorkTogether';
import Layout from "@/Layouts/Layout";

export default function Welcome({user, laravelVersion, phpVersion}: PageProps<{ laravelVersion: string, phpVersion: string }>) {
    return (
        <Layout>
            

            <div className="container-fluid p-3">
                <div className="py-100" style={{backgroundImage: `url(/images/openthebox.jpg)`, backgroundSize: "cover"}}>
                    <h1 className="text-center text-grey"><b>Introducing UMaaS</b> - Utility Management as a Service. <br/>Streamline & Optimise your <b>utility management!</b>
                    </h1>
                    <div className="text-center d-flex gap-3 justify-content-center">
                        <SelectButton title="Electricity" link="/open-the-box/electricity"/>
                        <SelectButton title="Gas" link="/open-the-box/gas"/>
                    </div>
                </div>
            </div>

            <div className="container pt-5 pb-5">
                <div className="row gap-5">
                    <div className="col-md-6 col-sm-12 align-self-center">
                        <h1 className="pb-5">Take <b>control</b> of your own <b>contracts</b></h1>
                        <BlueButton title="GET A DEAL!" link="/contact"/>
                    </div>
                    <div className="center-align col text-center">
                        <Image src="/images/home0a33.jpg" width="75%"/>
                    </div>
                </div>
            </div>

            <ServicesBoxes/>

            <div className="container py-16 pt-5 pb-5">
                <div className="row gap-5">
                    <div className="col-12 col-lg-5">
                        <h1 className="text-45"><b>Revolutionise</b> your <b>utility management</b> with our expertise.</h1>
                    </div>
                    <div className="col-12 col-lg-6">
                        <p>Navigating the utility landscape can be a daunting prospect. In fact, most utility companies actually count on you feeling that way. Keeping a close eye on your contracts and ensuring you’re working with the best option takes valuable time away from working on your business.</p>
                    </div>
                </div>
            </div>

            <Image src="/images/nasa-Q1p7bh3SHj8-unsplash.jpg" width="100%"/>

            <div className="container py-16 pt-5 pb-5">
                <div className="row gap-5">
                    <div className="col-12 col-lg-5">
                        <h1 className="text-4xl mb-4"><b>Our team</b> of experts<br/> will take care of<br/> every aspect</h1>
                        <p>Break free from Brokers! Say goodbye to utility brokers and embrace something truly revolutionary. Complete management of your utility contracts. Experience the difference now!</p>
                    </div>
                    <div className="col-12 col-lg-6">
                        <div className="container">
                            <div className="row gap-4">

                                <div className="bg-blue col-12 col-lg-5 p-4 rounded feature-container">
                                    <div className="row gap-1 feature-box">
                                        <div className="col-3">
                                            <i className="p-3 fa-solid fa-file-signature text-white" style={{fontSize: 28}}></i>
                                        </div>
                                        <div className="col-8"><h6 className="text-white">Negotiating contracts with suppliers</h6></div>
                                    </div>
                                </div>

                                <div className="bg-blue col-12 col-lg-5 p-4 rounded feature-container">
                                    <div className="row gap-1 feature-box">
                                        <div className="col-3">
                                            <i className="p-3 fa-solid fa-file-invoice text-white" style={{fontSize: 28}}></i>
                                        </div>
                                        <div className="col-8"><h6 className="text-white">Billing and invoicing</h6></div>
                                    </div>
                                </div>

                                <div className="bg-blue col-12 col-lg-5 p-4 rounded feature-container">
                                    <div className="row gap-1 feature-box">
                                        <div className="col-3">
                                            <i className="p-3 fa-solid fa-calculator text-white" style={{fontSize: 28}}></i>
                                        </div>
                                        <div className="col-8"><h6 className="text-white">Analysing utility usage</h6></div>
                                    </div>
                                </div>

                                <div className="bg-blue col-12 col-lg-5 p-4 rounded feature-container">
                                    <div className="row gap-1 feature-box">
                                        <div className="col-3">
                                            <i className="p-3 fa-solid fa-money-bill text-white" style={{fontSize: 28}}></i>
                                        </div>
                                        <div className="col-8"><h6 className="text-white">Identifying cost saving opportunities</h6></div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div className="container py-1">
                <div className="row gap-5 pt-5 pb-5">
                    <div className="col-12 col-lg-5">
                        <Image src="/images/change-your-plan.jpg" width="85%"/>
                    </div>
                    <div className="col-12 col-lg-6" style={{alignSelf: "center"}}>
                        <h3 className="mb-4 text-bold">Our Values</h3>
                        <p>For the longest time, the keyword in utility management was a switch. “By switching contracts you'll save money”, “Switching sends a message to the suppliers that things need to change”, and so on. The message was clear – that everyone’s best option was switching.<br/><br/>So whilst there may well be some value in switching, what consumers really need is better management. That’s where UMaaS comes in. It’s Utility Management as a Service. It’s a partnership with a team of utility experts who will help ensure that you are paying the best rates for your needs.</p>
                        <div className="bg-blue-to-light p-5 rounded">
                            <h5 className="mb-4 text-regular text-white"><b>FREE Audit</b><br/>
                                We'll show you how we can truly manage your utilities for you.<br/>
                                Free Contract Checking<br/>
                                Let our experts look at the contract you are about to sign
                                <br/><br/>So if you are looking at switching, that first switch should be to us!</h5>
                            <div className="flex d-flex gap-2">
                                <div><WhiteBorderButton title="Call Us" link="tel:+442039219000" icon="phone"/></div>
                                <div><WhiteBorderButton title="Email Us" link="mailto:info@utilitybox.org.uk" icon="mail"/></div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>


            <div className="container py-16">
                <hr className="mt-5" style={{opacity: "15%"}}/>

                <div className="row gap-5 pt-5 pb-5">
                    <div className="col-12 col-lg-5">
                        <h1><b>UMaaS</b> brings you <b>Swift Contract Generation System</b></h1>
                    </div>
                    <div className="col-12 col-lg-6">
                        <h4 className="mb-4 text-semibold">Powering Progress, Energizing Tomorrow: SCSG Energy Solutions</h4>
                        <p>With our innovative Swift Contract Generation System, clients now have the power to generate their own contracts directly on our website effortlessly. Our user-friendly interface puts the control in the hands of the client, allowing them to navigate through the contract creation process with ease. By simply inputting the necessary details and preferences, our system dynamically generates customized contracts tailored to your specific needs. This not only saves valuable time but also ensures accuracy and consistency in contract creation. With Swift Contract Generation
                            System, you can confidently create legally binding agreements in minutes, empowering you to take control of your business transactions.</p>
                    </div>
                </div>
            </div>

            {/* <div className="container pt-5 pb-5">
        <div className="row gap-5">
          <div className="center-align col text-center">
            <Image src="/images/5.jpg" width="85%" />
          </div>
          <div className="col-md-6 col-sm-12 align-self-center">
            <h2 className="pb-5 lh-base"><b>Brokers levy a 1p commission</b> solely for contract execution, whereas our pricing for <b>Utility Management</b> as a Service (UMaaS) ranges from <b>1p to 2p.</b></h2> */}
            {/* <BlueButton title="Learn More" link="/about" /> */}
            {/* </div>
        </div>
      </div> */}
            <VideoSection/>
            <WorkTogether/>
        </Layout>
    );
}
