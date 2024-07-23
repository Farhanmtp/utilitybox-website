import UpdatePasswordForm from './Partials/UpdatePasswordForm';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm';
import {Head} from '@inertiajs/react';
import {PageProps} from '@/types';
import Layout from "@/Layouts/Layout";

export default function Edit({user, mustVerifyEmail, status}: PageProps<{ mustVerifyEmail: boolean, status?: string }>) {
    return (
        <Layout>
            <Head title="Profile"/>
            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                    <div className="p-4 sm:p-8 bg-white border border-1 border-gray-500 sm:rounded-lg">
                        <UpdateProfileInformationForm
                            mustVerifyEmail={mustVerifyEmail}
                            status={status}
                        />
                    </div>

                    <div className="p-4 sm:p-8 bg-white border border-1 border-gray-500 sm:rounded-lg">
                        <UpdatePasswordForm className="max-w-xl"/>
                    </div>

                    {/*<div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <DeleteUserForm className="max-w-xl"/>
                    </div>*/}
                </div>
            </div>
        </Layout>
    );
}
