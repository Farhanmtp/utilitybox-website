import { Link } from "@inertiajs/react";
import { Image } from "react-bootstrap";

interface Props {
    title: string;
    link: string;
}

export function SelectButton({title, link}: Props) {
    const buttonText = title === 'Electricity' ? '/images/icons/electricity.png' : title === 'Gas' ? '/images/icons/gas-new.png' : '';

    return(
        <>
        <Link href={link} >
            <div className=" selectbutton rounded-3">
                    <div className="mb-4" style={{ width: "fit-content" }}>
                        <Image src={buttonText} style={{ width: 80 }} />
                    </div>
                <h5>{title}</h5>
            </div>
        </Link>
        </>
    )
}
