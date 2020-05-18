class Table {

    static versions = 0;

    constructor(tableProperties) {
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
            this.currentSite = tableProperties["site"];
        if (tableProperties.hasOwnProperty("drawSiteButtons"))
            if (Table.drawSiteButtons() === true)
                this.drawSiteButtons();

        if(tableProperties.hasOwnProperty("pageButtons"))
            if(tableProperties["pageButtons"]===true)
                this.drawSiteButtons();
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

    /**
     * Renders button that can be clicked by the user and will modify the variables of the of the object
     */
    drawSiteButtons() {
        const arrowRight = document.createTextNode(">")
        const arrowLeft = document.createTextNode("<")
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
            if(this.currentSite > 0)
                this.currentSite--;
            this.drawSiteButtons();
        });
        const rightPageButtonContainer = document.createElement("div");
        const rightPageButton = document.createElement("a");
        rightPageButton.classList.add("rightArrow");
        rightPageButton.classList.add("small");
        rightPageButton.addEventListener("click", ()=>{
           if(this.currentSite < this.numOfPages())
                this.currentSite++;
            this.drawSiteButtons();
        });

        container.appendChild(leftPageButtonContainer).appendChild(leftPageButton).appendChild(arrowLeft);
        container.appendChild(pageButtons);
        container.appendChild(rightPageButtonContainer).appendChild(rightPageButton).appendChild(arrowRight);

        if(this.numOfPages() <= 20){
            for (let i = 1; i <= this.numOfPages(); i++) {
                let pageButtonContainer = document.createElement("div");
                let pageButton = document.createElement("a");
                pageButton.dataset.id = i;
                pageButton.classList.add("small");
                if (i === Number(this.currentSite)) pageButton.classList.add("selected");

                pageButton.addEventListener("click", (element) => {
                    this.currentSite = element.target.dataset.id;
                    this.drawSite();
                });
                let page = document.createTextNode(i);
                pageButtons.appendChild(pageButton).appendChild(page);

            }
        }
    }

    hideSiteArrowButton(direction){
        if(direction==="left"){
            document.querySelector(`table.tableContent[data-id="${this.n}"].leftArrow`).style = "display:none";
        }else if(direction === "right"){
            document.querySelector(`table.tableContent[data-id="${this.n}"].rightArrow`).style = "display:none";
        }
    }

    showSiteArrowButton(direction){
        if(direction==="left"){
            document.querySelector(`table.tableContent[data-id="${this.n}"].leftArrow`).style;
        }else if(direction === "right"){
            document.querySelector(`table.tableContent[data-id="${this.n}"].rightArrow`).style;
        }
    }


    numOfPages() {
        return Math.ceil(this.tableData.length / this.maxEntries);
    }

}