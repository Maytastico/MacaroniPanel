document.addEventListener("DOMContentLoaded", ()=>{
   //Event Listener for Navigation
   const navButton = document.querySelector("nav #button");
   const navElement = document.querySelector("nav");
    navButton.addEventListener("click", () =>{
        if(!navButton.classList.contains("open")){
            openNav();
        }else{
            closeNav();
        }
    })
});

function openNav() {
    const navButton = document.querySelector("nav #button");
    const navElement = document.querySelector("nav");
    navButton.classList.add("open");
    navElement.classList.add("open");
}
function closeNav() {
    const navButton = document.querySelector("nav #button");
    const navElement = document.querySelector("nav");
    
    navButton.classList.remove("open");
    navElement.classList.remove("open");
}