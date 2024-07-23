import Slider from 'react-slick';
import {useEffect, useRef} from 'react';
import {Col, Image} from 'react-bootstrap';
import TestimonialItems from "../data/testimonials.json";
import {TestimonialCard} from './elements/TestimonialCard';

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
        prevArrow: <PrevArrow/>,
        nextArrow: <NextArrow/>,
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
        const {onClick} = props;
        return (
            <div className="slick-arrow slick-prev" onClick={onClick}>
                <Image className="arrow-prev" src="/images/icons/arrow.svg"/>
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

    return(
        <>
        <Slider {...settings} ref={sliderRef} className="slide-container mb-5">
          {TestimonialItems.map((item) => (
            <div key={item.id}>
              <Col>
                <TestimonialCard {...item} />
              </Col>
            </div>
          ))}
        </Slider>
        </>
    )
}
