let xhr=new XMLHttpRequest()
xhr.open("GET","records.php",true)
xhr.onload=()=>{
    if(xhr.status>=200 && xhr.status<300)
    {   const data=xhr.responseText
        document.body.innerHTML=data
    }
}
xhr.send()