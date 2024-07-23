import { Image, Modal } from 'react-bootstrap'
import { useState } from 'react';

export function VideoSection() {
    const [showModal, setShowModal] = useState(false);

  const handleCloseModal = () => {
    setShowModal(false);
  };

    return(
        <>
        <div className="bg-blue video-s">
            <div className="container md:flex gap-5 vid-s" style={{zIndex:2}} >
                <div className="video-section">
                    <div className="pt-5 pb-5 text-white">
                        <div>
                        <h2 className='text-3xl md:text-4xl'>Let <b>US</b> take charge<br /> of your <b>Energy Needs!</b></h2>
                        </div>
                    </div>
                </div>
                <div className="col cover text-center" style={{backgroundImage:"url(/images/videoimage.jpg)",backgroundSize:"cover",backgroundPosition:"center top"}}>
                    <div className="overlay-video md:py-auto py-12" onClick={() => setShowModal(true)}>
                    <Image src="/images/icons/play-circle.svg" width={80} className="play-circle" />
                    </div>
                </div>
            </div>
        </div>

        <Modal show={showModal} onHide={handleCloseModal} size="lg" centered>
            <Modal.Body className="p-0">
            <div className="embed-responsive embed-responsive-16by9">
                <iframe
                className="embed-responsive-item"
                src={`https://www.youtube.com/embed/NAoH15e-X5c`}
                title="YouTube Video"
                allowFullScreen
                width="100%"
                height="480px"
                style={{ border: 'none' }}
                ></iframe>
            </div>
            </Modal.Body>
        </Modal>
        </>
    )
}
