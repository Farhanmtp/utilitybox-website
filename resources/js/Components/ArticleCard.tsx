import { Link } from "@inertiajs/react";

type ArticleCardProps = {
    id: number
    title: string
    image: string
    category: any
    slug: string
    url: string
}

export function ArticleCard({ title, image, category, url, slug }: ArticleCardProps) {
    const Link1 = `/blog/${slug}`;
    return (
        <>
            <Link style={{ textDecoration: "none" }} href={url}>
                <div className="containers1 rounded-3">
                    <div className="rounded-3 p-4 d-flex align-items-end blogImg justify-content-center" style={{ backgroundImage: `linear-gradient(to bottom, #1e1e1e00,#1e1e1e45, #1e1e1e99),url(${image})` }}>
                        <div className="text-center">
                            <h6 className="text-grey mb-3 text-18 font-medium">{title}</h6>
                            <div style={{ display: "grid", justifyContent: "center" }}>
                                <div className="bg-blue p-2 rounded-2 text-grey" style={{ width: "fit-content" }}>
                                    {category?.title}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </Link>
        </>
    )
}
