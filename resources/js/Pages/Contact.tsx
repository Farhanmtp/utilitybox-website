import {Button, Image} from 'react-bootstrap';
import {ContactForm} from '@/Forms/ContactForm';
import {FaqSection} from '@/Components/FaqSection';
import {Head, Link, usePage} from '@inertiajs/react';
import Layout from "@/Layouts/Layout";
import {PageProps} from "@/types";

export default function Contact() {
    const app = usePage<PageProps>().props.app;
    const slinks = app.socialLinks;
    return (
        <Layout>
            <Head title="Contact"></Head>
            <div className="bg-grey">
                <div className="container">
                    <div className="row">
                        <div className="col-sm-11 col-md-5 contact-image py-100" style={{backgroundImage: `url(/images/bulbbb.jpg)`, backgroundSize: "cover", backgroundPosition: "center"}}>
                            <div className="container" style={{marginLeft: "0"}}>
                                {app.phone &&
                                    <Button className="btn-grey text-semibold mb-3 d-flex" href={"tel:" + app.phone}>
                                        <Image className="mb-1" style={{marginRight: 10}} src="/images/icons/call.png" width={20} alt="Call Icon"/> {app.phone}
                                    </Button>
                                }
                                {app.email &&
                                    <Button className="btn-grey text-semibold mb-3 d-flex" href="mailto:HELLO@UTILITYBOX.ORG.UK">
                                        <Image className="mb-1" style={{marginRight: 10}} src="/images/icons/mail.png" width={20} alt="Mail Icon"/> {app.email}
                                    </Button>
                                }
                                {app.address &&
                                    <Button className="btn-grey text-semibold d-flex" style={{marginBottom: "6rem"}} href="#">
                                        <Image className="mb-1" style={{marginRight: 10, alignSelf: 'center'}} src="/images/icons/loc.png" height={22} width={22} alt="address Icon"/> {app.address}
                                    </Button>
                                }
                                {(slinks.facebook || slinks.instagram || slinks.twitter || slinks.linkedin) &&
                                    <div className="flex d-flex gap-3">
                                        {slinks.facebook &&
                                            <a className="mb-3" href={slinks.facebook}><Image className="mb-5" src="/images/icons/fb.png" width="50px"/></a>
                                        }
                                        {slinks.instagram &&
                                            <a className="mb-3" href={slinks.instagram}><Image className="mb-5" src="/images/icons/insta.png" width="50px"/></a>
                                        }
                                        {slinks.twitter &&
                                            <Link className="mb-3" href={slinks.twitter}><Image className="mb-5" src="/images/icons/twitter.png" width="50px"/></Link>
                                        }
                                        {slinks.linkedin &&
                                            <Link className="mb-3" href={slinks.linkedin}><Image className="mb-5" src="/images/icons/linkedin.png" width="50px"/></Link>
                                        }
                                    </div>
                                }
                            </div>
                        </div>
                        <div className="col-sm-11 col-md-6 pt-5 pb-5">
                            <div className="container">
                                <h2 className="mb-4">Connect <b>with Us</b></h2>
                                <ContactForm/>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="faqSection" className="container pt-5 pb-5">
                <h2 className="mb-5">Frequently Asked <br/><b>Questions</b></h2>
                <FaqSection/>
            </div>
        </Layout>
    )
}
