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