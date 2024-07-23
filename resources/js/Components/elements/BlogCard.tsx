import {Link} from "@inertiajs/react";


type ArticleCardProps = {
    id: number;
    title: string;
    image: string;
    category: any;
    url: string;
};

export function BlogCard({title, image, url}: ArticleCardProps) {
    return (
        <>
            <Link style={{textDecoration: "none"}} href={url}>
                <div className="containers rounded-3">
                    <div className="rounded-3 p-4 d-flex align-items-end blogImg" style={{backgroundImage: `linear-gradient(to bottom, #1e1e1e00, #1e1e1e),url(${image})`}}>
                        <h5 className="text-grey text-18 font-medium">{title}</h5>
                    </div>
                </div>
            </Link>
        </>
    );
}
