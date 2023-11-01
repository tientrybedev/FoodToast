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
        // kéo lên
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
// tương tác giữ scroll và sidebar
window.addEventListener("scroll", function() {
    const sidebar = document.querySelector(".sidebar-container");
    const start = document.querySelector(".start");
    const end = document.querySelector("#footer");
    //tính khoảng cách hiển thị
    const rectS = start.getBoundingClientRect();
    const rectE = end.getBoundingClientRect();
    //khoảng cách hiển thị side bar
    if (rectS.bottom < 0 && rectE.top > window.innerHeight) {
        sidebar.style.opacity = "1"; 
        sidebar.style.pointerEvents = "auto"; 
    } else {
        sidebar.style.opacity = "0"; 
        sidebar.style.pointerEvents = "none"; 
    }
});
// hiện responsive menu
const bar =document.querySelector('.bar');
const hiddenMenu = document.querySelector('.hidden-bar')
bar.onclick = function(){
    bar.classList.toggle('openbar')
    hiddenMenu.classList.toggle('bar-active')
}

window.addEventListener("load", () => {
    //khi load, không được scroll
    document.body.classList.add("disable-scroll");
    setTimeout(() => {
        const loaderContainer = document.querySelector(".loader-container");
        loaderContainer.classList.add("loader-container--hidden");

        setTimeout(() => {

            document.body.classList.remove("disable-scroll");
            loaderContainer.remove();
        }, 200);
    }, 3350);
});

// hiển thị hàng thứ 2 ở các phân loại 
function toggleSection(buttonId, sectionId) {
    const button = document.getElementById(buttonId);
    const section = document.getElementById(sectionId);
    if (button) {
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
}
// áp dụng cho các nút 
toggleSection('showSecondRow1', 'stuff1');
toggleSection('showSecondRow2', 'stuff2');
toggleSection('showSecondRow3', 'stuff3');
toggleSection('showSecondRow4', 'stuff4');
toggleSection('showSecondRow5', 'stuff5');
toggleSection('showSecondRow6', 'stuff6');

// ấn nút để chuyển đến phân loại
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

//carousel có thể kéo, tự động chuyển, có nút bấm
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

// hiển thị sản phẩm sl sản phẩm trong giỏ, thêm sản phẩm vào giỏ
$(document).ready(function() {
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
                    showToast.removeClass("success-toast error-toast"); 
                }, 3500);
            }
        });
    }
    $(".addToCart").on("click", function() {
        const productId = $(this).data("product-id");
        addToCart(productId);
    });
    updateCartBadge();
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
var http; 
function object() {
    if (window.XMLHttpRequest) {
        // cho IE7+, Firefox, Chrome, Opera, Safari
        http = new XMLHttpRequest();
    } else {
        // cho IE6, IE5
        http = new ActiveXObject("Microsoft.XMLHTTP");
    }
    return http;
}
//live search
function liveSearch(query) {
    const searchResults = document.getElementById("searchResults");
    if (query.trim() !== "") {
        const http = object(); 
        http.onreadystatechange = process;
        http.open('GET', 'search_products.php?query=' + encodeURIComponent(query), true);
        http.send();
        // Hiệu ứng cho kết quả tìm kiếm
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

    lastScrollY = currentScrollY;
}
let lastScrollY = 0;
if (document.getElementById("searchResults")) {
    window.addEventListener("scroll", handleScroll);
}
//thêm sản phẩm vào trang sản phẩm yêu thích
$(document).ready(function () {
    $(".like").on("click", function () {
        const userId = $(this).data("user-id");
        const isLoggedIn = document.querySelector(".not-logged-in");
        const productId = $(this).data("product-id");
        const likeButton = $(this);
        console.log("Clicked element:", this); 
        if (!isLoggedIn) {
            $.ajax({
                type: "POST",
                url: "add_to_favorites.php",
                data: { user_id: userId, product_id: productId },
                dataType: "json",
                success: function (response) {
                    console.log("Ajax response:", response); 
                    if (response.status === "success") {
                        var showToast = $("#toastMessage");
                        showToast.show();
                        showToast.text(response.message);
                        showToast.addClass("success-toast");
                        console.log("Like button:", likeButton); 
                        likeButton.find("i").css({
                            pointerEvents: 'none',
                            color: 'var(--heart-color)',
                            transform: 'scale(1.2)'
                        });
                    }
                    setTimeout(function () {
                        showToast.hide();
                        showToast.removeClass("success-toast");
                    }, 3500);
                },
                error: function (xhr, textStatus, errorThrown) {
                    console.error("Error: " + textStatus, errorThrown);
                    alert("Lỗi.");
                }
            });
        } else {
            showLoginAnnouncement();
        }
    });
});






