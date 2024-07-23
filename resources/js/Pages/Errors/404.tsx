import Layout from "@/Layouts/Layout";
import {Head, usePage} from "@inertiajs/react";
import {PageProps} from "@/types";

export default function OpenTheBox() {
    const {status, message} = usePage<any>().props;
    return (
        <Layout>
            <Head title={'Home'}>
                <meta name="title" content="Home"/>
            </Head>

            <div>
                <div className="container text-center my-5">
                    {status == 404 ? (
                        <div>
                            <div>
                                <h1>{status}</h1>
                                <h2 className={'mb-3'}>Oops! Page not found.</h2>
                                <p>
                                    We could not find the page you were looking for.
                                    Meanwhile, you may <a href={route('home')}>return to home</a>.
                                </p>
                            </div>
                        </div>
                    ) : (
                        <div>
                            <div>
                                <h1>{status}</h1>
                                <p>{message}</p>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </Layout>
    )
}
