interface Props {
    title:string
    content:string
    imgUrl:string
}

export function ServiceBox({title,content,imgUrl}:Props) {
    return(
        <>
        <div className="service-box" style={{backgroundImage:`linear-gradient(0, #1e1e1e90, #1e1e1e90), url(${imgUrl})`,backgroundSize:"cover"}}>
            <h5 className="text-semibold text-grey mobile" style={{ margin: "auto",marginLeft:"15px",marginBottom:"15px" }}>{title}</h5>
            <div style={{position:"relative", display: "flex", alignItems: "center" }}>
                <div className="details-box bg-white p-3"></div>
                <p className="details-t">{content}</p>
            </div>
            <h5 className="text-semibold text-grey desktop" style={{ margin: "auto" }}>{title}</h5>
        </div>
        </>
    )
}