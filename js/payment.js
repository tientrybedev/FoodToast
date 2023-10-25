document.addEventListener("DOMContentLoaded", function () {
    const paymentForm = document.getElementById("paymentForm");
    const realname = document.getElementById("realname");
    const address = document.getElementById("address");
    const phone = document.getElementById("phone");
    const realNameError = document.getElementById("realNameError");
    const addressError = document.getElementById("addressError");
    const phoneError = document.getElementById("phoneError");

    const cardInfo = document.getElementById('cardInfo');
    const cardNumberInput = document.getElementById('card-number');
    const cardNumberError = document.getElementById('cardNumberError');
    const cvvInput = document.getElementById('cvvInput');
    const cvvError = document.getElementById('cvvError');
    const radioButtons = document.querySelectorAll('input[name="paymentType"]');

    paymentForm.addEventListener("submit", function (event) {
        let valid = true;
        const selectedPaymentOption = document.querySelector('input[name="paymentType"]:checked').value;

        if (selectedPaymentOption === 'Banking') {
            const cardNumber = cardNumberInput.value.trim();
            const cvv = cvvInput.value.trim();

            if (cardNumber === "" || cardNumber.length !== 19) {
                displayError(cardNumberError, "Số thẻ phải là 16 số");
                valid = false;
            } else {
                hideError(cardNumberError);
            }

            if (cvv === "" || cvv.length !== 3) {
                displayError(cvvError, "CVV phải là 3 số");
                valid = false;
            } else {
                hideError(cvvError);
            }
        }

        if (realname.value.trim() === "" || realname.value.length < 2) {
            displayError(realNameError, "Họ và tên phải ít nhất 4 ký tự");
            valid = false;
        } else {
            hideError(realNameError);
        }

        if (address.value.trim() === "") {
            displayError(addressError, "Vui lòng nhập địa chỉ");
            valid = false;
        } else {
            hideError(addressError);
        }

        if (phone.value.trim().length !== 10 || isNaN(phone.value.trim())) {
            displayError(phoneError, "Số điện thoại phải là 10 chữ số");
            valid = false;
        } else {
            hideError(phoneError);
        }

        if (!valid) {
            event.preventDefault();
        }
    });

    radioButtons.forEach(function (radioButton) {
        radioButton.addEventListener('change', function () {
            if (radioButton.checked && radioButton.value === 'Banking') {
                cardInfo.style.animation = 'showup 1s forwards';
            } else {
                cardInfo.style.animation = 'close 1s forwards';
                hideError(cardNumberError);
                hideError(cvvError);
            }
        });
    });

    function displayError(errorElement, errorMessage) {
        errorElement.textContent = errorMessage;
        errorElement.style.opacity = "1";
        setTimeout(function () {
            hideError(errorElement);
        }, 2500); 
    }

    function hideError(errorElement) {
        errorElement.textContent = "";
        errorElement.style.opacity = "0";
    }
});

document.getElementById("address").addEventListener("input", function () {
    const address = this.value.toLowerCase(); // Convert the address to lowercase for case-insensitive comparison

    if (address.includes("q1") || address.includes("q2") || address.includes("q3") || address.includes("q4")) {
        document.getElementById("deliveryCost").value = "15.000";
    } else if (address.includes("q5") || address.includes("q6") || address.includes("q7") || address.includes("q8") || address.includes("q9")) {
        document.getElementById("deliveryCost").value = "30.000";
    } else {
        document.getElementById("deliveryCost").value = "45.000";
    }

    // Calculate the final payment and display it
    const totalAmount = parseFloat(document.getElementById("totalAmount").value);
    const deliveryCost = parseFloat(document.getElementById("deliveryCost").value);
    const finalPayment = totalAmount + deliveryCost;
    document.getElementById("finalPayment").value = finalPayment.toFixed(3);
});


function validateRealName(input) {
    input.value = input.value.replace(/[0-9]/g, "");
}

function validateCVV(input) {
    input.value = input.value.replace(/\D/g, "");
}

function validateCardNumber(input) {
    input.value = input.value.replace(/\D/g, '');

    const cardNumber = input.value;
    const formattedCardNumber = cardNumber.replace(/(\d{4})(?=\d)/g, '$1 ');
    input.value = formattedCardNumber;
}

