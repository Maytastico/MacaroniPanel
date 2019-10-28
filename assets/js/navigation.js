/*Closes or opens a tab inside the navigation*/
function childAppear(index) {
    var matches = document.querySelectorAll(".item ul");
    closeAll(matches);
    openTab(matches, index);
}
/*Handels the Animation to open the tab*/
async function openTab(matches, index){
    await Sleep(500);
    matches[index].classList.remove("disappear");
    matches[index].classList.add("appear");
}
/*Closes all tabs with a animation*/
function closeAll(matches) {
    var disappearElement = document.querySelectorAll(".item ul.appear");

    if (disappearElement.length > 0) {
        disappearElement[0].classList.add("disappear");
        closeElement(matches);
    }
}
/*Closes the whole navigation*/
function toggleNav(){
    var matches = document.querySelectorAll("nav.open");
    if(matches.length > 0 && matches != null){
        matches[0].classList.add("closing");
        closeElement(matches);
        document.getElementById("nav-btn").classList.add("closed");
        document.getElementById("nav-btn").innerHTML = "&#9776;";

    }else {
        document.querySelector("nav").classList.add("open");
        document.querySelector("nav").classList.remove("closing");
        document.getElementById("nav-btn").classList.remove("closed");
        document.getElementById("nav-btn").innerHTML = "<i class=\"fas fa-times fa-2x\"></i>";

    }
}
/*Handels the close animation of any element inside the navigation*/
async function closeElement(matches) {
    await Sleep(500); // Pausiert die Fu    nktion für 3 Sekunden
    matches.forEach(function (element) {
        element.classList.remove("appear");

    });
    matches[0].classList.remove("open");
}
function Sleep(milliseconds) {
    return new Promise(resolve => setTimeout(resolve, milliseconds));
}
function toggleMenue() {
    var matches = document.querySelectorAll("#menü.open");
    if(matches.length > 0 && matches != null){
        matches[0].classList.add("closing");
        closeElement(matches);
        document.getElementById("menü").classList.add("closed");
        document.getElementById("profileMenü").innerHTML = "<i class=\"fas fa-ellipsis-v\"></i>";

    }else {
        document.getElementById("menü").classList.add("open");
        document.getElementById("menü").classList.remove("closing");

        document.getElementById("profileMenü").innerHTML = "<i class=\"fas fa-times \"></i>";
    }
}