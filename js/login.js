function hideErrorMessage() {
        var errorMessage = document.querySelector(".error-message");
        if (errorMessage) {
            errorMessage.style.opacity = "1"; 
            errorMessage.style.transform = "translateY(0)"; // Reset translateY to 0
        }
    }
    setTimeout(hideErrorMessage, 1500);
        setTimeout(function () {
        var errorMessage = document.querySelector(".error-message");
            if (errorMessage) {
            errorMessage.style.opacity = "0"; 
            errorMessage.style.transform = "translateY(-150px)"; 
            }
        }, 4500);

        document.addEventListener("DOMContentLoaded", function() {
            var formSubmitted = sessionStorage.getItem("formSubmitted");
            if (!formSubmitted) {
                ScrollReveal({ reset: false });
                ScrollReveal().reveal('.content-container', { duration: 1000, delay: 300, opacity: 0 });
                ScrollReveal().reveal('.left-box', { duration: 900, delay: 800, distance: '30px', origin: 'left' });
                ScrollReveal().reveal('.right-box', { duration: 900, delay: 800, distance: '30px', origin: 'right' });
            }
            sessionStorage.removeItem("formSubmitted");
        });
        
        var form = document.querySelector('form');
        
        form.addEventListener('submit', function() {
            sessionStorage.setItem("formSubmitted", "true");
        });

