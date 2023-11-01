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
updateCartBadge();
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