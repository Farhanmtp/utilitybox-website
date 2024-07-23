import { Link } from "@inertiajs/react";
import { Button } from "react-bootstrap";

interface Props {
    title: string;
    link: string;
    icon: 'phone' | 'mail' | 'null';
}

export function WhiteBorderButton({ title, link, icon }: Props) {
    let iconClass = "";

    if (icon === 'phone') {
        iconClass = "fas fa-phone";
    } else if (icon === 'mail') {
        iconClass = "fas fa-envelope";
    }

    return (
        <Link href={link}>
            <Button className="btn-transparent rounded-0 md:px-[45px] px-[25px] py-[12px] w-full md:w-auto" >
                {iconClass && <i className={iconClass} style={{ paddingRight: "10px" }} />}
                {title}
            </Button>
        </Link>
    )
}
