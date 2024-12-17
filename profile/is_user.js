
const loginmain1 = document.getElementById("login")
let xhr = new XMLHttpRequest()
xhr.open("GET", "../is_user.php", true)
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
const sign = document.querySelector(".login")

loginmain1.addEventListener("click", (event) => {
    console.log(is_user)
    event.preventDefault()
    if (is_user) {
        window.location.href = ""
    }
    else {
        sign.classList.toggle("disp")
    }
})


