
const loginmain1 = document.getElementById("login")
let xhr = new XMLHttpRequest()
xhr.open("GET", "is_user.php", true)
let is_user = false
xhr.onload = () => {
    if (xhr.status === 200 && xhr.readyState === XMLHttpRequest.DONE) {
        let data = xhr.responseText.trim();
        if (data === "Session active") {
            loginmain1.textContent = "Profile"; is_user = true
        }
        else {
            loginmain1.textContent = "Login"
        }
    }
}
xhr.send()


const signup = document.querySelector(".signd");
const login = document.querySelector(".logd");
const sign = document.querySelector(".loginchoice")
const loginform = document.querySelector(".forms .loginform")
const signinform = document.querySelector(".signin ")
const patient = document.querySelector(".pat")
const doc = document.querySelector(".doc")
const loginpat = document.querySelector(".login")
const docform = document.querySelector(".logindoc .login")
const cross1 = loginpat.querySelector(".cross")
const logindoctor = document.querySelector(".logindoc .login .signd")
// const logindocform=document.querySelector("")
console.log(loginmain1.textContent)

function qs(classmain) {
    return document.querySelector(classmain)
}
signup.addEventListener("click", () => {
    login.classList.toggle("bg");
    signup.classList.toggle("bgadd");
    loginform.classList.toggle("disp")
    signinform.classList.toggle("disp")
    loginbutton.style.display = "block"
    loginform.style.display = "none"
});
login.addEventListener("click", () => {
    login.classList.toggle("bg");
    signup.classList.toggle("bgadd");
    loginform.classList.toggle("disp")
    signinform.classList.toggle("disp")
    loginbutton.style.display = "none"
    loginform.style.display = "flex"
})
loginmain1.addEventListener("click", (event) => {
    console.log(is_user)
    event.preventDefault()
    if (is_user) {
        window.location.href = "profile"
        console.log("it is logged")
    }
    else {
        // sign.classList.toggle("disp")
        sign.style.display = "flex"
        console.log("hiiii")
    }
})
cross1.addEventListener("click", () => {
    loginpat.classList.toggle("disp")
    sign.style.display = "block"
})

patient.onclick = () => {
    loginpat.classList.toggle("disp")
    sign.style.display = "none"
}
const doc_log_2 = document.querySelector(".logindoc .login");

doc.onclick = () => {
    console.log("doc clickd");

    const currentDisplay = window.getComputedStyle(doc_log_2).display;

    if (currentDisplay === "none") {
        doc_log_2.style.display = "block";
        
    } else {
        doc_log_2.style.display = "none";
    }

    console.log(doc_log_2.style.display);
};


