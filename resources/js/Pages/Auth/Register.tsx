import {FormEventHandler, useEffect} from 'react';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import {Head, Link, useForm} from '@inertiajs/react';
import Layout from "@/Layouts/Layout";
import { Button } from 'react-bootstrap';

export default function Register() {
    const {data, setData, post, processing, errors, reset} = useForm({
        first_name: '',
        last_name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    useEffect(() => {
        return () => {
            reset('password', 'password_confirmation');
        };
    }, []);

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('register'));
    };

    return (
        <Layout>
            <Head title="Register"/>
            <div className="md:flex">
                <div className="md:w-3/5 w-full container lg:px-32 lg:py-20 p-10 bg-white order-last">
                    <h2 className='text-4xl text-bold mb-4'>
                        Let's Get Started!
                    </h2>
                    <p className='mb-3'>Please enter your details.</p>
                    <form onSubmit={submit}>
                        <div className="mb-3">
                            <InputLabel htmlFor="email" value="Email"/>

                            <TextInput
                                id="email"
                                type="email"
                                name="email"
                                value={data.email}
                                className="mt-1 block w-full"
                                autoComplete="username"
                                onChange={(e) => setData('email', e.target.value)}
                                required
                            />

                            <InputError message={errors.email} className="mt-2"/>
                        </div>

                        {/* <div className="row"> */}
                            {/* <div className="col-md-6"> */}
                                <div className="mb-3">
                                    <InputLabel htmlFor="first_name" value="First Name"/>

                                    <TextInput
                                        id="first_name"
                                        name="first_name"
                                        value={data.first_name}
                                        className="mt-1 block w-full"
                                        autoComplete="first_name"
                                        isFocused={true}
                                        onChange={(e) => setData('first_name', e.target.value)}
                                        required
                                    />

                                    <InputError message={errors.first_name} className="mt-2"/>
                                </div>
                            {/* </div> */}
                            {/* <div className="col-md-6"> */}
                                <div className="mb-3">
                                    <InputLabel htmlFor="last_name" value="Last Name"/>

                                    <TextInput
                                        id="last_name"
                                        name="last_name"
                                        value={data.last_name}
                                        className="mt-1 block w-full"
                                        autoComplete="last_name"
                                        isFocused={true}
                                        onChange={(e) => setData('last_name', e.target.value)}
                                        required
                                    />

                                    <InputError message={errors.last_name} className="mt-2"/>
                                </div>
                            {/* </div> */}
                        {/* </div> */}

                        {/* <div className="row"> */}
                            {/* <div className="col-md-6"> */}
                                <div className="mb-3">
                                    <InputLabel htmlFor="password" value="Password"/>

                                    <TextInput
                                        id="password"
                                        type="password"
                                        name="password"
                                        value={data.password}
                                        className="mt-1 block w-full"
                                        autoComplete="new-password"
                                        onChange={(e) => setData('password', e.target.value)}
                                        required
                                    />

                                    <InputError message={errors.password} className="mt-2"/>
                                </div>
                            {/* </div> */}
                            {/* <div className="col-md-6"> */}
                                <div className="mb-3">
                                    <InputLabel htmlFor="password_confirmation" value="Re-Type Password"/>

                                    <TextInput
                                        id="password_confirmation"
                                        type="password"
                                        name="password_confirmation"
                                        value={data.password_confirmation}
                                        className="mt-1 block w-full"
                                        autoComplete="new-password"
                                        onChange={(e) => setData('password_confirmation', e.target.value)}
                                        required
                                    />

                                    <InputError message={errors.password_confirmation} className="mt-2"/>
                                </div>
                            {/* </div> */}
                        {/* </div> */}

                        <div className="items-center mb-[5vh]">
                            <Button type='submit' className="btn-blue border-none rounded-2 w-full" style={{paddingLeft: 45,paddingRight: 45,paddingTop:12,paddingBottom:12}} disabled={processing}>
                                Sign Up
                            </Button>
                        </div>

                        <span className='flex'>
                            Already Have an account? 
                            <Link
                            href={route('login')}
                            className="hover:underline text-copper text-bold hover:text-blue ml-1"
                            >
                                Sign in
                            </Link>
                        </span>
                    </form>
                </div>
                <div className="md:w-4/5 w-full bg-signup container">
                    <div className='container lg:p-20 p-10'>
                        <h2 className='mb-5 lg:text-4xl text-xl'><b>Ready to</b> revolutionise the way you <b>manage utilities?</b> Sign up with <b>Utility Box</b> 
                        <b> </b>and embark on a journey towards <b>utility solutions.</b>
                        </h2>
                        <ul className="">
                            <li className='mb-4'>
                                <span className="text-blue text-semibold">1. Create Your Account:</span> Fill in the form with your business details to kickstart your utility management journey.
                            </li>
                            <li className='mb-4'>
                                <span className="text-blue text-semibold">2. Verify Your Email:</span> A verification link will be sent to your email. Click to verify and activate your account.
                            </li>
                            <li className='mb-4'>
                                <span className="text-blue text-semibold">3. Personalised Consultation:</span> Our team will reach out to you for a brief consultation to understand your unique requirements better.
                            </li>
                            <li className='mb-4'>
                                <span className="text-blue text-semibold">4. Receive Your Quote:</span> Based on our discussion, we'll provide you with a personalised quote outlining our services and potential cost savings for your business.
                            </li>
                        </ul>
                    </div>
                </div> 
            </div>

            {/* <div className="container">
                <div className="row">
                    <div className="col-sm-10 offset-sm-1 col-md-6 offset-md-3 pt-5 pb-5">
                        <form onSubmit={submit}>
                            <div className="row">
                                <div className="col-md-6">
                                    <div className="mb-3">
                                        <InputLabel htmlFor="first_name" value="First Name"/>

                                        <TextInput
                                            id="first_name"
                                            name="first_name"
                                            value={data.first_name}
                                            className="mt-1 block w-full"
                                            autoComplete="first_name"
                                            isFocused={true}
                                            onChange={(e) => setData('first_name', e.target.value)}
                                            required
                                        />

                                        <InputError message={errors.first_name} className="mt-2"/>
                                    </div>
                                </div>
                                <div className="col-md-6">
                                    <div className="mb-3">
                                        <InputLabel htmlFor="last_name" value="Last Name"/>

                                        <TextInput
                                            id="last_name"
                                            name="last_name"
                                            value={data.last_name}
                                            className="mt-1 block w-full"
                                            autoComplete="last_name"
                                            isFocused={true}
                                            onChange={(e) => setData('last_name', e.target.value)}
                                            required
                                        />

                                        <InputError message={errors.last_name} className="mt-2"/>
                                    </div>
                                </div>
                            </div>

                            <div className="mb-3">
                                <InputLabel htmlFor="email" value="Email"/>

                                <TextInput
                                    id="email"
                                    type="email"
                                    name="email"
                                    value={data.email}
                                    className="mt-1 block w-full"
                                    autoComplete="username"
                                    onChange={(e) => setData('email', e.target.value)}
                                    required
                                />

                                <InputError message={errors.email} className="mt-2"/>
                            </div>
                            <div className="row">
                                <div className="col-md-6">
                                    <div className="mb-3">
                                        <InputLabel htmlFor="password" value="Password"/>

                                        <TextInput
                                            id="password"
                                            type="password"
                                            name="password"
                                            value={data.password}
                                            className="mt-1 block w-full"
                                            autoComplete="new-password"
                                            onChange={(e) => setData('password', e.target.value)}
                                            required
                                        />

                                        <InputError message={errors.password} className="mt-2"/>
                                    </div>
                                </div>
                                <div className="col-md-6">
                                    <div className="mb-3">
                                        <InputLabel htmlFor="password_confirmation" value="Confirm Password"/>

                                        <TextInput
                                            id="password_confirmation"
                                            type="password"
                                            name="password_confirmation"
                                            value={data.password_confirmation}
                                            className="mt-1 block w-full"
                                            autoComplete="new-password"
                                            onChange={(e) => setData('password_confirmation', e.target.value)}
                                            required
                                        />

                                        <InputError message={errors.password_confirmation} className="mt-2"/>
                                    </div>
                                </div>
                            </div>

                            <div className="flex items-center justify-end">
                                <Link
                                    href={route('login')}
                                    className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Already registered?
                                </Link>

                                <PrimaryButton className="ml-4" disabled={processing}>
                                    Register
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div> */}
        </Layout>
    );
}
