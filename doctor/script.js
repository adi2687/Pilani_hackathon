const detail = document.querySelector(".detail")
let xhr = new XMLHttpRequest()
xhr.open("GET", "doctor.php", true)
xhr.onload = () => {
    if (xhr.status == 200) {
        let data = (xhr.responseText)
        detail.innerHTML = data
    }
}
xhr.send()
