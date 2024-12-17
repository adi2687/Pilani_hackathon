document.getElementById("patient_report").onclick = () => {
    let xhrlogin = new XMLHttpRequest();
    xhrlogin.open("GET", "login_check.php", true);

    xhrlogin.onload = () => {
        if (xhrlogin.status === 200) {
            const data = xhrlogin.responseText;
            // console.log(data);

            console.log(data)
            if (data.trim() === "good") {
                window.location.href = "reports"; 
                console.log("Patient access granted, redirecting...");
            } else {
                console.log("Unauthorized access attempt.");
                window.location.href="reports/patient"
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
