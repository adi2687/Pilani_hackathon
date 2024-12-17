const form = document.querySelector('.logindoc .login .forms .signin');

form.onsubmit = function(event) {
    event.preventDefault(); // Prevent the form from submitting the traditional way

    const refId = form.querySelector('input[name="ref_id"]').value;
    const password = form.querySelector('input[name="password"]').value;

    const xhr = new XMLHttpRequest();

    xhr.open("POST", "logindoctor.php", true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            const response = xhr.responseText.trim();
            console.log( response);
            if (response==="success") {
                window.location.href = "profile"; // Redirect on success
                console.log("hiii")
            } else {
                document.querySelector('.logindoc .login .forms .error').textContent = "The credentials are wrong . Try again" ;
            }
        } else {
            alert("Something went wrong. Please try again.");
        }
    };
    const formdata=new FormData(form)
    xhr.send(formdata);
};
