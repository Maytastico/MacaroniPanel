//For Navigation
const profileMenuPath = "nav #userMenue";

document.addEventListener("DOMContentLoaded", ()=>{
    //For Admin Page
    let applyAdminSelect = document.querySelector("body#admin select[name='maxEntries']");
    let adminSelectValue = applyAdminSelect.value;
    applyAdminSelect.addEventListener("click", () =>{
        if(adminSelectValue!=getMaxEntriesValue()){
            triggerLoadingElement();
            document.querySelector("body#admin #content form").submit();
        }
    });
    //Event Listener for Navigation
   const navButton = document.querySelector("nav #button");
   const navElement = document.querySelector("nav");
    navButton.addEventListener("click", () =>{
        if(!navButton.classList.contains("open")){
            openNav();
        }else{
            closeNav();
            closeElement(profileMenuPath)
        }
    });
    const profileButton = document.querySelector("nav #userButton");
    const profileMenue = document.querySelector("nav #userMenue");
    profileButton.addEventListener("click", () =>{
        if(!profileMenue.classList.contains("open")){
            //opens the profile menu
            openElement(profileMenuPath);
        }else{
            //closes the profile menu
            closeElement(profileMenuPath);
        }
    });


});


function triggerLoadingElement(){
    let loading = document.querySelector(".loading");
    loading.classList.remove("hidden");
}

function getMaxEntriesValue(){
    const applyAdminSelect = document.querySelector("body#admin select[name='maxEntries']");
    return applyAdminSelect.value;
}
/**
 * Opens the navigation. Adds the open class to the nav button and nav element.
 */
function openNav() {
    const navButton = document.querySelector("nav #button");
    const navElement = document.querySelector("nav");
    navButton.classList.add("open");
    navElement.classList.add("open");
}

/**
 * Closes the navigation. It removes the open class from the nav button and nav element.
 */
function closeNav() {
    const navButton = document.querySelector("nav #button");
    const navElement = document.querySelector("nav");
    
    navButton.classList.remove("open");
    navElement.classList.remove("open");
}