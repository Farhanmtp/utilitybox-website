import {Image} from 'react-bootstrap'
import Layout from "@/Layouts/Layout";

export default function About() {
    return (
        <Layout>
            <Image src="/images/nasa-Q1p7bh3SHj8-unsplash.jpg" width="100%"/>
            <div className="container py-100">
                <div className="row gap-5">
                    <div className="col-12 col-lg-12">
                        <h1 className="text-45"><b>Introducing</b> a refreshing <b>new approach</b> to the management of your <b>commercial energy</b>.</h1>
                    </div>
                    <div className="col-12 col-lg-12">
                        <p>
                            Through our revolutionary concept, Utility Management as a Service (UMaaS), we aim to provide a comprehensive range of services that are designed to handle all utility needs. Empowering you to focus on your core business operations with peace of mind so that your business maximises the benefits of its energy purchasing strategy. Some of the services include energy account management, invoice validation, energy usage audit and supplier-relationship management.
                        </p>
                    </div>
                </div>
            </div>
        </Layout>
    )
}
