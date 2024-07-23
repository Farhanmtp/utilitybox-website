import React from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import Checkbox from '@/Components/Checkbox';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import { FormEventHandler, useEffect, useState } from 'react';
import { Button, Image, Modal } from 'react-bootstrap';
import { useGlobalState } from '@/Layouts/elements/PopupContext';

export function LoginPopUp({
  status,
  canResetPassword,
}: {
  status?: string;
  canResetPassword: boolean;
}) {
  const { data, setData, post, processing, errors, reset } = useForm({
    email: '',
    password: '',
    remember: false,
  });

  useEffect(() => {
    return () => {
      reset('password');
    };
  }, []);

  const submit: FormEventHandler = (e) => {
    e.preventDefault();
    post(route('login'));
  };

  const { showModal, setShowModal } = useGlobalState();

  // const [showModal1, setShowModal1] = useState(false);

  useState(() => {
    setShowModal(false);
  });

  const handleCloseModal = () => {
    setShowModal(false);
  };

  return (
    <div>
      <Modal show={showModal} onHide={handleCloseModal} size="xl" centered>
        <Modal.Body className="p-0">
          <div className="flex">
            <div className="lg:w-50 w-full p-4">
              <Image src="images/favicon.png" width={40} />

              <div className="p-2 p-sm-5">
                <h3 className="mb-4 text-center text-semibold">Welcome back</h3>
                <p className="mb-5 text-center text-sm">Please enter your details.</p>

                <form onSubmit={submit}>
                  <div>
                    <InputLabel htmlFor="email" value="Email" />

                    <TextInput
                      id="email"
                      type="email"
                      name="email"
                      value={data.email}
                      className="mt-1 block w-full"
                      autoComplete="username"
                      isFocused={true}
                      onChange={(e) => setData('email', e.target.value)}
                      required
                    />

                    {errors.email && (
                      <InputError message={errors.email} className="mt-2 text-red-500" />
                    )}
                  </div>

                  <div className="mt-4">
                    <InputLabel htmlFor="password" value="Password" />

                    <TextInput
                      id="password"
                      type="password"
                      name="password"
                      value={data.password}
                      className="mt-1 block w-full"
                      autoComplete="current-password"
                      onChange={(e) => setData('password', e.target.value)}
                      required
                    />

                    {errors.password && (
                      <InputError message={errors.password} className="mt-2 text-red-500" />
                    )}
                  </div>

                  <div className="block mt-4">
                    <div className="d-xl-flex sm:justify-between">
                      <label className="text-sm text-gray-600">
                        <Checkbox
                          name="remember"
                          checked={data.remember}
                          onChange={(e) => setData('remember', e.target.checked)}
                        />{' '}
                        Remember for 30 days
                      </label>
                      <br />
                      {canResetPassword && (
                        <Link
                          href={route('password.request')}
                          className="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                        >
                          Forgot your password?
                        </Link>
                      )}
                    </div>
                  </div>

                  <div className="flex items-center justify-end mt-4">
                    <Button
                      type="submit"
                      className="btn-blue border-none rounded-2 w-full"
                      style={{
                        paddingLeft: 45,
                        paddingRight: 45,
                        paddingTop: 12,
                        paddingBottom: 12,
                      }}
                      disabled={processing}
                    >
                      Log in
                    </Button>
                  </div>
                </form>

                <p className="text-center mt-5">
                  Don't have an account?{' '}
                  <Link href="/register">
                    <b>SignUp</b>
                  </Link>
                </p>
              </div>
            </div>
            <div className="lg:w-50 w-full d-lg-block d-none bg-right-background"></div>
          </div>
        </Modal.Body>
      </Modal>
    </div>
  );
}
