import {useParams} from 'react-router-dom';
import Slider from 'react-slick';
import {useEffect, useRef, useState} from 'react';
import {Col, Image} from 'react-bootstrap';
import {BlogCard} from '@/Components/elements/BlogCard';
import ReactHtmlParser from 'react-html-parser';
// import { FacebookShareButton, TwitterShareButton, LinkedinShareButton } from 'react-share';
import {FaLink} from 'react-icons/fa';
import {PageProps} from "@/types";
import {Head} from "@inertiajs/react";
import Layout from "@/Layouts/Layout";


export default function BlogDetails({blog, relativePosts}: PageProps<{ blog: any, relativePosts: [] }>) {
    const {slug} = useParams<{ slug: string }>();
    const blogItem = blog;//BlogContent.find((item) => item.slug === slug);

    const sliderRef = useRef<Slider>(null);

    useEffect(() => {
        const slider = sliderRef.current;
        if (slider) {
            slider.slickGoTo(0); // Go to the first slide when the component mounts
        }
    }, []);

    const settings = {
        dots: false,
        infinite: true,
        speed: 500,
        slidesToShow: 4,
        slidesToScroll: 1,
        arrows: true,
        prevArrow: <PrevArrow/>,
        nextArrow: <NextArrow/>,
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                },
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                },
            },
            {
                breakpoint: 576,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1,
                },
            },
        ],
        cssEase: "ease-in-out",
        slide: "slide",
    };

    function PrevArrow(props: any) {
        const {onClick} = props;
        return (
            <div className="slick-arrow slick-prev" onClick={onClick}>
                <Image className="arrow-prev" src="/images/icons/arrow.svg"/>
            </div>
        );
    }

    function NextArrow(props: any) {
        const {onClick} = props;
        return (
            <div className="slick-arrow slick-next" onClick={onClick}>
                <Image className="arrow-next" src="/images/icons/arrow.svg"/>
            </div>
        );
    }

    const shareUrl = window.location.href;

    const [isCopied, setIsCopied] = useState(false);

    const handleCopy = () => {
        navigator.clipboard.writeText(window.location.href)
            .then(() => {
                setIsCopied(true);
                setTimeout(() => {
                    setIsCopied(false);
                }, 5000); // Reset to "Copy Link" after 5 seconds
            })
            .catch((error) => {
                console.error('Failed to copy:', error);
            });
    };

    return (
        <Layout>
            <Head>
                <title>{blogItem?.title}</title>
                <meta name="title" content={blogItem?.meta_title}/>
                <meta name="description" content={blogItem?.meta_description}/>
            </Head>
            <div className="container pt-5 pb-5">
                <p className="text-semibold text-blue mb-1">{blogItem?.category?.title}</p>
                <h1 className="border-bottom-blue mb-4 text-2xl md:text-4xl">{blogItem?.title}</h1>
                {/* <FacebookShareButton style={{marginRight:10,fontSize:21}} url={shareUrl}>
              <FaFacebook style={{ color: '#1877F2' }} />
            </FacebookShareButton>

            <TwitterShareButton style={{marginRight:10,fontSize:21}} url={shareUrl}>
              <FaTwitter style={{ color: '#1DA1F2' }} />
            </TwitterShareButton>

            <LinkedinShareButton style={{marginRight:10,fontSize:21}} url={shareUrl}>
              <FaLinkedin style={{ color: '#0077B5' }} />
            </LinkedinShareButton> */}

                <FaLink onClick={handleCopy}/> {isCopied ? 'Copied!' : ' '}<span style={{marginLeft: "10px"}}>/</span> <i>Share</i>
            </div>

            <div className="div" style={{height: "50vh", backgroundImage: `url(${blogItem?.image})`, backgroundSize: "cover", backgroundPosition: "center"}}></div>

            <div className="container py-100">
                {ReactHtmlParser(blogItem?.content ?? '')}
            </div>

            <div className="container pt-5 pb-5 text-center w-fit">
                <h1 className="border-bottom-blue1 mb-4"><b>Related</b> Articles</h1>
            </div>

            <div className="blog-slider">
                <Slider {...settings} ref={sliderRef} className="slide-container mb-5">
                    {relativePosts?.map((item: any) => (
                        <div key={item.id}>
                            <Col>
                                <BlogCard {...item} />
                            </Col>
                        </div>
                    ))}
                </Slider>
            </div>
        </Layout>
    )
}
