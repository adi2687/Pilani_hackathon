const button = document.querySelector(".logout button");

button.onclick = () => {
    let xhr = new XMLHttpRequest();
    xhr.open("POST", "../logout.php", true);
    xhr.onload = () => {
        if (xhr.status === 200) {
            console.log("logged out");
            window.location.href="../"
        }
    };
    console.log("clicked");
    xhr.send(); // This should be inside the onclick handler
};

console.log("hey");
