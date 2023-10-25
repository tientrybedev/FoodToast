window.addEventListener("load", () => {
    // Add the class that disables scrolling
    document.body.classList.add("disable-scroll");

    // Delay for 4 seconds (4000 milliseconds) before removing the loader
    setTimeout(() => {
        const loaderContainer = document.querySelector(".loader-container");
        loaderContainer.classList.add("loader-container--hidden");

        setTimeout(() => {
            // Remove the class that disables scrolling
            document.body.classList.remove("disable-scroll");
            loaderContainer.remove();
        }, 200);
    }, 4200);
});
document.addEventListener('DOMContentLoaded', function () {
    // Get references to the modal and buttons
    var lgOut = document.getElementById("lg-out-message");
    var confirmButton = document.getElementById("confirmLogout");
    var cancelButton = document.getElementById("cancelLogout");
    var lgOutBtn = document.querySelector(".log-out-btn");

    if(lgOutBtn){
    function openMes() {
        lgOut.style.display = "block";
        document.body.classList.add("message-open"); // Add the class
    }

    // Function to close the modal
    function closeMes() {
        lgOut.style.display = "none";
        document.body.classList.remove("message-open"); 
    }

    // Show the modal when the "Log Out" button is clicked
    lgOutBtn.addEventListener("click", openMes);

    // Close the modal when the "Cancel" button is clicked
    cancelButton.addEventListener("click", closeMes);

    // Redirect to logout.php when the "Confirm" button is clicked
    confirmButton.addEventListener("click", function() {
        window.location.href = "log_out.php";
    });
}
});



//==========================================================================================
//==========================================================================================
//===============================================sticky-navbar
//==========================================================================================
//==========================================================================================
let prevScrollPos = window.pageYOffset;
let isNavVisible = true;
let reachedTop = false;

window.addEventListener("scroll", function() {
    var nav = document.querySelector(".nav-bar");
    var currentScrollPos = window.pageYOffset;

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
    const start = document.querySelector(".top-content");
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



 // ==========================================================================
  // ==========================================================================
//======================================carousel-section
 // ==========================================================================
// ===========================================================================
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

//tìm kiếm live trên search-bar
var http;
function object() {
    if (window.XMLHttpRequest) {
        // code cho IE7+, Firefox, Chrome, Opera, Safari
        http = new XMLHttpRequest();
    } else {
        // code cho IE6, IE5
        http = new ActiveXObject("Microsoft.XMLHTTP");
    }
    return http;
}
function liveSearch(query) {
    const searchResults = document.getElementById("searchResults");

    if (query.trim() !== "") {
        const http = object(); 
        http.onreadystatechange = process;
        http.open('GET', 'search_products.php?query=' + encodeURIComponent(query), true);
        http.send();
        // hiệu ứng UI/UX
        searchResults.style.display = "block";
        setTimeout(function () {
            searchResults.style.opacity = "1";
            searchResults.style.transform = "translateY(0)";
            searchResults.style.visibility = "visible";
        }, 130);
    }else{
        searchResults.style.opacity = "0";
        searchResults.style.transform = "translateY(-12px)";
        searchResults.style.visibility = "hidden"; 
        setTimeout(function () {
            searchResults.style.display = "none";
        }, 350);
    }
}

function process() {
    if (http.readyState === 4 && http.status === 200) {
        const result = http.responseText;
        document.getElementById("searchResults").innerHTML = result;
    }
}
//Hiệu ứng kéo xuống cho kết quả tìm kiếm
function handleScroll() {
    const currentScrollY = window.scrollY;
    const searchResults = document.getElementById("searchResults");
    if (searchResults) {
        if (currentScrollY > lastScrollY) {
            searchResults.style.transform ="translateY(-18px)"
            setTimeout(function () {
                searchResults.style.opacity ="0"
                searchResults.style.visibility = "hidden";
            }, 140);

        } else {
            setTimeout(function () {
                searchResults.style.transform ="translateY(0)"
                searchResults.style.opacity ="1"
                searchResults.style.visibility = "visible";
            }, 180);
        }
    }
    // Update the lastScrollY variable
    lastScrollY = currentScrollY;
}
let lastScrollY = 0;
if (document.getElementById("searchResults")) {
    window.addEventListener("scroll", handleScroll);
}



//đếm sl sản phẩm và hiển thị
$(document).ready(function() {
    function updateCartBadge() {
        $.ajax({
            type: "GET",
            url: "cart_count.php",
            dataType: "json", 
            success: function(response) {
                var cartCount = response.count;
                $("#cartBadge").text(response.count);
                if (cartCount === 0) {
                    $("#cartBadge").hide();
                } else {
                    $("#cartBadge").show();
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                console.error("Error fetching cart count:", textStatus, errorThrown);
            }
        });
    }    
    //thêm sản phẩm vào giỏ hàng
        function updateCartBadge() {
        $.ajax({
            type: "GET",
            url: "cart_count.php",
            dataType: "json", // Replace with the actual URL to get cart count
            success: function(response) {
                var cartCount = response.count;
                $("#cartBadge").text(response.count);
                if (cartCount === 0) {
                    $("#cartBadge").hide();
                } else {
                    $("#cartBadge").show();
                }
            },
            error: function(xhr, textStatus, errorThrown) {
                console.error("Error fetching cart count:", textStatus, errorThrown);
            }
        });
    }    
    function addToCart(productId) {
        $.ajax({
            type: "POST",
            url: "add_to_cart_process.php",
            data: { product_id: productId },
            success: function(response) {
                updateCartBadge();
                var showToast = $("#toastMessage");
                showToast.show();
                if (response.status === "success") {
                    showToast.text(response.message); 
                    showToast.addClass("success-toast"); 
                }else{
                    showToast.text(response.message); 
                    showToast.addClass("error-toast"); 
                }
                setTimeout(function() {
                    showToast.hide();
                    showToast.removeClass("success-toast error-toast"); // Remove styling 
                }, 3500);
            }
        });
    }
    $(".into-cart-btn").on("click", function() {
        const productId = $(this).data("product-id");
        addToCart(productId);
    });
    updateCartBadge();
});


$(document).ready(function () {
    var brandsContainer = $(".brands-container");
    var secondRow = $(".brands-container.second-row");
    var showSecondRowButton = $("#showSecondRowButton");
    var viewAllLink = $("#viewAllLink");

    // Initially, hide the second row and the "View All" link.
    secondRow.hide();
    viewAllLink.hide();

    // Check if there are more than 4 brand images to show the "Show More" button.
    if (brandsContainer.children().length > 4) {
        showSecondRowButton.show();
    }

    // Toggle the visibility of the second row when clicking the "Show More" button.
    showSecondRowButton.click(function () {
        secondRow.slideToggle();
        showSecondRowButton.text(function (i, text) {
            return text === "Xem Thêm" ? "Ẩn bớt" : "Xem Thêm";
        });
    });
});

function showLoginAnnouncement(event) {
    if(event){
        event.preventDefault();
        var showToast = $('#toastMessage');
        showToast.show();
        showToast.addClass('announce');
        showToast.text("Bạn Chưa Đăng Nhập");
        setTimeout(function() {
            showToast.hide();
        }, 4000);
    }else{
        var showToast = $('#toastMessage');
        showToast.show();
        showToast.addClass('announce');
        showToast.text("Bạn Chưa Đăng Nhập");
        setTimeout(function() {
            showToast.hide();
        }, 4000);
    }
}