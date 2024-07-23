import { Image } from "react-bootstrap"

type TestimonialCardProps = {
    name:string
    content:string
    title :string
}

export function TestimonialCard({name, content, title}:TestimonialCardProps) {
    return(
        <>
        <div className="container bg-white rounded-3 p-4" >
            {/* <h1 className="text-copper comma" style={{fontFamily:"Mosk"}}>"</h1> */}
            <Image className="mb-4" src="/images/icons/comma.png" width={30} />
            <p className="mb-5">{content}</p>
            <div className="md:position-absolute">
                <b className="text-copper">{name}</b><br />
                <span>{title}</span>
            </div>
        </div>
        </>
    )
}
