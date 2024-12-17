const details=document.querySelector(".detail")

const xhrdetails = new XMLHttpRequest()
xhrdetails.open('GET', 'profile.php', true)
xhrdetails.onload=()=>{
    if(xhrdetails.status===200){
        console.log("hi")
        details.innerHTML=xhrdetails.responseText
    }
    else{
        details.textContent="Error loading please refresh the page ."
    }
}

xhrdetails.send()
