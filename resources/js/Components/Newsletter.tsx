import { NewsletterForm } from "@/Forms/NewsletterForm";
import { WhiteBorderButton } from "./elements/WhiteBorderButton";

export function Newsletter() {
    return (
        <div className="Newsletter bg-light-to-blue text-white py-5">
            <div className="container d-lg-flex">
                <div className="col-lg-6 col-md-12 md:mb-auto mb-5">
                    <h3 className="font-medium mb-2">Have a Question?</h3>
                    <p className="mb-4">Get your online quotation, sign up & subscribe- All at one place</p>
                    <WhiteBorderButton title="FAQ's" link="/contact#faqSection" icon="null" />
                </div>
                <div className="col-lg-6 col-md-12 pt-lg-0 pt-sm-5">
                    <NewsletterForm />
                </div>
            </div>
        </div>
    )
}
