import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import {Link, useForm, usePage} from '@inertiajs/react';
import {Transition} from '@headlessui/react';
import {FormEventHandler} from 'react';
import {PageProps} from '@/types';

export default function UpdateProfileInformation({mustVerifyEmail, status, className = ''}: { mustVerifyEmail: boolean, status?: string, className?: string }) {
    const user = usePage<PageProps>().props.user;

    const {data, setData, patch, errors, processing, recentlySuccessful} = useForm({
        first_name: user.first_name,
        last_name: user.last_name,
        email: user.email,
        phone: user.phone,
        date_of_birth: user.date_of_birth,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        patch(route('profile.update'));
    };

    return (
        <section className="w-full">
            <header>
                <h2 className="text-lg font-medium text-gray-900">Profile Information</h2>

                <p className="mt-1 text-sm text-gray-600">
                    Update your account's profile information and email address.
                </p>
            </header>

            <form onSubmit={submit} className="mt-6 space-y-6">
                <div className="grid sm:grid-cols-2 grid-cols-1 gap-4">
                    <div>
                        <InputLabel htmlFor="first_name" value="First Name"/>

                        <TextInput
                            id="first_name"
                            className="mt-1 block w-full"
                            value={data.first_name ?? ''}
                            onChange={(e) => setData('first_name', e.target.value)}
                            required
                            isFocused
                            autoComplete="first_name"
                        />

                        <InputError className="mt-2" message={errors.first_name}/>
                    </div>
                    <div>
                        <InputLabel htmlFor="last_name" value="Last Name"/>
                        <TextInput
                            id="last_name"
                            className="mt-1 block w-full"
                            value={data.last_name ?? ''}
                            onChange={(e) => setData('last_name', e.target.value)}
                            required
                            isFocused
                            autoComplete="last_name"
                        />
                        <InputError className="mt-2" message={errors.last_name}/>
                    </div>

                    <div>
                        <InputLabel htmlFor="email" value="Email"/>

                        <TextInput
                            id="email"
                            type="email"
                            className="mt-1 block w-full"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            required
                            autoComplete="email"
                        />
                        <InputError className="mt-2" message={errors.email}/>
                        {mustVerifyEmail && user.email_verified_at === null && (
                            <div>
                                <p className="text-sm mt-2 text-gray-800">
                                    Your email address is unverified.
                                    <Link
                                        href={route('verification.send')}
                                        method="post"
                                        as="button"
                                        className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    >
                                        Click here to re-send the verification email.
                                    </Link>
                                </p>

                                {status === 'verification-link-sent' && (
                                    <div className="mt-2 font-medium text-sm text-green-600">
                                        A new verification link has been sent to your email address.
                                    </div>
                                )}
                            </div>
                        )}
                    </div>

                    <div>
                        <InputLabel htmlFor="phone" value="Phone"/>
                        <TextInput
                            id="phone"
                            type="text"
                            className="mt-1 block w-full"
                            value={data.phone}
                            onChange={(e) => setData('phone', e.target.value)}
                            required
                            autoComplete="phone"
                        />
                        <InputError className="mt-2" message={errors.phone}/>
                    </div>
                    <div>
                        <InputLabel htmlFor="date_of_birth" value="Date Of Birth"/>
                        <TextInput
                            id="date_of_birth"
                            type="date"
                            className="mt-1 block w-full"
                            value={data.date_of_birth}
                            onChange={(e) => setData('date_of_birth', e.target.value)}
                            required
                            autoComplete="date_of_birth"
                        />
                        <InputError className="mt-2" message={errors.date_of_birth}/>
                    </div>
                </div>

                <div className="flex items-center gap-4">
                    <PrimaryButton disabled={processing}>Save</PrimaryButton>

                    <Transition
                        show={recentlySuccessful}
                        enter="transition ease-in-out"
                        enterFrom="opacity-0"
                        leave="transition ease-in-out"
                        leaveTo="opacity-0"
                    >
                        <p className="text-sm text-gray-600">Saved.</p>
                    </Transition>
                </div>
            </form>
        </section>
    );
}
