
class Dialog{


    constructor() {
        const dialogOverlay = document.createElement("section");
        dialogOverlay.classList.add("overlay");
        const dialogContainer = document.createElement("div");
        dialogContainer.classList.add("flex");
        dialogContainer.classList.add("center");
        dialogContainer.classList.add("column");
        dialogContainer.style = "height:100%";
        this.dialog = document.querySelector("body").appendChild(dialogOverlay).appendChild(dialogContainer);
    }
    loadingDialog(){
        const loading = document.createElement("div");
        loading.classList.add("loading");
        loading.classList.add("center");
        this.dialog.appendChild(loading);
    }
    message(msg){
        const messageContainer = document.createElement("div");
        messageContainer.classList.add("flex");
        messageContainer.classList.add("center");
        const message = document.createTextNode(msg);
        this.dialog.appendChild(messageContainer).appendChild(message);
    }
    open(){
        document.getElementsByClassName("overlay")[0].classList.add("open");
    }
    close(){
        document.getElementsByClassName("overlay")[0].classList.remove("open");
    }
    /**
     * Opens a dialog. It adds a open class to the Element
     * @param elementName
     * elementName: Contains the CSS path of the element the should be opened.
     */
    static openElement(elementName){
        var selectedElement = document.querySelector(elementName);
        selectedElement.classList.add("open");
    }

    /**
     * Closes a dialog. It removes the open class from the element
     * @param elementName
     * elementName: Contains the CSS path of the element the should be opened.
     */
    static closeElement(elementName) {
        var selectedElement = document.querySelector(elementName);
        selectedElement.classList.remove("open");
    }
}

