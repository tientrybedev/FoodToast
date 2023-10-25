function showLoginForm() {
    const registerContainer = document.querySelector('.register-container');
    const loginContainer = document.querySelector('.login-container');
    const contain = document.querySelector('.all');
    contain.style.opacity = '0';
    loginContainer.style.animation = 'slideToLeft 1.2s ease-in forwards';
    registerContainer.style.animation = 'slideFromRight 1.2s ease-out forwards';
    setTimeout(function () {
        loginContainer.style.display = 'none';
        registerContainer.style.display = 'block';
        contain.style.opacity = '1';
    }, 1200);
}
document.getElementById('registerBtn').addEventListener('click', function () {
    showLoginForm();
});


function showRegisterForm() {
    const registerContainer = document.querySelector('.register-container');
    const loginContainer = document.querySelector('.login-container');
    const contain = document.querySelector('.all');
    contain.style.opacity = '0';
    registerContainer.style.animation = 'slideToRight 1.2s ease-in forwards';
    loginContainer.style.animation = 'slideFromLeft 1.2s ease-out forwards';
    setTimeout(function () {
        registerContainer.style.display = 'none';
        loginContainer.style.display = 'block';
        contain.style.opacity = '1';
    }, 1200);
}

// Add a click event listener to the login button
document.getElementById('loginBtn').addEventListener('click', function () {
    showRegisterForm();
});


document.addEventListener("DOMContentLoaded", function () {
    const resForm = document.getElementById("resRegisterForm");
    const form = document.querySelector(".res-register-form");
    const restaurantName = document.getElementById("restaurantName");
    const email = document.getElementById("email");
    const description = document.getElementById("description");
    const file = document.getElementById("file");
    const phone = document.getElementById("phone");
    const password = document.getElementById("password");
    const confirmPassword = document.getElementById("confirmPassword");
    const restaurantNameError = document.getElementById("restaurantNameError");
    const emailError = document.getElementById("emailError");
    const descriptionError = document.getElementById("descriptionError");
    const imgError = document.getElementById("imgError");
    const phoneError = document.getElementById("phoneError");
    const passwordError = document.getElementById("passwordError");
    const cpasswordError = document.getElementById("cpasswordError");

    resForm.addEventListener("submit", function (event) {
        event.preventDefault();
        let valid = true;

        if (restaurantName.value.trim() === "" || restaurantName.value.length < 4) {
            displayError(restaurantNameError, "Tên nhà hàng phải ít nhất 4 ký tự");
            valid = false;
        } else {
            hideError(restaurantNameError);
        }

        if (phone.value.trim().length !== 10 || isNaN(phone.value.trim())) {
            displayError(phoneError, "SĐT phải là 10 chữ số");
            valid = false;
        } else {
            hideError(phoneError);
        }

        if (description.value.trim().length < 15 || (/\d/.test(description.value) && description.value.match(/\d/g).length > 10)) {
            displayError(descriptionError, "Ít nhất 15 chữ và không được quá 10 số");
            valid = false;
        } else {
            hideError(descriptionError);
        }
        if (file.files.length === 0) {
            displayError(document.getElementById("imgError"), "Hãy chọn ảnh đại diện");
            valid = false;
        } else {
            hideError(document.getElementById("imgError"));
        }
    
        if (!validateEmail(email.value)) {
            displayError(emailError, "Email không hợp lệ");
            valid = false;
        } else {
            hideError(emailError);
        }

        if (password.value.trim().length < 4 || !/[A-Z]/.test(password.value)) {
            displayError(passwordError, "Mật khẩu không đúng yêu cầu");
            valid = false;
        } else {
            hideError(passwordError);
        }

        if (confirmPassword.value.trim() !== password.value.trim()) {
            displayError(cpasswordError, "Xác nhận không đúng");
            valid = false;
        } else {
            hideError(cpasswordError);
        }

        if (valid) {
            // If all validations pass, proceed with form submission
            const formData = new FormData(resForm);

            fetch("res_register_process.php", {
                method: "POST",
                body: formData,
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "success") {
                        // Registration was successful
                        showToast(data.message, "message");
                        resForm.reset();
                    } else if (data.status === "fail") {
                        // Display the error message from the server
                        showToast(data.message, "message");
                    }
                })
                .catch(error => {
                    // Handle network or other errors here
                    console.error(error);
                });
        }
    });

    function validateEmail(email) {
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
    //Thông báo cho người dùng
    function showToast(message, type = "info") {
        const toast = document.getElementById("toastMessage");
        toast.textContent = message;
        toast.className = "toast " + type;
        if (type === "success") {
            toast.style.backgroundColor = "rgba(26, 172, 172, 0.8)";
        }
        toast.style.display = "block";
        setTimeout(function () {
            toast.style.display = "none";
        }, 4000); // Hide after 3 seconds (adjust as needed)
    }
});


document.addEventListener("DOMContentLoaded", function () {
    const loginForm = document.getElementById("resLoginForm");
    const errorToast = document.getElementById("errorToast");

    loginForm.addEventListener("submit", function (event) {
        event.preventDefault();

        const formData = new FormData(loginForm);

        // Send the form data to the server for validation using AJAX
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "res_login_process.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);

                if (response.status === "success") {
                    // Login was successful, redirect the user or perform other actions
                    window.location.href = "res_home.php"; 
                } else {
                    // Login failed, display the error message in a toast
                    showErrorToast(response.message);
                }
            }
        };

        xhr.send(new URLSearchParams(formData).toString());
    });

    function showErrorToast(message) {
        errorToast.textContent = message;
        errorToast.style.display = "block";

        setTimeout(function () {
            errorToast.style.display = "none";
        }, 4500);
    }
});









