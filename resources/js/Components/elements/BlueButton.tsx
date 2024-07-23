import { Link } from "@inertiajs/react";
import { Button } from "react-bootstrap"

interface Props {
    title:string;
    link: string;
}

export function BlueButton({title, link}: Props) {
    return(
        <Link href={link}>
            <Button className="btn-blue border-none rounded-0 w-full md:w-auto" style={{paddingLeft: 45,paddingRight: 45,paddingTop:12,paddingBottom:12}}>
            {title}
            </Button>
        </Link>
    )
}