document.getElementById("patient").onclick = () => {
    let xhrlogin = new XMLHttpRequest();
    xhrlogin.open("GET", "access.php", true);

    xhrlogin.onload = () => {
        if (xhrlogin.status === 200) {
            const data = xhrlogin.responseText;
            console.log(data);

          
            if (data.trim() === "good") {
                window.location.href = "patients"; 
                console.log("Patient access granted, redirecting...");
            } else {
                alert("Access Denied: Confidential Data");
                console.log("Unauthorized access attempt.");
            }
        } else {
            alert("Error: Could not verify login.");
            console.log("Error occurred: " + xhrlogin.status);
        }
    };

    xhrlogin.onerror = () => {
        console.error("Request failed due to a network error.");
    };

    xhrlogin.send();
};

console.log("Login script initialized.");
