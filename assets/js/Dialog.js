
class Dialog{

    static generated = 0;

    constructor(generateOverlay, close) {
        Dialog.generated++;
        this.number = Dialog.generated;
        if(generateOverlay===true){
            this.generateOverlay();
        }
        if(close===true){

        }
    }
    generateCloseButton(){
        let closeButton = document.createElement("a");
        closeButton.classList.add("icon");
        closeButton.innerHTML = "x";
        closeButton.style = "position:absolute;top:0;left:0";
        this.dialog.appendChild(closeButton);
    }
    generateOverlay(){
        const dialogOverlay = document.createElement("section");
        dialogOverlay.classList.add("overlay");
        dialogOverlay.dataset.id = this.number;
        const dialogContainer = document.createElement("div");
        dialogContainer.classList.add("flex");
        dialogContainer.classList.add("center");
        dialogContainer.classList.add("column");
        dialogContainer.style = "height:100%";
        this.dialog = document.querySelector("body").appendChild(dialogOverlay).appendChild(dialogContainer);
    }

    generateLoadingDialog(){
        const loading = document.createElement("section");
        loading.dataset.name = "loading";
        loading.classList.add("loading");
        loading.classList.add("center");
        this.dialog.appendChild(loading);
    }

    deleteLoadingDialog(){
        const loadingDialog = document.querySelector(`.overlay[data-id="${this.number}"] section[data-name="loading"]`).remove();
    }

    generateExclamationMarkDialog(){
        const loading = document.createElement("section");
        loading.dataset.name = "exclamationMark";
        loading.style = "font-size: 5em;";
        loading.classList.add("center");
        const exclamationMark = document.createTextNode("!");
        this.dialog.appendChild(loading).appendChild(exclamationMark);
    }

    generateButtonContainer(){
        const buttonContainer = document.createElement("section");
        buttonContainer.dataset.name = "buttons";
        buttonContainer.classList.add("center");
        this.dialog.appendChild(buttonContainer);
    }

    addButton(name, additionalClass ,msg){
        const buttonContainer = document.querySelector(`.overlay[data-id="${this.number}"] section[data-name="buttons"]`);
        if(buttonContainer !== null){
            const button = document.createElement("a");
            button.innerHTML = msg;
            if(additionalClass !== ""){
                button.classList.add(additionalClass);
            }
            return buttonContainer.appendChild(button);
        }
    }

    addMessage(name ,msg){
        const messageContainer = document.createElement("section");
        messageContainer.dataset.name = name;
        messageContainer.classList.add("flex");
        messageContainer.classList.add("center");
        const message = document.createTextNode(msg);
        this.dialog.appendChild(messageContainer).appendChild(message);
    }

    editMessage(name, msg){
        const messageContainer = document.querySelector(`.overlay[data-id="${this.number}"] section[data-name="${name}"] `);
        messageContainer.innerHTML = msg;
    }

    destroy(){
        document.querySelector(`.overlay[data-id="${this.number}"]`).remove();
    }
    open(){
        document.querySelector(`.overlay[data-id="${this.number}"]`).classList.add("open");
    }
    close(){
        document.querySelector(`.overlay[data-id="${this.number}"]`).classList.remove("open");
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

