import { Button } from "react-bootstrap"

interface Props {
    title:string;
}

export function SaveAndContinue({title}: Props) {
    return(
        <Button type={'button'} className="btn-blue inline-block border-none rounded-0" style={{paddingLeft: 45,paddingRight: 45,paddingTop:12,paddingBottom:12}}>
            {title}
        </Button>
    )
}
