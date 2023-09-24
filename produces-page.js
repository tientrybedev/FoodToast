//==========================================================================================
//==========================================================================================
//===============================================sticky-navbar
//==========================================================================================
//==========================================================================================
let prevScrollPos = window.pageYOffset;
let isNavVisible = true;
let reachedTop = false;

window.addEventListener("scroll", function() {
    const nav = document.querySelector(".nav-bar");
    const currentScrollPos = window.pageYOffset;

    if (prevScrollPos > currentScrollPos) {
        // Scrolling up
        if (!isNavVisible) {
            nav.style.top = "0";
            nav.classList.add("sticky");
            isNavVisible = true;
        }
    } else {
        // Kéo xuống
        if (isNavVisible) {
            nav.style.top = "-125px";
            nav.classList.remove("sticky");
            isNavVisible = false;
        }
    }

    if (currentScrollPos === 0) {
        reachedTop = true;
    } else {
        reachedTop = false;
    }

    if (reachedTop) {
        nav.classList.remove("sticky");
    } else {
        nav.classList.add("sticky");
    }

    prevScrollPos = currentScrollPos;
});
window.addEventListener("scroll", function() {
    const sidebar = document.querySelector(".sidebar-container");
    const start = document.querySelector(".start");
    const end = document.querySelector("#footer");

    // Calculate the position of section 1 and section 6 relative to the viewport
    const rectS = start.getBoundingClientRect();
    const rectE = end.getBoundingClientRect();

    // If section 1 is above the viewport and section 6 is below the viewport, show the sidebar
    if (rectS.bottom < 0 && rectE.top > window.innerHeight) {
        sidebar.style.opacity = "1"; // Set opacity to 1 to make it visible
        sidebar.style.pointerEvents = "auto"; // Enable pointer events
    } else {
        sidebar.style.opacity = "0"; // Set opacity to 0 to hide it
        sidebar.style.pointerEvents = "none"; // Disable pointer events
    }
});
const bar =document.querySelector('.bar');
const hiddenMenu = document.querySelector('.hidden-bar')
bar.onclick = function(){
    bar.classList.toggle('openbar')
    hiddenMenu.classList.toggle('bar-active')
}








window.addEventListener("load", () => {
    // Delay for 4 seconds (4000 milliseconds) before removing the loader
    setTimeout(() => {
    const loaderContainer = document.querySelector(".loader-container");
    loaderContainer.classList.add("loader-container--hidden");

    setTimeout(() => {
            loaderContainer.remove();
        }, 200);
    }, 3350);
});

function toggleSection(buttonId, sectionId) {
    const button = document.getElementById(buttonId);
    const section = document.getElementById(sectionId);
    const secondRow = section.querySelectorAll('.card.hidden'); 

    button.addEventListener('click', () => {
        secondRow.forEach(card => {
            card.classList.toggle('hidden');
        });

        if (button.innerHTML.includes('fa-angles-up')) {
            button.innerHTML = '<i class="fa-solid fa-angles-down"></i>';
            button.classList.remove('move');
        } else {
            button.innerHTML = '<i class="fa-solid fa-angles-up"></i>';
            button.classList.add('move');
        }
    });
}
// ứng dụng cho các section khác 
toggleSection('showSecondRow1', 'stuff1');
toggleSection('showSecondRow2', 'stuff2');
toggleSection('showSecondRow3', 'stuff3');
toggleSection('showSecondRow4', 'stuff4');
toggleSection('showSecondRow5', 'stuff5');
toggleSection('showSecondRow6', 'stuff6');

document.addEventListener("DOMContentLoaded", function() {
    for (let i = 1; i <= 6; i++) {
        const item = document.getElementById(`item${i}`);
        const section = document.getElementById(`section${i}`);
        const fSpecial = document.getElementById('f-special');
        const specialSection = document.getElementById("special");

        item.addEventListener("click", function() {
            setTimeout(function() {
                section.scrollIntoView({ behavior: "smooth", block: "start" });
            }, 200); // 
        });

        fSpecial.addEventListener("click", function() {
            setTimeout(function() {
                specialSection.scrollIntoView({ behavior: "smooth", block: "start" });
            }, 200);
        });
    }
});



const wrapper = document.querySelector(".wrapper");
const carousel = document.querySelector(".carousel");
const firstCardWidth = carousel.querySelector(".guest-card").offsetWidth;
const arrowBtns = document.querySelectorAll(".wrapper i");
const carouselChildrens = [...carousel.children];

let isDragging = false, isAutoPlay = true, startX, startScrollLeft, timeoutId;
// Get the number of cards that can fit in the carousel at once
let cardPerView = Math.round(carousel.offsetWidth / firstCardWidth);
// Insert copies of the last few cards to beginning of carousel for infinite scrolling
carouselChildrens.slice(-cardPerView).reverse().forEach(card => {
    carousel.insertAdjacentHTML("afterbegin", card.outerHTML);
});
// Insert copies of the first few cards to end of carousel for infinite scrolling
carouselChildrens.slice(0, cardPerView).forEach(card => {
    carousel.insertAdjacentHTML("beforeend", card.outerHTML);
});
// Scroll the carousel at appropriate postition to hide first few duplicate cards on Firefox
carousel.classList.add("no-transition");
carousel.scrollLeft = carousel.offsetWidth;
carousel.classList.remove("no-transition");
// Add event listeners for the arrow buttons to scroll the carousel left and right
arrowBtns.forEach(btn => {
    btn.addEventListener("click", () => {
        carousel.scrollLeft += btn.id == "left" ? -firstCardWidth : firstCardWidth;
    });
});
const dragStart = (e) => {
    isDragging = true;
    carousel.classList.add("dragging");
    // Records the initial cursor and scroll position of the carousel
    startX = e.pageX;
    startScrollLeft = carousel.scrollLeft;
}
const dragging = (e) => {
    if(!isDragging) return; // if isDragging is false return from here
    // Updates the scroll position of the carousel based on the cursor movement
    carousel.scrollLeft = startScrollLeft - (e.pageX - startX);
}
const dragStop = () => {
    isDragging = false;
    carousel.classList.remove("dragging");
}
const infiniteScroll = () => {
    // If the carousel is at the beginning, scroll to the end
    if(carousel.scrollLeft === 0) {
        carousel.classList.add("no-transition");
        carousel.scrollLeft = carousel.scrollWidth - (2 * carousel.offsetWidth);
        carousel.classList.remove("no-transition");
    }
    // If the carousel is at the end, scroll to the beginning
    else if(Math.ceil(carousel.scrollLeft) === carousel.scrollWidth - carousel.offsetWidth) {
        carousel.classList.add("no-transition");
        carousel.scrollLeft = carousel.offsetWidth;
        carousel.classList.remove("no-transition");
    }
    // Clear existing timeout & start autoplay if mouse is not hovering over carousel
    clearTimeout(timeoutId);
    if(!wrapper.matches(":hover")) autoPlay();
}
const autoPlay = () => {
    if(window.innerWidth < 800 || !isAutoPlay) return; // Return if window is smaller than 800 or isAutoPlay is false
    // Autoplay the carousel after every 2500 ms
    timeoutId = setTimeout(() => carousel.scrollLeft += firstCardWidth, 2500);
}
autoPlay();
carousel.addEventListener("mousedown", dragStart);
carousel.addEventListener("mousemove", dragging);
document.addEventListener("mouseup", dragStop);
carousel.addEventListener("scroll", infiniteScroll);
wrapper.addEventListener("mouseenter", () => clearTimeout(timeoutId));
wrapper.addEventListener("mouseleave", autoPlay);
