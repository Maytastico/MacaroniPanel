/**
 * Opens a dialog. It adds a open class to the Element
 * @param elementName
 * elementName: Contains the CSS path of the element the should be opened.
 */
function openElement(elementName){
    var selectedElement = document.querySelector(elementName);
    selectedElement.classList.add("open");
}

/**
 * Closes a dialog. It removes the open class from the element
 * @param elementName
 * elementName: Contains the CSS path of the element the should be opened.
 */
function closeElement(elementName) {
    var selectedElement = document.querySelector(elementName);
    selectedElement.classList.remove("open");
}