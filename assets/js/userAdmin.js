/*------------Add user event handler------------*/
const apiUrl = `${apiFolderDestination}/userAdmin.php`;

let pwInputName = "showPW";
let editPWName = "#editUserDialog input[name=\"pw\"]";

let pageBackButton = "pageBack";
let pageForwardButton = "pageForward";

let loading = new Dialog();
//If the site has loaded, the event listeners will be added to the elements
document.addEventListener("DOMContentLoaded", () => {

    loading.generateOverlay();
    loading.generateLoadingDialog();
    loading.addMessage("dataLoading", "Loading Data");


    editPWName = document.getElementById(pwInputName);
    pwInputName = document.querySelector("#editUserDialog input[name=\"pw\"]");

    pageBackButton = document.getElementById(pageBackButton);
    pageForwardButton = document.getElementById(pageForwardButton);

    pageBackButton.addEventListener("click", () => {
        Table.currentSite--;
        Table.drawSite();
    });

    pageForwardButton.addEventListener("click", () => {
        Table.currentSite++;
        Table.drawSite();
    });

    //Setting up max entries
    const maxEntriesSelect = document.querySelector('select[name="maxEntries"]');
    let before = maxEntriesSelect.value;
    Table.maxEntries = maxEntriesSelect.value;
    maxEntriesSelect.addEventListener("focusout", () => {
        if (before != maxEntriesSelect.value) {
            Table.maxEntries = maxEntriesSelect.value;
            before = maxEntriesSelect.value;
            Table.currentSite = 1;
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
            if (userAddDialogElement.classList.contains("open")) {
                Dialog.closeElement(userAddDialog);
            } else {
                Dialog.openElement(userAddDialog);
            }
        });
    }

    /*------User Add Send data----*/
    const addUserSendButton = document.querySelector(addUserSend);
    addUserSendButton.addEventListener("click", () => {
        let uid = document.querySelector('#addUserDialog input[name="uid"]').value;
        let email = document.querySelector('#addUserDialog input[name="e-mail"]').value;
        let pw = document.querySelector('#addUserDialog input[name="pw"]').value;
        const role = document.querySelector('#addUserDialog select[name="type"]').value;
        const dialogField = document.querySelector('#addUserDialog .dialog');
        let params = {
            csrf: getCSRFToken(),
            uid: uid,
            pw: pw,
            email: email,
            role: role,
        };
        console.log(params);
        let responseStatus;
        fetch(apiUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': getSessionID(),
            },
            body: JSON.stringify(params),
        }).then((response) => {
            if (response.status === 401) {
                location.reload(true);
            } else if (response.status === 403) {
                dialogField.classList.add("red");
                dialogField.classList.remove("success");
                dialogField.innerHTML = "You are not authorized to add an user";
            } else if (response.status === 200) {
                dialogField.innerHTML = "Added user successfully";
                dialogField.classList.remove("red");
                dialogField.classList.add("success");
                uid = "";
                pw = "";
                email = "";
                loading.editMessage("dataLoading", "Refreshing Data");
                Table.getDataFromApi();
            }
            responseStatus = response.status;
            return response.json();
        }).then((data) => {
            if (responseStatus === 400) {
                dialogField.classList.remove("success");
                if (data.error === "empty") {
                    dialogField.classList.add("red");
                    dialogField.innerHTML = "You have forgot to enter something into the fields";
                } else if (data.error === "admin") {
                    dialogField.classList.add("red");
                    dialogField.innerHTML = "You are not allowed to enter \"admin\" as a username!";
                } else if (data.error === "pw") {
                    dialogField.classList.add("red");
                    dialogField.innerHTML = "The password you have entered is too short";
                } else if (data.error === "usernameExists") {
                    dialogField.classList.add("red");
                    dialogField.innerHTML = "This username already exists!";
                } else if (data.error === "email") {
                    dialogField.classList.add("red");
                    dialogField.innerHTML = "This email format is not right";
                } else if (data.error === "userNotExist") {
                    dialogField.classList.add("red");
                    dialogField.innerHTML = "The user you wanted to edit does not exist!";
                } else {
                    dialogField.classList.add("red");
                    dialogField.innerHTML = "Something went wrong";
                }
            }
        }).catch((error) => {
            let err = new Dialog({
                generateOverlay: true,
                close: {action: "destroy"},
                feedbackMsg: `An error occurred while adding an user!`,
                type: ":/",
                open: true
            });
            err.addMessage("error", error);
        });
    });

    //Selectors above
    //opens the password field
    editPWName.addEventListener("click", () => {
        togglePWField();
    });
    const editUserButton = document.getElementById("editUser");
    editUserButton.addEventListener("click", () => {
        let uid = document.querySelector('#editUserDialog input[name="uid"]');
        let pw = document.querySelector('#editUserDialog input[name="pw"]');
        let role = document.querySelector('#editUserDialog select[name="type"]');
        let email = document.querySelector('#editUserDialog input[name="email"]');
        const dialogField = document.querySelector('#editUserDialog .dialog');
        const params = {
            ["csrf"]: getCSRFToken(),
            ["identifierUid"]: uid.placeholder,
            ["newUid"]: uid.value,
            ["newPW"]: pw.value,
            ["role"]: role.value,
            ["newEmail"]: email.value
        };
        let responseStatus;
        fetch(apiUrl, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': getSessionID(),
            },
            body: JSON.stringify(params),
        }).then((response) => {
            console.log(params);
            if (response.status === 401) {
                location.reload(true);
            } else if (response.status === 403) {
                dialogField.classList.add("red");
                dialogField.classList.remove("success");
                dialogField.innerHTML = "You are not authorized to edit an user";
            } else if (response.status === 200) {
                dialogField.innerHTML = "Edited user successfully";
                dialogField.classList.remove("red");
                dialogField.classList.add("success");
                ;
                if (params["newUid"] === "")
                    uid.placeholder = params["identifierUid"];
                else
                    uid.placeholder = params["newUid"];
                uid.value = "";
                pw.value = "";
                email.value = "";
                loading.editMessage("dataLoading", "Refreshing Data");
                Table.getDataFromApi();
            }
            responseStatus = response.status;
            return response.json();
        })
            .then((data) => {
                if (responseStatus === 400) {
                    dialogField.classList.remove("success");
                    if (data.error === "empty") {
                        dialogField.classList.add("red");
                        dialogField.innerHTML = "You have forgot to enter something into the fields";
                    } else if (data.error === "admin") {
                        dialogField.classList.add("red");
                        dialogField.innerHTML = "You are not allowed to enter \"admin\" as a username!";
                    } else if (data.error === "pw") {
                        dialogField.classList.add("red");
                        dialogField.innerHTML = "The password you have entered is too short";
                    } else if (data.error === "usernameExists") {
                        dialogField.classList.add("red");
                        dialogField.innerHTML = "This username already exists!";
                    } else if (data.error === "email") {
                        dialogField.classList.add("red");
                        dialogField.innerHTML = "This email format is not right";
                    } else if (data.error === "userNotExist") {
                        dialogField.classList.add("red");
                        dialogField.innerHTML = "The user you wanted to edit does not exist!";
                    } else {
                        dialogField.classList.add("red");
                        dialogField.innerHTML = "Something went wrong";
                    }
                }
            }).catch((error) => {
        });

    });


    let userTable = new Table({
        header: ["hi", "makaroni", "was"],
        drawHeader: true,
        generateTableContainer: "#content",
        pageButtons: true,
        maxEntries: 1,
        data: ["Profile", "Username", "E-Mail", "Last Login", "Role", "Actions"],
    });


});

function togglePWField() {
    if (pwInputName.classList.contains("hidden")) {
        editPWName.classList.add("hidden");
        pwInputName.classList.remove("hidden");
    } else {
        editPWName.classList.remove("hidden");
        pwInputName.classList.add("hidden");
    }
}




class Table {
    /**
     * @type array
     * Contains the data, that was requested from the userUser.php script
     */
    static data;
    /**
     * @type {number}
     * maxEntries will be set when the site was loaded.
     * Will be modified, if the user changes the select input field of the page
     */
    static maxEntries;
    /**
     * @type {number}
     * The first page will be shown at default. Will be modified by the site button on the button.
     */
    static currentSite = 1;

    static getDataFromApi() {
        loading.open();
        fetch(`${apiUrl}?csrf=${getCSRFToken()}`, {
            headers: {
                'Authorization': getSessionID()
            }
        })
            .then((response) => {
                if (response.status === 401) {
                    //location.reload(true);
                } else if (response.status === 200) {
                    loading.close();
                }
                return response.json();
            })
            .then((data) => {
                self.data = data;
                Table.drawSite();
            }).catch(() => {
                loading.close();
                let error = new Dialog({
                    type: ":/",
                    generateOverlay: true,
                    feedbackMsg: "Getting data failed",
                    open: true
                })
            }
        );
    }

    static drawSiteButtons() {
        const buttonContainer = document.querySelector("#clickableSites");
        buttonContainer.innerHTML = "";
        if (Number(this.currentSite) === 1) pageBackButton.classList.add("hidden");
        else pageBackButton.classList.remove("hidden");
        if (Number(this.currentSite) === Table.numOfPages()) pageForwardButton.classList.add("hidden");
        else pageForwardButton.classList.remove("hidden");

        for (let i = 1; i <= this.numOfPages(); i++) {
            let pageButtonContainer = document.createElement("div");
            let pageButton = document.createElement("a");
            pageButton.dataset.id = i;
            if (i === Number(Table.currentSite)) pageButton.classList.add("selected");

            pageButton.addEventListener("click", (element) => {
                this.currentSite = element.target.dataset.id;
                this.drawSite();
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
        deleteAction.addEventListener("click", () => {
            let deleteDialog = new Dialog({
                generateOverlay: true,
                feedbackMsg: `Do you really want to delete ${element.username}?`,
                generateButtonContainer: true
            });
            deleteDialog.addButton("deleteUser", {
                "additionalClasses": ["red"],
                "msg": "Delete User"
            }).addEventListener("click", () => {
                const params = {
                    ["csrf"]: getCSRFToken(),
                    ["identifierUid"]: element.username
                };
                fetch(apiUrl, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': getSessionID(),
                    },
                    body: JSON.stringify(params),
                }).then((response) => {
                    deleteDialog.destroy();
                    if (response.status === 200) {
                        loading.editMessage("dataLoading", "Refreshing Data");
                        Table.getDataFromApi();
                    } else if (response.status === 403) {
                        let nope = new Dialog({
                            generateOverlay: true,
                            close: {action: "destroy"},
                            feedbackMsg: "You do not have the permission to delete this user!",
                            type: "exclamationMark",
                            open: true
                        });
                    } else {
                        let error = new Dialog({
                            generateOverlay: true,
                            close: {action: "destroy"},
                            feedbackMsg: "Something went wrong :/",
                            type: "exclamationMark",
                            open: true
                        });
                    }
                });
            });
            deleteDialog.addButton("Cancel", {"msg": "Cancel"}).addEventListener("click", () => {
                deleteDialog.destroy();
            });
            deleteDialog.open();
        });
        const deleteIcon = document.createElement("img");
        deleteIcon.src = "../assets/icons/feather/trash-2.svg";
        buttonContainer.appendChild(deleteAction).appendChild(deleteIcon);

        const editDialog = "#editUserDialog";
        const editAction = document.createElement("a");
        editAction.classList.add("col");
        editAction.classList.add("radial");
        editAction.classList.add("editUser");
        editAction.addEventListener("click", () => {
            if (document.querySelector(editDialog).classList.contains("open")) {
                Dialog.closeElement(editDialog);
            } else {
                console.log(element);
                Dialog.openElement(editDialog);
                document.querySelector('#editUserDialog input[name="uid"]').placeholder = element.username;
                document.querySelector('#editUserDialog input[name="email"]').placeholder = element.email;
                document.querySelector('#editUserDialog select[name="type"]').value = element.role;
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
