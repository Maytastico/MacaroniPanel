function toggleDialog(id){
    let query = "#" + id + ".open";
    let results = document.querySelectorAll(query);
    if(!(results.length > 0)){
        let element = document.getElementById(id);
        element.classList.add("open");
    }else{
        let element = document.getElementById(id);
        element.classList.remove("open")
    }
}
function deleteDialog(formName) {
    x = document.getElementById("dialogBox");

    x.classList.add("show");

    x.innerHTML = "<div class=''><h5>Do you really want to delete this entry?</h5>" +
                    "<button onclick='submitDialog(\""+formName+"\")'>Yes</button>"+
                    "<button class='cancel'  onclick='closeDialog()'>Cancel</button></div>";

}
function closeDialog(){
    let x = document.getElementById("dialogBox");

    x.classList.remove("show");
}
function submitDialog(formName){
    document.getElementById(formName).submit();
}
function deleteData(formName) {
    document.getElementById(formName).submit();
}