import Scgs from "@/Components/scgs/SCGS";
import Layout from "@/Layouts/Layout";
import {Head} from "@inertiajs/react";
// import { Navbar } from "../Components/Navbar";
// import {PageProps} from "@/types";




// eslint-disable-next-line react-refresh/only-export-components
export default function PCW() {
    return (
        <Layout>
            <Head>
                <title>SCGS</title>
            </Head>
            <style>
                {`
                  /* Your custom CSS styles here */
                  footer {
                    display:none;
                  }
                  .cta-btn {
                    display:none;
                  }
                  .navbar {
                    display:none;
                  }
                `}
            </style>
            <div className="">
                <Scgs />
            </div>

            {/* <div className="compare-nav">
            <Navbar />
            </div>
            <div className="iframe-container">
              <iframe
                src="http://quote.energywise.biz/"
                title="PCW - Power Comparison Website"
                frameBorder="0"
                allowFullScreen
              ></iframe>
            </div> */}
        </Layout>
    )
}
