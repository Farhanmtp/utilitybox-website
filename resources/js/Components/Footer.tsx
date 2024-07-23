import {Container, Image} from "react-bootstrap"
import {Newsletter} from "./Newsletter"
import {Link, usePage} from "@inertiajs/react";
import {PageProps} from "@/types";

export function Footer() {
    const currentYear = new Date().getFullYear();

    const app = usePage<PageProps>().props.app;
    const slinks = app.socialLinks;

    return (
        <footer className="position-relative">
            <Newsletter/>
            <div className="bg-accent text-white">
                <div className="container">
                    <Container className="row py-100 gap-5">
                        <div className="col-sm">
                            <Link href="/"><Image className="mb-5" src="/images/logo-white.png" width="166px"/></Link>
                            {app.address &&
                                <p className="mb-4">{app.address}</p>
                            }
                            {app.phone &&
                                <a href={"tel:" + app.phone}><p className="mb-4">{app.phone}</p></a>
                            }
                            {app.email &&
                                <a href={"mailto:" + app.email}><p className="mb-4">{app.email}</p></a>
                            }
                        </div>
                        <div className="col-sm">
                            <h4 className="mb-4">Navigate</h4>
                            <Link className="mb-3 d-block" href="/">Home</Link>
                            <Link className="mb-3 d-block" href="/about">About</Link>
                            <Link className="mb-3 d-block" href="/">Open-the-box</Link>
                            <Link className="mb-3 d-block" href="/contact">Contact</Link>
                        </div>
                        <div className="col-sm">
                            <h4 className="mb-4">Links</h4>
                            <Link className="mb-3 d-block" href="/contact#faq">FAQ</Link>
                            <Link className="mb-3 d-block" href="/blog">Blog</Link>
                            <Link className="mb-3" href="/terms-and-conditions" >Terms & Conditions</Link>
                        </div>
                        {(slinks.facebook || slinks.instagram || slinks.twitter || slinks.linkedin) &&
                            <div className="col-sm">
                                <h4 className="mb-4">Social Links</h4>
                                <div className="flex d-flex gap-3">
                                    {slinks.facebook &&
                                        <Link className="mb-3" href={slinks.facebook}><Image className="mb-5" src="/images/icons/fb.png" width="50px"/></Link>
                                    }
                                    {slinks.instagram &&
                                        <Link className="mb-3" href={slinks.instagram}><Image className="mb-5" src="/images/icons/insta.png" width="50px"/></Link>
                                    }
                                    {slinks.twitter &&
                                        <Link className="mb-3" href={slinks.twitter}><Image className="mb-5" src="/images/icons/twitter.png" width="50px"/></Link>
                                    }
                                    {slinks.linkedin &&
                                        <Link className="mb-3" href={slinks.linkedin}><Image className="mb-5" src="/images/icons/linkedin.png" width="50px"/></Link>
                                    }
                                </div>
                            </div>
                        }
                    </Container>
                    <hr/>
                    <div className="copyrights py-16 pb-5">
                        <p className="mb-0">© All Rights Reserved - { app.name || "Utility Box" } {currentYear}</p>
                    </div>
                </div>
            </div>
        </footer>
    )
}
