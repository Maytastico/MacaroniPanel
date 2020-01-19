document.addEventListener("DOMContentLoaded", ()=>{
    //Event Listener for Navigation
    const settingsButtons = document.querySelectorAll("button.userSettings");
    const settingsElement = document.querySelector("div#editUser");
    for (const settingsButton of settingsButtons){
        settingsButton.addEventListener("click", () =>{
            if(!settingsElement.classList.contains("open")){
                openSettings();
                closeNav();
                closeProfileMenue();
            }else{
                closeSettings();
                closePwBox();
            }
        });
    }
    const changePWButtons = document.querySelectorAll(".changePw");
    const changePWElement = document.querySelector("div#pwBox");
    for (const changePWButton of changePWButtons){
        changePWButton.addEventListener("click", () =>{
            if(!changePWElement.classList.contains("open")){
                openPwBox();
            }else{
                closePwBox();
            }
        });
    }

});

function openSettings() {
    const settingsElement = document.querySelector("div#editUser");
    settingsElement.classList.add("open");
}
function closeSettings() {
    const settingsElement = document.querySelector("div#editUser");
    settingsElement.classList.remove("open");
}

function openPwBox() {
    const changePWElement = document.querySelector("div#pwBox");
    changePWElement.classList.add("open");
}

function closePwBox() {
    const changePWElement = document.querySelector("div#pwBox");
    changePWElement.classList.remove("open");
}
