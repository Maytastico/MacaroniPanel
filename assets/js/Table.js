class Table {

    static generated = 0;

    constructor(tableProperties) {
        this.n = Table.generated++;
        if (tableProperties.hasOwnProperty("generateTableContainer")) {
            this.generateTableContainer(tableProperties["generateTableContainer"]);
            this.containerDestination = document.querySelector(tableProperties["generateTableContainer"]);
            this.innerTable = document.querySelector(`table.tableContent[data-id=${this.n}]`);

        } else {
            this.containerDestination = document.querySelector("body");
            this.generateTableContainer("body");
        }
        if (tableProperties.hasOwnProperty("header")){
            this.headerData = tableProperties["header"];
            this.drawTableHeader();
        }
        if (tableProperties.hasOwnProperty("data")) {
            this.tableData = tableProperties["data"];

        }

        if (tableProperties.hasOwnProperty("site"))
            this.currentSite = tableProperties["site"];
        if (tableProperties.hasOwnProperty("drawSiteButtons"))
            if (Table.drawSiteButtons() === true)
                this.drawSiteButtons();
    }

    /**
     * Draws the head of the table with the given information
     */
    drawTableHeader() {
        let thead = document.createElement("thead");
        let header = this.containerDestination.appendChild(thead);
        const entry = document.createElement("tr");
        this.headerData.forEach((element)=>{
            const tableHeader = document.createElement("th");
            const profile = document.createTextNode(element);
            this.innerTable.appendChild(entry).appendChild(tableHeader).appendChild(profile);
        });
    }

    /**
     * Generate html tags that specify a table inside the defined container
     */
    generateTableContainer() {
        let tableContainer = document.createElement("table");
        tableContainer.dataset.id = this.n;
        tableContainer.classList.add("tableContent");
        let tableBody = document.createElement("tbody");
        this.containerDestination.appendChild(tableContainer).appendChild(tableBody);
    }

    /**
     * Renders button that can be clicked by the user and will modify the variables of the of the object
     */
    drawSiteButtons() {

=======
    static versions = 0;

    constructor(tableInformation = {}) {
        Table.versions++;
        this.version = Table.versions;
        /*
         * Initializes and declares all attributes of the object.
         */

        //contains the Strings that will be shown inside the header
        if (tableInformation.hasOwnProperty("tableHeader"))
            this.tableHeader = tableInformation["tableHeader"];

        //Contains a list of information that can be shown inside the table
        if (tableInformation.hasOwnProperty("tableData"))
            this.tableData = tableInformation["tableData"];

        //If a developer wishes to specify a default max entries value,
        //the developer should use this key during declaration.
        if (tableInformation.hasOwnProperty("maxEntries"))
            this.maxEntries = tableInformation["maxEntries"];
        else
            this.maxEntries = 10;

        //If a developer wishes to specify a default site that should be displayed,
        //the developer should use this key during declaration.
        if(tableInformation.hasOwnProperty("site"))
            this.currentSite = tableInformation["site"];
        else
            this.currentSite = 1;

        if(tableInformation.hasOwnProperty("container"))
            this.containerRef = tableInformation["container"];
        else
            this.containerRef = "body";
    }

    generateTableContainer(containerRef){
        const genRef = document.querySelector(containerRef);
        let tableTag = document.createElement("table");
        tableTag.classList.add("tableContent");
        tableTag.dataset.id = Table.versions;

        const tableHeadTag = document.createElement("thead");
        const tableBodyTag = document.createElement("tbody");
        tableTag = genRef.appendChild(tableTag);
        tableTag.appendChild(tableHeadTag);
        tableTag.appendChild(tableBodyTag);
    }

    numOfPages() {
        return Math.ceil(this.tableData.length / this.maxEntries);
    }

    static drawTableHeader() {
        const table = document.querySelector(`.tableContent[data-id="${this.version}"] thead`);
        const entry = document.createElement("tr");
        headers.forEach((element) => {
            const header = document.createElement("th");
            const profile = document.createTextNode(element);
            table.appendChild(entry).appendChild(header).appendChild(profile);
        })
>>>>>>> Table class added
    }

}