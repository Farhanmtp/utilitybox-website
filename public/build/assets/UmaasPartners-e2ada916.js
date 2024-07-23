import{j as e,r as o}from"./app-94af9d92.js";import{S as c,C as d}from"./index-d86ee669.js";import{I as a}from"./Image-c6e21a97.js";function u({title:s,content:n,imgUrl:r}){return e.jsx(e.Fragment,{children:e.jsxs("div",{className:"service-box",style:{backgroundImage:`linear-gradient(0, #1e1e1e90, #1e1e1e90), url(${r})`,backgroundSize:"cover"},children:[e.jsx("h5",{className:"text-semibold text-grey mobile",style:{margin:"auto",marginLeft:"15px",marginBottom:"15px"},children:s}),e.jsxs("div",{style:{position:"relative",display:"flex",alignItems:"center"},children:[e.jsx("div",{className:"details-box bg-white p-3"}),e.jsx("p",{className:"details-t",children:n})]}),e.jsx("h5",{className:"text-semibold text-grey desktop",style:{margin:"auto"},children:s})]})})}const p=[{id:1,name:"Andrew Maben",title:"AC Maben Ltd.",content:"... Utility Box offered a seamless service from initial contact to signing contracts with energy suppliers which massively reduced the hours of hassle. They helped us in achieving competitive rates for our company's gas and electricity."},{id:2,name:"Anjum Mahmood",title:"Black Knight Ltd.",content:"…. They are wow! The ease in which they handled our electricity renewal from start to end meant they took away all the fuss and faff. I have just subscribed to their Utility Management Services, as it seems like a no brainer. Who wouldn't want someone managing & auditing your Utilities in today's climate"},{id:3,name:"John Doe",title:"D&A Security Ltd.",content:"It is a straightforward, professional & no pressure service."},{id:4,name:"Ira",title:"UBI Lease Management Ltd.",content:"…. One of the best decision's I have probably made this year. Utility Box have simplified our Portfolio management for Utilities and have demonstrated savings against quotations I have received directly from suppliers. They also manage our Change of Tenancies, which means they liaise with the supplier to inform them that a tenant has left, and a new tenant has come in."}];function m({name:s,content:n,title:r}){return e.jsx(e.Fragment,{children:e.jsxs("div",{className:"containers bg-white rounded-3",style:{height:"fit-content",padding:"3rem",paddingBottom:"5rem"},children:[e.jsx(a,{className:"mb-4",src:"/images/icons/comma.png",width:30}),e.jsx("p",{className:"mb-5",children:n}),e.jsxs("div",{className:"position-absolute",children:[e.jsx("b",{className:"text-copper",children:s}),e.jsx("br",{}),e.jsx("span",{children:r})]})]})})}function f(){const s=o.useRef(null);o.useEffect(()=>{const t=s.current;t&&t.slickGoTo(0)},[]);const n={dots:!1,infinite:!0,speed:500,slidesToShow:2,slidesToScroll:1,arrows:!1,prevArrow:e.jsx(r,{}),nextArrow:e.jsx(l,{}),responsive:[{breakpoint:992,settings:{slidesToShow:2,slidesToScroll:1}},{breakpoint:768,settings:{slidesToShow:2,slidesToScroll:1}},{breakpoint:576,settings:{slidesToShow:1,slidesToScroll:1}}],cssEase:"ease-in-out",slide:"slide"};function r(t){const{onClick:i}=t;return e.jsx("div",{className:"slick-arrow slick-prev",onClick:i,children:e.jsx(a,{className:"arrow-prev",src:"/images/icons/arrow.svg"})})}function l(t){const{onClick:i}=t;return e.jsx("div",{className:"slick-arrow slick-next",onClick:i,children:e.jsx(a,{className:"arrow-next",src:"/images/icons/arrow.svg"})})}return e.jsx(e.Fragment,{children:e.jsx(c,{...n,ref:s,className:"slide-container mb-5",children:p.map(t=>e.jsx("div",{children:e.jsx(d,{children:e.jsx(m,{...t})})},t.id))})})}function v(){const s=["../partners/avanti.png","../partners/british gas.png","../partners/british gas lite.png","../partners/corona energy.png","../partners/crown gas.png","../partners/d-energi.png","../partners/drax.png","../partners/dyce.png","../partners/engie.png","../partners/eon.png","../partners/eon-next.png","../partners/n-power.png","../partners/opus.png","../partners/pozitive.png","../partners/scottish.png","../partners/sefe.png","../partners/smartenergy.png","../partners/sse.png","../partners/total.png","../partners/ugp.png","../partners/valda.png","../partners/ygp.png","../partners/yu.png","../partners/OE.png","../partners/edf.png"];return e.jsx("div",{className:"bg-grey pt-5 pb-5 d-grid",style:{justifyContent:"center"},children:e.jsxs("div",{className:"container text-center",children:[e.jsxs("h2",{className:"text-center",children:[e.jsx("b",{children:"UMaaS"})," Energy Partners"]}),e.jsx("div",{className:"row mt-5 mb-5 gap-0",children:s.map((n,r)=>e.jsx("div",{className:"col-lg-2 col-md-3 col-6 text-center mb-3 mt-4",children:e.jsx(a,{src:n,width:140})},r))})]})})}export{u as S,f as T,v as U};