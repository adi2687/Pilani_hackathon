const loginformmain = document.querySelector(".forms .loginform");
const errorlogin = document.querySelector(".loginform .error");
const logindisplay=document.getElementById("login")
const loginmain=document.querySelector(".login")
function submitLogin(e) {
    e.preventDefault();

    let xhr = new XMLHttpRequest();
    xhr.open("POST", "login.php", true);
    let formData = new FormData(loginformmain);

    xhr.onload = () => {
        if (xhr.status === 200 && xhr.readyState === XMLHttpRequest.DONE) {
            let data = xhr.responseText.trim();
            
            if (data === "success") {
                errorlogin.textContent = "Login successful! Redirecting.....";
                logindisplay.textContent="Profile"
                errorlogin.style.color="blue"
                loginmain.style.display="none"
            }  else {
                errorlogin.textContent = data;
            }
        } else {
            errorlogin.textContent = "Failed to load status. Please try again.";
        }
    };

    xhr.onerror = () => {
        errorlogin.textContent = "Request failed. Please check your connection.";
    };

    xhr.send(formData);
}

loginformmain.onsubmit = (event) => {
    submitLogin(event);
};
