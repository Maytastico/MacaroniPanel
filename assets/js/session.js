function getSessionID() {
    const cookies = document.cookie.split('=');
    let i = 0;
    for(const entry of cookies){
        if(entry.trim() == "PHPSESSID"){
            return cookies[i + 1];
        }
        i++;
    }
}

function getCSRFToken(){
    const CSRFContainer = document.getElementById("csrfToken");
    return CSRFContainer.value;
}