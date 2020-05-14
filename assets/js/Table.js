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

    }

}