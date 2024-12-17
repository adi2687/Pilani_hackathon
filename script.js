document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.add_doc form');
    const error=document.querySelector(".add_doc .error")
    form.addEventListener('submit', function(e) {
        e.preventDefault();  // Prevent the default form submission

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'add_doctors.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

        xhr.onload = function() {
            if (xhr.status >= 200 && xhr.status < 300) {
                console.log('Response:', xhr.responseText);
                error.textContent=xhr.responseText
            } else {
                error.textContent=xhr.textContent
            }
        };

        const formData = new FormData(form);
        const queryParams = new URLSearchParams();
        for (const pair of formData) {
            queryParams.append(pair[0], pair[1]);
        }
        xhr.send(queryParams);
    });
});

const register=document.querySelector(".register-clinic")
const add_doc=document.querySelector(".add_doc")
register.addEventListener("click",function(){
add_doc.style.display="block"
console.log("clicked")
})
const cross=document.querySelector(".add_doc .cros")
cross.addEventListener("click",()=>{
    add_doc.style.display="none"
})
const cross_doc=document.querySelector(".logindoc .login .cross")
const doc_log=document.querySelector(".logindoc ")
cross_doc.onclick=()=>{
    // doc_log.classList.toggle("disp");
    console.log("lickd")

    if (doc_log.style.display="flex"){
        doc_log.style.display="none"
    }
    else{
        doc_log.style.display="none"
    }
    console.log(doc_log.style.display)
}