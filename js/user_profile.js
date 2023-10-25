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

// Get references to the button and modal
var openFormButton = document.getElementById("open_change_form");
var closeFormButton = document.getElementById("close_change_form");
var changeForm = document.getElementById("change_form_container");

// Open the form when the button is clicked
openFormButton.addEventListener("click", function() {
    changeForm.style.display = "block";
});

// Close the form when the close button is clicked
closeFormButton.addEventListener("click", function() {
    changeForm.style.display = "none";
});
const errorMes = document.querySelector(".error-messages");
if(errorMes){
setTimeout(function () {
    errorMes.style.display = "block";
}, 5000);

setTimeout(function () {
    errorMes.style.opacity = "0";
    errorMes.style.transform = "translateY(-50px)";
    errorMes.style.visibility = "hidden"; // Hide it again;
}, 7500);
setTimeout(function () {
    errorMes.style.display = "none";
}, 9000);
}

document.addEventListener("DOMContentLoaded", function () {
    const changeProfileForm = document.getElementById("changeProfileForm");
    const username = document.getElementById("username");
    const phone = document.getElementById("phone");
    const email = document.getElementById("email");
    const usernameError = document.getElementById("usernameError");
    const phoneError = document.getElementById("phoneError");
    const emailError = document.getElementById("emailError");
    const imgError = document.getElementById("imgError");

    changeProfileForm.addEventListener("submit", function (event) {
        event.preventDefault();
        let valid = true;

        if (username.value.trim() === "" || username.value.length < 4) {
            displayError(usernameError, "Tên đăng nhập phải ít nhất 4 ký tự");
            valid = false;
        } else {
            hideError(usernameError);
        }

        if (phone.value.trim().length !== 10 || isNaN(phone.value.trim())) {
            displayError(phoneError, "Số điện thoại phải là 10 chữ số");
            valid = false;
        } else {
            hideError(phoneError);
        }

        if (email.value.trim() === "" || !validateEmail(email.value)) {
            displayError(emailError, "Email không hợp lệ");
            valid = false;
        } else {
            hideError(emailError);
        }


        if (valid) {
            submitProfileUpdate();
        }

    function validateEmail(email) {
        // Check if the email contains @ and ends with .com
        return email.includes("@") && email.endsWith(".com");
    }

    function displayError(errorElement, errorMessage) {
        errorElement.textContent = errorMessage;
        errorElement.style.opacity = "1";
        errorElement.style.bottom = "-26px";
        setTimeout(function () {
            hideError(errorElement);
        }, 4000);
    }

    function hideError(errorElement) {
        errorElement.textContent = "";
        errorElement.style.opacity = "0";
        errorElement.style.bottom = "-20px";
    }
});

function submitProfileUpdate() {
    var formData = new FormData(changeProfileForm);

    $.ajax({
        type: "POST",
        url: "update_user_profile.php",
        data: formData,
        processData: false, // Prevent data processing for file uploads
        contentType: false, // Set content type to false for file uploads
        dataType: "json", // Expect JSON response
        success: function (response) {
            if (response.status === "success") {
                $("#change_form_container").hide();
                $("#edit_username").text(response.newUsername);
                $("#edit_phone").text(response.newPhone);
                $("#edit_email").text(response.newEmail);
                if (response.newImage) {
                    $("#profileImage").attr("src", response.newImage);
                }
                var showToast = $("#toastMessage");
                showToast.show();
                showToast.text(response.message);
                showToast.addClass("success-toast");
    
                // After a delay, hide the toast message and remove styling classes
                setTimeout(function () {
                    showToast.hide();
                    showToast.removeClass("success-toast");
                }, 3500);
            } else {
                // Handle error messages and display them
                var errorMessage = response.message.join('\n');
                alert("Profile update failed:\n" + errorMessage);
                var showToast = $("#profileUpdateToast");
                showToast.show();
                showToast.text(response.message);
                showToast.addClass("error-toast");

                // After a delay, hide the toast message and remove styling classes
                setTimeout(function () {
                    showToast.hide();
                    showToast.removeClass("error-toast");
                }, 3500);
            }
        }
    });
}
});