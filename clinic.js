       const clinic = document.getElementById("clinic")
        const clinic_form = document.querySelector(".clinic-login-form")
        const cross_cli = clinic_form.querySelector(".cross")
        clinic.onclick = () => {
            clinic_form.style.display = "block"
            console.log("hii")
        }
        cross_cli.onclick = () => { clinic_form.style.display = "none" }
        const clinic_log = clinic_form.querySelector(".clinic-login-form .cho div")
        const clinic_login = document.querySelector(".clinic_login")
        const clinic_signup = document.querySelector(".clinic_signup")
        const clinic_sign = document.getElementById("sign")
        const clinic_login_but = document.getElementById("log")
        let color = window.getComputedStyle(clinic_login_but).backgroundColor

        clinic_login_but.onclick = () => {
            console.log("hey")
            clinic_login.style.display = "block"
            clinic_signup.style.display = "none"
            console.log(color)
            clinic_sign.style.backgroundColor = "transparent"
            clinic_login_but.style.backgroundColor = color
        }
        clinic_sign.onclick = () => {
            clinic_signup.style.display = "block"
            clinic_login.style.display = "none"
            clinic_sign.style.backgroundColor = color
            clinic_login_but.style.backgroundColor = "transparent"
        }
   
        document.getElementById('clinicLoginForm').addEventListener('submit', function (event) {
            event.preventDefault();

            const xhr = new XMLHttpRequest();
            const form = event.target;

            const formData = new FormData(form);
            const status = document.querySelector(" .error_cli")
            xhr.open('POST', 'clinic_login.php', true);

            xhr.onload = function () {
                if (xhr.status === 200) {

                    console.log(xhr.responseText);

                    status.textContent = xhr.responseText
                    if (xhr.responseText === "Good") {
                        window.location.href = "profile"

                    }
                    else {
                        status.style.color="red"
                        status.textContent = "The details are not correct"
                        status.style.marginLeft="20%"
                        
                    }
                } else {
                    console.error("Login failed. Status: " + xhr.status);
                }
            };

            xhr.send(formData);
        });

        document.querySelector(".clinic_signup").addEventListener('submit', function (event) {
            event.preventDefault();

            const xhr = new XMLHttpRequest();
            const form = event.target;

            const formData = new FormData(form);
            const status = document.querySelector(" .error_cli_sign")
            xhr.open('POST', 'clinic_signup.php', true);

            xhr.onload = function () {
                if (xhr.status === 200) {

                    console.log(xhr.responseText);

                    status.textContent = xhr.responseText
                    if (xhr.responseText === "Good") {
                        window.location.href = "profile"

                    }
                    else {
                        status.style.color="red"
                        status.textContent = "The details are not correct. They are already taken"
                        status.style.marginLeft="0%"
                        status.style.display="flex"
                        status.style.justifyContent="center"
                        status.style.textAlign="center"
                    }
                } else {
                    console.error("Login failed. Status: " + xhr.status);
                }
            };

            xhr.send(formData);
        });

        