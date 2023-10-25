
function hideErrorMessage() {
  var errorMessage = document.querySelector(".error-message");
  if (errorMessage) {
      errorMessage.style.opacity = "1"; 
      errorMessage.style.transform = "translateY(0)"; 
  }
}
setTimeout(hideErrorMessage, 100);

setTimeout(function () {
  var errorMessage = document.querySelector(".error-message");
  if (errorMessage) {
      errorMessage.style.opacity = "0"; 
      errorMessage.style.transform = "translateY(-100px)"; 
  }
}, 4500);

function SuccessMessage() {
  var successMessage = document.querySelector(".success-message");
  var allDiv = document.querySelector(".all");
  
  if (successMessage && allDiv) {
    allDiv.style.pointerEvents = "none";
    setTimeout(function () {
      successMessage.style.transform = "translateY(150px)";
      successMessage.style.opacity = "1"; 
    });
  }
}

SuccessMessage();


function showAlert(message) {
  var alertContainer = document.getElementById("alert-container");

  var alert = document.createElement("div");
  alert.className = "alert";
  alert.innerHTML = `
      ${message}
      <span class="close-icon" onclick="closeAlert(this)">X</span>
  `;

  alertContainer.appendChild(alert);

  setTimeout(function () {
      alertContainer.removeChild(alert);
  }, 4500);
}
function showAlert(message) {
var alertContainer = document.getElementById("alert-container");

// Create an alert element
var alert = document.createElement("div");
alert.className = "alert";
alert.innerHTML = `
    ${message}
    <span class="close-icon" onclick="closeAlert(this)">X</span>
`;

alertContainer.appendChild(alert);
setTimeout(function () {
    alertContainer.removeChild(alert);
}, 4500);
}

function closeAlert(element) {
var alertContainer = document.getElementById("alert-container");
alertContainer.removeChild(element.parentElement);
}

  function validateForm() {
      var username = document.getElementById("username").value;
      var password = document.getElementById("password").value;
      var phone = document.getElementById("phone").value; // Remove non-digit characters
      var confirmPassword = document.getElementById("confirmPassword").value;
      var uppercaseRegex = /[A-Z]/;
      if (username.length < 4) {
        showAlert("Tên đăng nhập phải trên 4 ký tự");
        return false; 
      }
      if (password.length < 1 || !uppercaseRegex.test(password)) {
        showAlert("Mật khẩu phải trên 5 ký tự và gồm chữ hoa");
        return false;
      }
      if (password !== confirmPassword) {
        showAlert("Xác nhận mật khẩu không chính xác");
        return false; 
      }
      if (phone.length !== 10 || isNaN(phone)) {
        showAlert("Số điện thoại phải chứa đúng 10 chữ số.");
        return false;
    }
      return true;
  }
  document.addEventListener("DOMContentLoaded", function() {
    var formSubmitted = sessionStorage.getItem("formSubmitted");
    if (!formSubmitted) {
        ScrollReveal({ reset: false });
        ScrollReveal().reveal('.container', { duration: 800, delay: 800 , distance: '30px', origin: 'bottom'});
        ScrollReveal().reveal(' .item1', { duration: 800, delay: 850 , distance: '60px', origin: 'top'});
        ScrollReveal().reveal(' .item2, .item3 ', { duration: 800, delay: 900 , distance: '60px', origin: 'top', interval: 200});
    }

    // Remove the formSubmitted session flag after processing
    sessionStorage.removeItem("formSubmitted");
});


    // Add an event listener to detect form submission
    var form = document.querySelector('#registerForm');

    form.addEventListener('submit', function (event) {
        var fileInput = document.getElementById('file');
        var maxFileSize = 1000 * 1024; // 120 KB in bytes (1 KB = 1024 bytes)

        if (fileInput.files.length > 0) {
            var fileSize = fileInput.files[0].size;

            if (fileSize > maxFileSize) {
                alert("File size is too large. Please upload a file under 120 KB.");
                event.preventDefault(); // Prevent form submission
            }
        }
    });

    // Function to check file size on input change
    function checkFileSize(input) {
        var maxFileSize = 1000 * 1024; // 120 KB in bytes (1 KB = 1024 bytes)

        if (input.files.length > 0) {
            var fileSize = input.files[0].size;

            if (fileSize > maxFileSize) {
                alert("File size is too large. Please upload a file under 120 KB.");
                input.value = ""; // Clear the file input
            }
        }
    }


