class Dialog {

    /**
     * @type {number}
     * Contains the number of instances that were created
     * It is to evaluate a unique name for an HTML Element
     */
    static generated = 0;

    /**
     * @param settings
     * settings: Contains certain attributes that are necessary to create an overlay.
     *  Attributes:
     *       {generateOverlay:true}: Generates a container where all elements will be generated
     *       close: Adds an close button to the overlay.
     *       {type:"loading"}: Adds an rotating circle to the overlay
     *       {type:"exclamationMark"}: Adds an "!" to the overlay
     *       feedbackMsg: Adds an Message to the overlay.
     */
    constructor(settings = {}) {
        Dialog.generated++;
        this.number = Dialog.generated;

        if (settings.hasOwnProperty("generateOverlay"))
            if (settings["generateOverlay"] === true)
                this.generateOverlay();
        if (settings.hasOwnProperty("close"))
            this.generateCloseButton(settings["close"]);
        if (settings.hasOwnProperty("type")) {
            switch (settings["type"]) {
                case "loading":
                    this.generateLoadingDialog();
                    break;
                case "exclamationMark":
                    this.generateExclamationMarkDialog();
                    break;
                default:
                    this.generateTextDialog(settings["type"]);
                    break;
            }
        }
        if (settings.hasOwnProperty("feedbackMsg"))
            this.addMessage("feedback", settings["feedbackMsg"]);
        if (settings.hasOwnProperty("generateButtonContainer"))
            if (settings["generateButtonContainer"] === true)
                this.generateButtonContainer()
        if (settings.hasOwnProperty("open"))
            if (settings["open"] === true)
                this.open();
    }


    /**
     * @param properties
     * properties: Contains certain attributes, so you customize your close button
     *  Attributes and their meaning:
     *  style: Can contain a certain position (topLeft, topRight, bottomLeft, bottomRight) or an style you want define by yourself.
     *  additionalClasses: Con contain an array of css class name
     *  destroy: It destroys the overlay element and the instance of the Dialog class.
     *  customIcon: Can contain a certain path to an image
     * Adds an close button to the dialog. Will be executed inside the constructor.
     */
    generateCloseButton(properties) {
        const closeButtonContainer = document.createElement("div");
        const closeButton = document.createElement("a");
        closeButton.classList.add("flex");
        closeButton.classList.add("center");
        let icon = document.createTextNode("X");
        if (properties.hasOwnProperty("style")) {
            if (properties["style"] === "topLeft")
                closeButton.style = "position:absolute;top:0;left:0;width:1em;height:1em;";
            else if (properties["style"] === "topRight")
                closeButton.style = "position:absolute;top:0;right:0;width:1em;height:1em;";
            else if (properties["style"] === "bottomLeft")
                closeButton.style = "position:absolute;bottom:0;left:0;width:1em;height:1em;";
            else if (properties["style"] === "bottomRight")
                closeButton.style = "position:absolute;bottom:0;right:0;width:1em;height:1em;";
            else
                closeButton.style = properties["style"];
        } else {
            closeButton.style = "position:absolute;top:0;left:0;width:1em;height:1em;";
        }
        if (properties.hasOwnProperty("additionalClasses")) {
            for (const style of properties["additionalClasses"])
                closeButton.classList.add(style);
        } else {
            closeButton.classList.add("radial");
            closeButton.classList.add("red");
        }
        if (properties.hasOwnProperty("customIcon")) {
            icon = document.createElement("img");
            icon.src = properties["customIcon"];
        }
        if (properties.hasOwnProperty("action")) {
            if (properties["action"] === "destroy") {
                closeButton.addEventListener("click", () => {
                    this.destroy();
                });
                this.dialog.appendChild(closeButtonContainer).appendChild(closeButton).appendChild(icon);
            }
        } else {
            closeButton.addEventListener("click", () => {
                this.close();
            });
            this.dialog.appendChild(closeButtonContainer).appendChild(closeButton).appendChild(icon);
        }

    }

    /**
     * Generates the HTML Element where everything will be put in.
     */
    generateOverlay() {
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

    /**
     * Creates a rotating circle.
     * You can imply that something is loading
     */
    generateLoadingDialog() {
        const loading = document.createElement("section");
        loading.dataset.name = "loading";
        loading.classList.add("loading");
        loading.classList.add("center");
        this.dialog.appendChild(loading);
    }

    /**
     * Adds an element to the overlay that contains a "!" to give feedback to the user.
     * You can imply that something went wrong.
     */
    generateExclamationMarkDialog() {
        const loading = document.createElement("section");
        loading.dataset.name = "exclamationMark";
        loading.style = "font-size: 5em;";
        loading.classList.add("center");
        const exclamationMark = document.createTextNode("!");
        this.dialog.appendChild(loading).appendChild(exclamationMark);
    }

    /**
     * Adds an element to the overlay that contains a text to give feedback to the user.
     * You can imply that something went wrong.
     */
    generateTextDialog(text) {
        const loading = document.createElement("section");
        loading.dataset.name = "textDialog";
        loading.style = "font-size: 5em;";
        loading.classList.add("center");
        const exclamationMark = document.createTextNode(text);
        this.dialog.appendChild(loading).appendChild(exclamationMark);
    }

    /**
     * Generate a container for buttons.
     * Should be executed before you add buttons.
     */
    generateButtonContainer() {
        const buttonContainer = document.createElement("section");
        buttonContainer.dataset.name = "buttons";
        buttonContainer.classList.add("center");
        this.dialog.appendChild(buttonContainer);
    }

    /**
     * @param name
     * @param properties
     * @returns {HTMLAnchorElement}
     * name: The name of the button so you can identify it later on.
     * properties: It is an object that should contain at least an message.
     *  Example: {msg:"Cancel"}
     *  If you want modify the style of the button, you can add several classes.
     *  Example: {additionalClasses:["icon", "radial"]}
     * It returns a reference to the created button, so you can add an event listener to the button.
     * Adds buttons to an existing container. So it has to be generated first.
     * It is useful, if you want to add some interactivity to your dialog.
     */
    addButton(name, properties) {
        const buttonContainer = document.querySelector(`.overlay[data-id="${this.number}"] section[data-name="buttons"]`);
        if (buttonContainer !== null) {
            const button = document.createElement("a");
            if (properties.hasOwnProperty("msg"))
                button.innerHTML = properties["msg"];
            if (properties.hasOwnProperty("additionalClasses")) {
                for (const style of properties["additionalClasses"])
                    button.classList.add(style);
            }
            return buttonContainer.appendChild(button);
        }
    }

    /**
     * @param name
     * @param msg
     * name: The name you want to call the object. Can come to errors, if you have duplicate names.
     * msg: The message you want to give to the user.
     * Creates a new message and adds it to the overlay element.
     * It is useful, if you want to give feedback to the user.
     */
    addMessage(name, msg) {
        const messageContainer = document.createElement("section");
        messageContainer.dataset.name = name;
        messageContainer.classList.add("flex");
        messageContainer.classList.add("center");
        const message = document.createTextNode(msg);
        this.dialog.appendChild(messageContainer).appendChild(message);
    }

    /**
     * @param name
     * @param msg
     * name: The name of the message. It is the name you have given to it when you created it.
     * msg: Your wished message to the user.
     * Modifies the message of an existing message element
     * It is useful, if you want to reuse an dialog
     */
    editMessage(name, msg) {
        const messageContainer = document.querySelector(`.overlay[data-id="${this.number}"] section[data-name="${name}"] `);
        messageContainer.innerHTML = msg;
    }

    /**
     * Deletes the HTML Element and the instance of the Dialog class as well.
     * It will be useful, if you want to open an dialog and then delete it entirely after
     * its job is done.
     */
    destroy() {
        document.querySelector(`.overlay[data-id="${this.number}"]`).remove();
        delete this;
    }

    /**
     * Modifies the class list of the overlay element and adds an "open" class to it.
     * This css class in combination with the "overlay" class contains all styles.
     */
    open() {
        document.querySelector(`.overlay[data-id="${this.number}"]`).classList.add("open");
    }

    /**
     * Modifies the class list of the overlay element and removes the "open" class form the class list.
     */
    close() {
        document.querySelector(`.overlay[data-id="${this.number}"]`).classList.remove("open");
    }

    /**
     * Opens a dialog. It adds a open class to the Element
     * @param elementName
     * elementName: Contains the CSS path of the element the should be opened.
     */
    static openElement(elementName) {
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

