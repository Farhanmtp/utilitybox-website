import{W as p,r as f,j as e,a as j,d as h}from"./app-94af9d92.js";import{L as g,C as b}from"./Layout-bcf419ff.js";import{I as o,T as l,a as i}from"./TextInput-8b276b73.js";import{P as v}from"./PrimaryButton-fa65d64b.js";import"./Image-c6e21a97.js";function L({status:t,canResetPassword:n}){const{data:a,setData:r,post:d,processing:c,errors:m,reset:u}=p({email:"",password:"",remember:!1});f.useEffect(()=>()=>{u("password")},[]);const x=s=>{s.preventDefault(),d(route("login"))};return e.jsxs(g,{children:[e.jsx(j,{title:"Log in"}),t&&e.jsx("div",{className:"mb-4 font-medium text-sm text-green-600",children:t}),e.jsx("div",{className:"container",children:e.jsx("div",{className:"row",children:e.jsx("div",{className:"col-sm-10 offset-sm-1 col-md-6 offset-md-3 pt-5 pb-5",children:e.jsxs("form",{onSubmit:x,children:[e.jsxs("div",{children:[e.jsx(o,{htmlFor:"email",value:"Email"}),e.jsx(l,{id:"email",type:"email",name:"email",value:a.email,className:"mt-1 block w-full",autoComplete:"username",isFocused:!0,onChange:s=>r("email",s.target.value)}),e.jsx(i,{message:m.email,className:"mt-2"})]}),e.jsxs("div",{className:"mt-4",children:[e.jsx(o,{htmlFor:"password",value:"Password"}),e.jsx(l,{id:"password",type:"password",name:"password",value:a.password,className:"mt-1 block w-full",autoComplete:"current-password",onChange:s=>r("password",s.target.value)}),e.jsx(i,{message:m.password,className:"mt-2"})]}),e.jsx("div",{className:"block mt-4",children:e.jsxs("label",{className:"flex items-center",children:[e.jsx(b,{name:"remember",checked:a.remember,onChange:s=>r("remember",s.target.checked)}),e.jsx("span",{className:"ml-2 text-sm text-gray-600",children:"Remember me"})]})}),e.jsxs("div",{className:"flex items-center justify-end mt-4",children:[n&&e.jsx(h,{href:route("password.request"),className:"underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500",children:"Forgot your password?"}),e.jsx(v,{className:"ml-4",disabled:c,children:"Log in"})]})]})})})})]})}export{L as default};
