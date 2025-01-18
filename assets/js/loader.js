document.querySelector('.homepage').style.display = 'none';

// window.addEventListener("DOMContentLoaded",()=>{
    setInterval(() => {
        document.querySelector('.loader').style.display = 'none'; 
        document.querySelector('.homepage').style.display = 'block';
        // window.location.replace("homepage.php");
    }, 4000);
// })