class Table {

    static versions = 0;

    constructor(tableProperties, TableDrawFunction) {
        this.n = Table.versions++;
        //Evaluates where the table container will be generated
        if (tableProperties.hasOwnProperty("generateTableContainer")) {
            this.generateTableContainer(tableProperties["generateTableContainer"]);
            this.containerDestination = tableProperties["generateTableContainer"];
        } else {
            this.containerDestination = document.querySelector("body");
            this.generateTableContainer("body");
        }

        //Defines what kind of information will be shown on the header of the table
        if (tableProperties.hasOwnProperty("header")) {
            this.headerData = tableProperties["header"];
            this.drawTableHeader();
        }

        //Adds entrie to the data array
        if (tableProperties.hasOwnProperty("data")) {
            this.tableData = tableProperties["data"];

        }

        if(tableProperties.hasOwnProperty("maxEntries"))
            this.maxEntries = tableProperties["maxEntries"];
        else
            this.maxEntries = 10;

        //Defines on which site the script should start
        if (tableProperties.hasOwnProperty("site"))
            this.currentSite = Number(tableProperties["site"]);
        else
            this.currentSite = 1;

        if(tableProperties.hasOwnProperty("pageButtons"))
            if(tableProperties["pageButtons"]===true){
                this.generateSiteButtonContainer();
                this.drawSiteButtons();
            }

        if(tableProperties.hasOwnProperty("maxEntryDropdown"))

        this.drawTable = TableDrawFunction;

    }

    /**
     * Draws the head of the table with the given information
     */
    drawTableHeader() {
        let thead = document.createElement("thead");
        let header = document.querySelector(this.containerDestination).appendChild(thead);
        const entry = document.createElement("tr");
        this.innerTable = document.querySelector(`table.tableContent[data-id="${this.n}"]`);
        this.headerData.forEach((element) => {
            const tableHeader = document.createElement("th");
            const profile = document.createTextNode(element);
            this.innerTable.appendChild(header).appendChild(entry).appendChild(tableHeader).appendChild(profile);
        });
    }

    /**
     * Generate html tags that specify a table inside the defined container
     */
    generateTableContainer(containerPosition) {
        const containerDestination = document.querySelector(containerPosition);
        let tableContainer = document.createElement("table");
        tableContainer.dataset.id = this.n;
        tableContainer.classList.add("tableContent");
        let tableBody = document.createElement("tbody");
        containerDestination.appendChild(tableContainer).appendChild(tableBody);
    }

    generateMaxEntriesContainer(){
        const container = document.createElement("div");
        const select = document.createElement("select");
    }

    generateSiteButtonContainer(){
        const arrowRight = document.createTextNode(">");
        const arrowLeft = document.createTextNode("<");
        const buttonContainer = document.createElement("div");
        buttonContainer.classList.add("buttonContainer");
        buttonContainer.classList.add("flex");
        const container = this.innerTable.appendChild(buttonContainer);
        const pageButtons = document.createElement("div");
        pageButtons.classList.add("pageButtons");
        const leftPageButtonContainer = document.createElement("div");
        const leftPageButton = document.createElement("a");
        leftPageButton.classList.add("leftArrow");
        leftPageButton.classList.add("small");
        leftPageButton.addEventListener("click", ()=>{
            if(this.currentSite > 1)
                this.currentSite--;
            this.clearPageButtons();
            this.drawSiteButtons();
        });
        const rightPageButtonContainer = document.createElement("div");
        const rightPageButton = document.createElement("a");
        rightPageButton.classList.add("rightArrow");
        rightPageButton.classList.add("small");
        rightPageButton.addEventListener("click", ()=>{

            if(this.currentSite < this.numOfPages())
                this.currentSite++;
            this.clearPageButtons();
            this.drawSiteButtons();
        });
        container.appendChild(leftPageButtonContainer).appendChild(leftPageButton).appendChild(arrowLeft);
        container.appendChild(pageButtons);
        container.appendChild(rightPageButtonContainer).appendChild(rightPageButton).appendChild(arrowRight);
    }
    /**
     * Renders button that can be clicked by the user and will modify the variables of the of the object
     */
    drawSiteButtons() {
        const pageButtons = document.querySelector(`table.tableContent[data-id=\"${this.n}\"] .buttonContainer .pageButtons`);
        if(this.numOfPages() <= 20){
            for (let i = 1; i <= this.numOfPages(); i++) {
                let pageButtonContainer = document.createElement("div");
                let pageButton = document.createElement("a");
                pageButton.dataset.id = i;
                pageButton.classList.add("small");
                if (i === Number(this.currentSite)) pageButton.classList.add("selected");

                pageButton.addEventListener("click", (element) => {
                    this.currentSite = element.target.dataset.id;
                    pageButtons.innerHTML = "";
                    this.drawSiteButtons();
                });
                let page = document.createTextNode(i);
                pageButtons.appendChild(pageButton).appendChild(page);

            }
        }else{
            const selectElement = document.createElement("select");
            for (let i = 1; i <= this.numOfPages(); i++) {
                let pageButton = document.createElement("option");
                if (i === Number(this.currentSite)) pageButton.selected = true;

                pageButton.addEventListener("click",() => {
                    this.currentSite = i;
                    pageButtons.innerHTML = "";
                    this.drawSiteButtons();
                });
                let page = document.createTextNode(i);
                pageButtons.appendChild(selectElement).appendChild(pageButton).appendChild(page);

            }
        }

        //Hiding left or right page button
        if(this.currentSite === 1){
            this.hideSiteArrowButton("left");
        }else if(this.currentSite === this.numOfPages()){
            this.hideSiteArrowButton("right");
        }else {
            this.showSiteArrowButton("left");
            this.showSiteArrowButton("right");
        }

        this.drawTable();
    }



    clearPageButtons(){
        const pageButtons = document.querySelector(`table.tableContent[data-id=\"${this.n}\"] .buttonContainer .pageButtons`);
        pageButtons.innerHTML = "";
    }
    hideSiteArrowButton(direction){
        if(direction == "left"){
            document.querySelector(`table.tableContent[data-id="${this.n}"] .leftArrow`).style = "opacity:0";
        }else if(direction == "right"){
            document.querySelector(`table.tableContent[data-id="${this.n}"] .rightArrow`).style = "opacity:0";
        }
    }

    showSiteArrowButton(direction){
        if(direction==="left"){
            document.querySelector(`table.tableContent[data-id="${this.n}"] .leftArrow`).style = "opacity:1";
        }else if(direction === "right"){
            document.querySelector(`table.tableContent[data-id="${this.n}"] .rightArrow`).style = "opacity:1";
        }
    }


    numOfPages() {
        return Math.ceil(this.tableData.length / this.maxEntries);
    }

}