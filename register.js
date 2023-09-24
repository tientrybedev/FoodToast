window.addEventListener("load", () => {
    setTimeout(() => {
    const loaderContainer = document.querySelector(".loader-container");
    loaderContainer.classList.add("loader-container--hidden");
    
    setTimeout(() => {
        loaderContainer.remove();
      }, 200); //
    }, 4200);
});
