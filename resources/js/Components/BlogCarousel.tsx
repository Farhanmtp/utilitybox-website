import { useEffect, useRef } from "react";
import Slider from "react-slick";
import { Col, Image } from "react-bootstrap";
import { ArticleCard } from "./ArticleCard";

function BlogCarousel({ latestPosts = [] }: { latestPosts: any }) {
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
        prevArrow: <PrevArrow />,
        nextArrow: <NextArrow />,
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
        const { onClick } = props;
        return (
            <div className="slick-arrow slick-prev" onClick={onClick}>
                <Image className="arrow-prev" src="/images/icons/arrow.svg" />
            </div>
        );
    }

    function NextArrow(props: any) {
        const { onClick } = props;
        return (
            <div className="slick-arrow slick-next" onClick={onClick}>
                <Image className="arrow-next" src="/images/icons/arrow.svg" />
            </div>
        );
    }
    console.log(latestPosts)
    return (
        <>
            <div className="bg-white pb-5 blog-slider">
                <div className="container pt-5 pb-5">
                    <p className="text-semibold text-blue mb-1">Stay Informed, Stay Inspired</p>
                    <h3 className="border-bottom-blue mb-4">
                        <b>Discover</b> our latest articles
                    </h3>
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
        </>
    );
}

export default BlogCarousel;
