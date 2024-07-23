import {useEffect, useRef} from "react";
import {Col, Image} from "react-bootstrap";
import Slider from "react-slick";
import "slick-carousel/slick/slick.css";
import "slick-carousel/slick/slick-theme.css";
import {ArticleCard} from "@/Components/ArticleCard";
import {BlogCard} from "@/Components/elements/BlogCard";
import {PageProps} from "@/types";
import Layout from "@/Layouts/Layout";
import {Head} from "@inertiajs/react";


export default function Blog({posts, latestPosts}: PageProps<{ posts: [], latestPosts: [] }>) {
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

    return (
        <Layout>
            <Head title="Blog"></Head>
            <div className="bg-grey pb-5 blog-slider">
                <div className="container pt-5 pb-5">
                    <p className="text-semibold text-blue mb-1">Stay Informed, Stay Inspired</p>
                    <h2 className="border-bottom-blue mb-4">
                        <b>Discover</b> our latest articles
                    </h2>

                </div>
                <Slider {...settings} ref={sliderRef} className="slide-container">
                    {latestPosts?.map((item: any) => (
                        <div key={item.id}>
                            <Col>
                                <ArticleCard {...item} />
                            </Col>
                        </div>
                    ))}
                </Slider>
            </div>

            <div className="container pt-5 pb-5">
                <h2 className="border-bottom-blue mb-4">
                    <b>Explore</b> Articles
                </h2>

                <div className="row mt-5 mb-5">
                    {posts?.map((item: any) => (
                        <div key={item.id} className="col-lg-4 mb-4">
                            <BlogCard {...item} />
                        </div>
                    ))}
                </div>
            </div>
        </Layout>
    );
}
