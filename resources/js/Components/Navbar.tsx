import { useEffect, useRef, useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { Container, Image, Nav, NavDropdown, NavLink, Navbar as NavbarBs } from 'react-bootstrap';
import { PageProps } from "@/types";
import { useGlobalState } from '@/Layouts/elements/PopupContext';

export function Navbar() {
    const logo = usePage<PageProps>().props.app.logo;
    const user = usePage<PageProps>().props.user;
    const [isDropdownOpen, setIsDropdownOpen] = useState<boolean>(false);
    const dropdownRef = useRef<HTMLDivElement>(null);
    const [show, setShow] = useState(false);

    const showDropdown = () => {
        setShow(!show);
    }
    const hideDropdown = () => {
        setShow(false);
    }

    const handleToggleDropdown = () => {
        setIsDropdownOpen(!isDropdownOpen);
    };

    const [expanded, setExpanded] = useState(false);
    const [isSticky, setIsSticky] = useState(false);

    const toggleNavbar = () => {
        setExpanded(!expanded);
    };

    useEffect(() => {
        const handleScroll = () => {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            setIsSticky(scrollTop > 0);
        };

        window.addEventListener('scroll', handleScroll);

        return () => {
            window.removeEventListener('scroll', handleScroll);
        };
    }, []);

    const { showModal, setShowModal } = useGlobalState();

    const toggleModal = () => {
        setShowModal(true);
    };

    const isLoggedIn = usePage<PageProps>().props.loggedin;

    const pageUrl = usePage<PageProps>().url;

    useEffect(() => {
        // Check if the page URL is '/swift-contract-generation-system' and isLoggedIn is false
        if (pageUrl === route('contract', [], false) && !isLoggedIn) {
            toggleModal();
        }
    }, [pageUrl, isLoggedIn]);

    useEffect(() => {
        const handleDocumentClick = (event: MouseEvent) => {
            if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
                // Clicked outside the dropdown, close it
                setIsDropdownOpen(false);
            }
        };

        document.addEventListener('click', handleDocumentClick);

        return () => {
            document.removeEventListener('click', handleDocumentClick);
        };
    }, []);

    return (
        <>
            <NavbarBs
                sticky="top"
                className={`block ${!isSticky ? 'sticky' : ''}`}
                expand="lg"
                style={{ backgroundColor: "white" }}
            >
                <Container className="flex d-flex gap-4 py-16">
                    <div className="col-2 nav-logo">
                        <Link href="/">
                            <Image src={logo} width="166px" />
                        </Link>
                    </div>
                    <NavbarBs.Toggle
                        onClick={toggleNavbar}
                        aria-controls="basic-navbar-nav"
                        className={expanded ? 'collapsed' : ''}
                    />
                    <Nav className="me-auto navbar navbar-expand-lg gap-5 d-none d-lg-flex">

                        <NavDropdown
                            title="Open the Box"
                            id=""
                            className="border-0 navs fromLeft bg-white dropdown-bs"
                            onClick={() => setExpanded(false)}
                            show={show}
                            onMouseEnter={showDropdown}
                            onMouseLeave={hideDropdown}
                        >
                            <NavDropdown.Item
                                className=""
                                href="/open-the-box"
                                as={NavLink}
                                onClick={() => setExpanded(false)}
                            >
                                Open the Box
                            </NavDropdown.Item>
                            <NavDropdown.Item
                                className=""
                                href="/open-the-box/electricity"
                                as={NavLink}
                                onClick={() => setExpanded(false)}
                            >
                                Electricity
                            </NavDropdown.Item>
                            <NavDropdown.Item
                                className=""
                                href="/open-the-box/gas"
                                as={NavLink}
                                onClick={() => setExpanded(false)}
                            >
                                Gas
                            </NavDropdown.Item>
                        </NavDropdown>

                        <Link
                            className="navs fromLeft"
                            href="/contact"
                            onClick={() => setExpanded(false)}
                        >
                            Contact
                        </Link>
                        <Link
                            className="navs fromLeft"
                            href={route('blog')}
                            onClick={() => setExpanded(false)}
                        >
                            Blog
                        </Link>
                    </Nav>

                    {!isLoggedIn ? (
                        <div className='justify-end d-none d-lg-flex'>
                            <a
                                className="self-center hover:font-medium hover:text-black mx-5 cursor-pointer"
                                onClick={toggleModal}
                            >
                                <button className='btn-blue border-none rounded-0 px-5 py-3 text-white'>
                                    Log In
                                </button>

                            </a>
                            {/* <BlueButton title='Sign Up' link='/register' /> */}
                        </div>
                    ) : (
                        <div className="dropdown avatar-button" onClick={handleToggleDropdown} ref={dropdownRef}>
                            <div className="d-flex align-items-center">
                                <img src={user?.avatar_url ? user.avatar_url : "/images/shimer-avatar.png"} alt="Avatar" className="avatar-image" />
                                <div>{user?.name ? user?.name : ''} </div>
                            </div>
                            {isDropdownOpen && (
                                <div className={`dropdown-content ${isDropdownOpen ? 'active' : ''}`}>
                                    <Link href={route('profile.edit')}>Profile</Link>
                                    <Link method="post" href={route('logout')}>Logout</Link>
                                </div>
                            )}
                        </div>
                    )}
                </Container>

                {expanded && (
                    <NavbarBs.Collapse id="basic-navbar-nav" in={expanded} className={expanded ? 'v-auto' : 'v-none'}>
                        <Nav className="me-auto navbar navbar-expand-lg gap-5">
                            <Link
                                className="navs fromLeft"
                                href="/"
                                onClick={() => setExpanded(false)}
                            >
                                Open-the-box
                            </Link>
                            <Link
                                className="navs fromLeft"
                                href="/contact"
                                onClick={() => setExpanded(false)}
                            >
                                Contact
                            </Link>
                            <Link
                                className="navs fromLeft"
                                href={route('blog')}
                                onClick={() => setExpanded(false)}
                            >
                                Blog
                            </Link>
                            <Link
                                className="navs fromLeft"
                                href={route('blog')}
                                onClick={() => setExpanded(false)}
                            >
                                Log In
                            </Link>
                            <Link
                                className="navs fromLeft"
                                href={route('register')}
                                onClick={() => setExpanded(false)}
                            >
                                Sign Up
                            </Link>
                        </Nav>
                    </NavbarBs.Collapse>
                )}
            </NavbarBs>
        </>
    );
}

function post(arg0: string) {
    throw new Error('Function not implemented.');
}

