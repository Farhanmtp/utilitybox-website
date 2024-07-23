import Slider from 'react-slick';
import { useEffect, useRef } from 'react';
import { Col, Image } from 'react-bootstrap';
import { TestimonialCard } from './elements/TestimonialCard';
//@ts-ignore
import TestimonialItems from "../Data/testimonials.json";

export function Testimonials() {
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
        slidesToShow: 2,
        slidesToScroll: 1,
        arrows: false,
        prevArrow: <PrevArrow />,
        nextArrow: <NextArrow />,
        responsive: [
            {
                breakpoint: 992,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
                },
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1,
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

    return (
        <>
            <div className="container pt-5 pb-4">
                <h3 className='text-center text-semibold mb-5'>Our Client Reviews</h3>
                <Slider {...settings} ref={sliderRef} className="slide-container mb-5">
                    {TestimonialItems.map((item: any) => (
                        <div key={item.id} className='overflow-hidden'>
                            <Col>
                                <TestimonialCard {...item} />
                            </Col>
                        </div>
                    ))}
                </Slider>
            </div>
        </>
    )
}
