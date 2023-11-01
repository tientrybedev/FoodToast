//Loading overlay
window.addEventListener("load", () => {
    document.body.classList.add("disable-scroll");
    setTimeout(() => {
        const loaderContainer = document.querySelector(".loader-container");
        loaderContainer.classList.add("loader-container--hidden");

        setTimeout(() => {
            // không scroll khi loading
            document.body.classList.remove("disable-scroll");
            loaderContainer.remove();
        }, 200);
    }, 4200);
});

document.addEventListener('DOMContentLoaded', function () {
    var lgOut = document.getElementById("lg-out-message");
    var confirmButton = document.getElementById("confirmLogout");
    var cancelButton = document.getElementById("cancelLogout");
    var lgOutBtn = document.querySelector(".log-out-btn");
    if(lgOutBtn){
    function openMes() {
        lgOut.style.display = "block";
        document.body.classList.add("message-open"); 
    }
    function closeMes() {
        lgOut.style.display = "none";
        document.body.classList.remove("message-open"); 
    }
    lgOutBtn.addEventListener("click", openMes);
    cancelButton.addEventListener("click", closeMes);
    confirmButton.addEventListener("click", function() {
        window.location.href = "log_out.php";
    });
}
});
//hiện responsive menu
document.addEventListener("DOMContentLoaded", function () {
    const bar = document.getElementById('responBar');
    const hiddenMenu = document.getElementById('hiddenBar');

    bar.onclick = function () {
        bar.classList.toggle('openbar');
        hiddenMenu.classList.toggle('bar-active');
    };
});

//==========================================================================================
//==========================================================================================
//===============================================sticky-navbar
//==========================================================================================
//==========================================================================================
//Thanh công cụ
let prevScrollPos = window.pageYOffset;
let isNavVisible = true;
let reachedTop = false;
window.addEventListener("scroll", function() {
    var nav = document.querySelector(".nav-bar");
    var currentScrollPos = window.pageYOffset;
    if (prevScrollPos > currentScrollPos) {
        // Kéo lên
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
//Hiển thị sidebar
window.addEventListener("scroll", function() {
    const sidebar = document.querySelector(".sidebar-container");
    const start = document.querySelector(".top-content");
    const end = document.querySelector("#footer");

    // Tính toán vị trí giữa 2 section
    const rectS = start.getBoundingClientRect();
    const rectE = end.getBoundingClientRect();

    // hiển thị side bar
    if (rectS.bottom < 0 && rectE.top > window.innerHeight) {
        sidebar.style.opacity = "1"; 
        sidebar.style.pointerEvents = "auto"; 
    } else {
        sidebar.style.opacity = "0"; 
        sidebar.style.pointerEvents = "none"; 
    }
});



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
let cardPerView = Math.round(carousel.offsetWidth / firstCardWidth);
carouselChildrens.slice(-cardPerView).reverse().forEach(card => {
    carousel.insertAdjacentHTML("afterbegin", card.outerHTML);
});
carouselChildrens.slice(0, cardPerView).forEach(card => {
    carousel.insertAdjacentHTML("beforeend", card.outerHTML);
});
carousel.classList.add("no-transition");
carousel.scrollLeft = carousel.offsetWidth;
carousel.classList.remove("no-transition");
arrowBtns.forEach(btn => {
    btn.addEventListener("click", () => {
        carousel.scrollLeft += btn.id == "left" ? -firstCardWidth : firstCardWidth;
    });
});
const dragStart = (e) => {
    isDragging = true;
    carousel.classList.add("dragging");
    startX = e.pageX;
    startScrollLeft = carousel.scrollLeft;
}
const dragging = (e) => {
    if(!isDragging) return;
    carousel.scrollLeft = startScrollLeft - (e.pageX - startX);
}
const dragStop = () => {
    isDragging = false;
    carousel.classList.remove("dragging");
}
const infiniteScroll = () => {
    if(carousel.scrollLeft === 0) {
        carousel.classList.add("no-transition");
        carousel.scrollLeft = carousel.scrollWidth - (2 * carousel.offsetWidth);
        carousel.classList.remove("no-transition");
    }
    else if(Math.ceil(carousel.scrollLeft) === carousel.scrollWidth - carousel.offsetWidth) {
        carousel.classList.add("no-transition");
        carousel.scrollLeft = carousel.offsetWidth;
        carousel.classList.remove("no-transition");
    }
    clearTimeout(timeoutId);
    if(!wrapper.matches(":hover")) autoPlay();
}
const autoPlay = () => {
    if(window.innerWidth < 800 || !isAutoPlay) return; 
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
    //chức năng thêm sản phẩm vào giỏ hàng, cập nhật số lượng sản phẩm trong giỏ hàng   
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
                    showToast.removeClass("success-toast error-toast"); 
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
//thông báo nếu chưa đăng nhập 
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

$(document).ready(function () {
    var brandsContainer = $(".brands-container");
    var secondRow = $(".brands-container.second-row");
    var showSecondRowButton = $("#showSecondRowButton");
    var viewAllLink = $("#viewAllLink");
    // nếu có ít hơn 4 nhà hàng
    secondRow.hide();
    viewAllLink.hide();
    // điều kiện nếu có trên 4 nhà hàng
    if (brandsContainer.children().length > 4) {
        showSecondRowButton.show();
    }
    // mở hàng thứ hai nhà hàng
    showSecondRowButton.click(function () {
        secondRow.slideToggle();
        showSecondRowButton.text(function (i, text) {
            return text === "Xem Thêm" ? "Ẩn bớt" : "Xem Thêm";
        });
    });
});

//chức năng thêm vào yêu thích -Ajax-
$(document).ready(function () {
    $(".like").on("click", function () {
        const userId = $(this).data("user-id"); 
        const isLoggedIn = document.querySelector(".not-logged-in");
        const productId = $(this).data("product-id"); 
        const likeButton = $(this);
        if(!isLoggedIn){
            console.log("User is logged in. Sending AJAX request...");
        $.ajax({
            type: "POST",
            url: "add_to_favorites.php",
            data: { user_id: userId, product_id: productId },
            dataType: "json",
            success: function (response) {
                if (response.status === "success") {
                    var showToast = $("#toastMessage");
                    showToast.show();
                    showToast.text(response.message); 
                    showToast.addClass("success-toast"); 
                    likeButton.find("i").css({
                        cursor: 'default',
                        pointerEvents: 'none',
                        color: 'var(--heart-color)',
                        transform: 'scale(1.2)'
                    });
                } else {
                    var showToast = $("#toastMessage");
                    showToast.text(response.message); 
                    showToast.addClass("error-toast"); 
                }
                setTimeout(function() {
                    if(showToast){
                    showToast.hide();
                    showToast.removeClass("success-toast error-toast");
                    }
                }, 3500);
            },
            error: function (xhr, textStatus, errorThrown) {
                console.error("Error: " + textStatus, errorThrown);
                alert("Lỗi."); 
            }
        });
    } else{
        showLoginAnnouncement();
    }
    });
});
