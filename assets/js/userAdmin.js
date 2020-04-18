/*------------Add user event handler------------*/
//If the site has loaded, the event listeners will be added to the elements
document.addEventListener("DOMContentLoaded", () => {
    //Setting up max entries
    const maxEntriesSelect = document.querySelector('select[name="maxEntries"]');
    let before = maxEntriesSelect.value;
    Table.maxEntries = maxEntriesSelect.value;
    maxEntriesSelect.addEventListener("focusout", () => {
        if (before != maxEntriesSelect.value) {
            Table.maxEntries = maxEntriesSelect.value;
            before = maxEntriesSelect.value;
            Table.drawSite();
        }
    });
    //Getting Data from API
    Table.drawTableHeader();
    Table.getDataFromApi();
    //points at buttons that opens and closes the dialog
    const userAddButton = ".addUserButton";
    const userAddDialog = "#addUserDialog";
    const addUserSend = "#addUser";
    const userAddToggleElement = document.querySelectorAll(userAddButton);
    const userAddDialogElement = document.querySelector(userAddDialog);
    for (const userAddButton of userAddToggleElement) {
        userAddButton.addEventListener("click", (element) => {
            console.log(element);
            if (userAddDialogElement.classList.contains("open")) {
                closeElement(userAddDialog);
            } else {
                openElement(userAddDialog);
            }
        });
    }
    //points at buttons that opens and closes the dialog
    const userEditButtonName = ".editUser";
    const userEditElements = document.querySelectorAll(userEditButtonName);
    const userEditDialog = "#editUserDialog";
    for (const userEditButton of userEditElements) {
        userEditButton.addEventListener("click", (element) => {
            console.log(element);
            if (userEditButton.classList.contains("open")) {
                closeElement(userEditDialog);
            } else {
                openElement(userEditDialog);
            }
        });
    }

    /*------User Add Send data----*/
    const addUserSendButton = document.querySelector(addUserSend);
    addUserSendButton.addEventListener("click", () => {
        const uid = document.querySelector('.signUp input[name="uid"]').value;
        const eMail = document.querySelector('.signUp input[name="e-mail"]').value;
        const pw = document.querySelector('.signUp input[name="pw"]').value;
        const type = document.querySelector('.signUp select[name="type"]').value;
        const publicCSRF = document.querySelector('#csrfToken').value;
        const xhr = new XMLHttpRequest();
        const url = `https://localhost/MacaroniPanel/scripts/userAdmin.php`;
        const params = `PHPSESSID=${getSessionID()}&uid=${uid}&email=${eMail}&pw=${pw}&type=${type}`;
        console.log(params);
        xhr.addEventListener("readystatechange", function () {
            console.log(this.readyState);
            if (this.readyState === 4) {
                console.log("Response from API");
                console.log(this.responseText);
            }
            if (this.status === 200) {
                const dialog = document.querySelector(".signUp .dialog");
                dialog.innerHTML = "Success";
                dialog.classList.add("success");
            }
        });
        // Add the required HTTP header for form data POST requests

        xhr.open("PUT", url, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        //xhr.setRequestHeader('Content-type','application/json; charset=utf-8');
        xhr.send(params);
    });
});

class Table {
    static data;
    static maxEntries;
    static currentSite = 1;

    static getDataFromApi() {
        fetch('http://localhost/MacaroniPanel/scripts/userAdmin.php')
            .then((response) => {
                return response.json();
            })
            .then((data) => {
                self.data = data;
                Table.drawSite(this.currentSite);
                Table.drawSiteButtons();
            });
    }

    static drawSiteButtons() {
        const buttonContainer = document.querySelector("#clickableSites");
        buttonContainer.innerHTML = "";
        for (let i = 1; i <= this.numOfPages(); i++) {
            let pageButtonContainer = document.createElement("div");
            let pageButton = document.createElement("a");
            pageButton.dataset.id = i;
            if (i === Table.currentSite) Number(pageButton.classList.add("selected"));

            pageButton.addEventListener("click", (element) => {
                this.currentSite = element.target.dataset.id;
                this.drawSite(this.currentSite);
                this.drawSiteButtons();
            });
            let page = document.createTextNode(i);
            buttonContainer.appendChild(pageButtonContainer).appendChild(pageButton).appendChild(page);

        }
    }

    static drawSite() {
        let page = Table.currentSite;
        const table = document.querySelector(".tableContent tbody");
        table.innerHTML = "";
        if (page < 1) page = 1;
        if (page > this.numOfPages()) page = this.numOfPages();
        let until = page * this.maxEntries;
        if (page * this.maxEntries > self.data.length) until = self.data.length;
        for (let i = (page - 1) * this.maxEntries; i < until; i++) {
            this.drawEntry(table, self.data[i]);
        }
        this.drawSiteButtons();
    }

    static drawEntry(table, element) {
        //User data
        let userData = [element.username, element.email, element.lastLogin, element.role];
        //Draws the container for the profile picture
        const entry = table.appendChild(document.createElement("tr"));
        const profilePicColumn = document.createElement("td");
        const profilePicContainer1 = document.createElement("div");
        profilePicContainer1.classList.add("row");
        const profilePicContainer2 = document.createElement("div");
        profilePicContainer2.classList.add("profilePicture");
        profilePicContainer2.innerHTML = element.profilePicture;
        entry.appendChild(profilePicColumn).appendChild(profilePicContainer1).appendChild(profilePicContainer2);

        userData.forEach((element) => {
            const data = document.createTextNode(element);
            const column = document.createElement("td");
            entry.appendChild(column).appendChild(data);
        });

        //action buttons
        let buttonContainer = document.createElement("div");
        const buttonColumn = document.createElement("td");
        buttonContainer = entry.appendChild(buttonColumn).appendChild(buttonContainer);
        const deleteAction = document.createElement("a");
        deleteAction.classList.add("col");
        deleteAction.classList.add("radial");
        deleteAction.classList.add("red");
        deleteAction.classList.add("deleteUser");
        deleteAction.dataset.username = element.username;
        const deleteIcon = document.createElement("img");
        deleteIcon.src = "../assets/icons/feather/trash-2.svg";
        buttonContainer.appendChild(deleteAction).appendChild(deleteIcon);

        const editDialog = "#editUserDialog";
        const editAction = document.createElement("a");
        editAction.classList.add("col");
        editAction.classList.add("radial");
        editAction.classList.add("editUser");
        editAction.dataset.username = element.username;
        editAction.dataset.email = element.email;
        editAction.dataset.role = element.role;
        editAction.addEventListener("click", ()=>{
            if (document.querySelector(editDialog).classList.contains("open")) {
                closeElement(editDialog);
            } else {
                openElement(editDialog);
                document.querySelector('input[name="uid"]').value = element.username;
            }
        });
        const editIcon = document.createElement("img");
        editIcon.src = "../assets/icons/feather/edit.svg";
        buttonContainer.appendChild(editAction).appendChild(editIcon);

    }

    static numOfPages() {
        return Math.ceil(self.data.length / Table.maxEntries);
    }

    static drawTableHeader() {
        const table = document.querySelector(".tableContent thead");
        let headers = ["Profile", "Username", "E-Mail", "Last Login", "Role", "Actions"];
        const entry = document.createElement("tr");
        headers.forEach((element) => {
            const header = document.createElement("th");
            const profile = document.createTextNode(element);
            table.appendChild(entry).appendChild(header).appendChild(profile);
        })
    }
}
