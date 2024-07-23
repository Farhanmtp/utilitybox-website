import { Link } from "@inertiajs/react";
import { Button, Image } from "react-bootstrap"

// eslint-disable-next-line react-refresh/only-export-components
export function CTA() {
    return(
        <div className="cta-btn">
        <Link href="/swift-contract-generation-system" >
            <Button className="btn-cta border-none rounded-2">
                <Image style={{marginRight:"15px"}} src="/images/icons/cta.png" width="35px" /><span>Generate Contract</span>
            </Button>
        </Link>
        </div>
    )
}
