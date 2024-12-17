const button=document.querySelector("button")
button.onclick=()=>{
    let xhr=new XMLHttpRequest()
    xhr.open("POST","logout.php",true)
    xhr.onload=()=>{
        if (xhr.status===200){
            console.log("logged out")
        }
    }
    console.log("clicked")
}
xhr.send()