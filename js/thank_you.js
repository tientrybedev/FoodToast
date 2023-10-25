// Show the loading overlay with a delay
setTimeout(function () {
    document.getElementById("overlay").style.opacity = 1;
    document.getElementById("overlay").style.pointerEvents = "auto";
}, 1000);

window.addEventListener("DOMContentLoaded", () => {
    const spinner = document.querySelector(".overlay");
    const thankContent = document.getElementById("thankContent");
    
    // Simulate the loading time
    setTimeout(() => {
        spinner.style.animation = "none";
        setTimeout(() => {
            spinner.style.display = "none"; 
            thankContent.style.opacity = "1"; 
            thankContent.style.pointerEvents = "auto"; 
        }, 750);
    }, 3600); 
});


const thankYouTitle = document.getElementById("thankYouTitle");
setTimeout(() => {
    thankYouTitle.classList.add("show-title");
}, 4400);
