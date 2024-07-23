import { Link } from "@inertiajs/react";
import { Image } from "react-bootstrap";

interface Props {
    title: string;
    link: string;
}

export function SelectButton({title, link}: Props) {
    const buttonText = title === 'Electricity' ? '/images/icons/electric.png' : title === 'Gas' ? '/images/icons/gas.png' : '';

    return(
        <>
        <Link href={link} >
            <div className=" selectbutton rounded-3">
                <div className="p-3 bg-blue rounded-3 mb-4" style={{width:"fit-content"}}>
                    <Image src={buttonText} />
                </div>
                <h5>{title}</h5>
            </div>
        </Link>
        </>
    )
}
