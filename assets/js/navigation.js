document.addEventListener("DOMContentLoaded", ()=>{
   //Event Listener for Navigation
   const navButton = document.querySelector("nav #button");
   const navElement = document.querySelector("nav");
    navButton.addEventListener("click", () =>{
        if(!navButton.classList.contains("open")){
            openNav();
        }else{
            closeNav();
            closeProfileMenue();
        }
    });
    const profileButton = document.querySelector("nav #userButton");
    const profileMenue = document.querySelector("nav #userMenue");
    profileButton.addEventListener("click", () =>{
        console.log("trigged");
        if(!profileMenue.classList.contains("open")){

            openProfileMenue();
        }else{
            closeProfileMenue();
        }
    });
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
function closeProfileMenue() {
    const profileMenue = document.querySelector("nav #userMenue");
    profileMenue.classList.remove("open");
}
function openProfileMenue() {
    const profileMenue = document.querySelector("nav #userMenue");
    profileMenue.classList.add("open");
}