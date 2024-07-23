import { Image } from "react-bootstrap";
import { Nav } from 'react-bootstrap';
// import { NavLink } from 'react-router-dom';

interface Props {
    title: string;
}

export function SelectButtonSCGS({title}: Props) {
    const buttonText = title === 'Electricity' ? '/images/icons/electric.png' : title === 'Gas' ? '/images/icons/gas.png' : '';

    return(
        <>
        <Nav.Link>
            <div className="selectbuttonscgs selectbutton rounded-3">
                <div className="p-3 bg-blue rounded-3 mb-4" style={{width:"fit-content"}}>
                    <Image src={buttonText} />
                </div>
                <h5 className="text-black-bold">{title}</h5>
            </div>
        </Nav.Link>
        </>
    )
}
