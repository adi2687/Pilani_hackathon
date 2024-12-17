const signupform=document.querySelector(".signin")
const error=document.querySelector(".error")
signupform.onsubmit=(e)=>{
    e.preventDefault()
    let xhr = new XMLHttpRequest();
        xhr.open("POST", "signup.php", true);
    let formData=new FormData(signupform)
        xhr.onload = () => {
            if (xhr.status === 200 && xhr.readyState === XMLHttpRequest.DONE) {
                let data = xhr.responseText;
                // window.location.href=""
                error.textContent=data
                if (data=="success"){
                window.location.href="profile"
                }
            } else {
                error.textContent = "Failed to load status. Please try again.";
            }
        };

        xhr.onerror = () => {
            error.textContent = "Request failed. Please check your connection.";
        };

        xhr.send(formData);
    };
