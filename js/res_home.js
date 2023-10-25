// form thêm sản phẩm
const openAddForm = document.getElementById("addProductBtn");

const modal = document.getElementById("add_product_container");
if(openAddForm){
openAddForm.addEventListener("click", () => {
    modal.style.display = "block"; 
    document.body.classList.add("non-scroll"); // chặn scroll
});

// đóng form
const closeAddForm = document.getElementById("closeAddProduct");
closeAddForm.addEventListener("click", () => {
    modal.style.display = "none"; 
    document.body.classList.remove("non-scroll");
});
}

//form thay đổi thông tin nhà hàng
const openInfoForm = document.getElementById("changeInfoBtn");
const infoForm = document.getElementById("change_info_container");
// mở form thay đổi thông tin
if(openInfoForm){
openInfoForm.addEventListener("click", () => {
    infoForm.style.display = "block"; 
    document.body.classList.add("non-scroll"); // chặn scroll
});
// đóng form thay đổi thông tin
const closeInfoForm = document.getElementById("closeInfoProduct");
closeInfoForm.addEventListener("click", () => {
    infoForm.style.display = "none"; 
    document.body.classList.remove("non-scroll");
});
}
//Modal xóa tài khoản nhà hàng
const openOrderForm = document.getElementById("ordersInfoBtn");
const orderForm = document.getElementById("order_info_container");
// Mở modal
if(openOrderForm){
    openOrderForm.addEventListener("click", () => {
    orderForm.style.display = "block"; 
    document.body.classList.add("non-scroll");
});
// Đóng modal
const closeOrderForm = document.getElementById("closeOrderBtn");
closeOrderForm.addEventListener("click", () => {
    orderForm.style.display = "none"
    document.body.classList.remove("non-scroll");
});
}
//Modal đăng xuất
const openLogOut = document.getElementById("openLogOutBtn");
const lgoutForm = document.getElementById("logout_container");
// mở modal đăng xuất
if(openLogOut){
openLogOut.addEventListener("click", () => {
    lgoutForm.style.display = "block"; 
    document.body.classList.add("non-scroll");
});
// đóng modal đăng xuất
const closeLogOut = document.getElementById("closeLogOutBtn");
closeLogOut.addEventListener("click", () => {
    lgoutForm.style.display = "none"; 
    document.body.classList.remove("non-scroll");
});
}

// Modal xóa sản phẩm
const delProductButtons = document.querySelectorAll(".delProductBtn");
delProductButtons.forEach((button) => {
    button.addEventListener("click", () => {
        const productId = button.getAttribute("data-productid");
        const modal = document.getElementById(`del_product_container_${productId}`);
        if (modal) {
            modal.style.display = "block"; 
            document.body.classList.add("non-scroll");
        }
    });
});

// đóng modal xóa sản phẩm
const closeDelProButtons = document.querySelectorAll(".closeDelProBtn");
closeDelProButtons.forEach((button) => {
    button.addEventListener("click", () => {
        const productId = button.getAttribute("data-productid");
        const modal = document.getElementById(`del_product_container_${productId}`);
        if (modal) {
            modal.style.display = "none"; 
            document.body.classList.remove("non-scroll");
        }
    });
});

//form thay đổi thông tin sản phẩm
document.addEventListener("DOMContentLoaded", function () {
    //mở form
    const editProductButtons = document.querySelectorAll(".editProductBtn");
    editProductButtons.forEach((button) => {
        button.addEventListener("click", () => {
            const productId = button.dataset.productid; 
            const modal = document.getElementById(`editProContainer_${productId}`);
            if (modal) {
                modal.style.display = "block";
                document.body.classList.add("non-scroll");
            }
        });
    });
    //đóng form
    const closeEditForms = document.querySelectorAll(".close_edit_form");
    closeEditForms.forEach((button) => {
        button.addEventListener("click", () => {
            const productId = button.dataset.productid;
            const modal = document.getElementById(`editProContainer_${productId}`);
            if (modal) {
                modal.style.display = "none";
                document.body.classList.remove("non-scroll");
            }
        });
    });
});


//tạo đơn vị vnđ
document.addEventListener('DOMContentLoaded', function() {
const priceInput = document.getElementById('pro_price');
if(priceInput){
priceInput.addEventListener('input', function() {
    this.value = this.value.replace(/[^\d]/g, '');
    if (this.value === '') {
        this.value = '';
        return;
    }
    this.value = addThousandsSeparator(this.value);
});
}
function addThousandsSeparator(value) {
    const parts = value.split('.');
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    return parts.join('.');
}
});

//cơ chế thay đổi ảnh, trình bày ảnh 
function previewImage(input, imgId) {
    const imgPreview = document.getElementById(imgId);
    const file = input.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imgPreview.src = e.target.result;
        };
        reader.readAsDataURL(file);
    } else {
        imgPreview.src = '#';
    }
}
function clearImagePreviews() {
    const imgPreviews = document.querySelectorAll('.uploaded-img');

    imgPreviews.forEach((img) => {
        img.src = '#';
    });
    console.log('Image previews cleared.');
}

//validations cho form thêm sản phẩm
document.addEventListener("DOMContentLoaded", function () {
    const addform = document.getElementById("addProductForm");
    const productName = document.getElementById("pro_name");
    const productDescription = document.getElementById("pro_description"); // Add this line
    const productPrice = document.getElementById("pro_price");
    const productQuantity = document.getElementById("pro_quantity");
    const productNameError = document.getElementById("nameFail");
    const productDesError = document.getElementById("descriptionFail");
    const productPriceError = document.getElementById("priceFail");
    const productQuantityError = document.getElementById("quantityFail");

    if(addform){
    addform.addEventListener("submit", function (event) {
        let valid = true;
        if (productName.value.trim() === "" || productName.value.length < 4) {
            displayError(productNameError, "Tên sản phẩm phải ít nhất 4 ký tự");
            valid = false;
            event.preventDefault(); // Prevent form submission
        } else {
            hideError(productNameError);
        }
        if (productDescription.value.trim().length < 20 || /\d/.test(productDescription.value)) {
            displayError(productDesError, "  Có ít nhất 20 chữ và không được nhập số");
            valid = false;
            event.preventDefault(); // Prevent form submission
        } else {
            hideError(productDesError);
        }
        if (productPrice.value.trim().length < 4 || !/^\d{1,3}(,\d{3})*(\.\d+)?$/.test(productPrice.value.trim())) {
            displayError(productPriceError, "Giá sản phẩm không đúng yêu cầu. Ex: 1.000");
            valid = false;
            event.preventDefault(); // Prevent form submission
        } else {
            hideError(productPriceError);
        }        
        if (productQuantity.value.trim() === "" || isNaN(productQuantity.value.trim()) || 
        parseInt(productQuantity.value.trim()) < 1 || parseInt(productQuantity.value.trim()) > 999) {
        displayError(productQuantityError, "Số lượng sản phẩm phải là từ 1 đến 999");
        valid = false;
        event.preventDefault(); 
        } else {
        hideError(productQuantityError);
        }
        if (!valid) {
            event.preventDefault();
        }
    });
}
    // lỗi nếu fail validations
    function displayError(errorElement, errorMessage) {
        errorElement.textContent = errorMessage;
        errorElement.style.opacity = "1";
        errorElement.style.bottom = "-26px";

        setTimeout(function () {
            hideError(errorElement);
        }, 4200);
    }
    //ẩn lỗi
    function hideError(errorElement) {
        errorElement.textContent = "";
        errorElement.style.opacity = "0";
        errorElement.style.bottom = "-20px";
    }
});



document.addEventListener("DOMContentLoaded", function () {
    const infoForm = document.getElementById("changeInfoForm");
    const newResName = document.getElementById("res_name");
    const newEmail = document.getElementById("res_email");
    const newDes = document.getElementById("res_description");
    const newPhone = document.getElementById("res_phone");
    const restaurantNameError = document.getElementById("resNameError");
    const emailError = document.getElementById("resEmailError");
    const descriptionError = document.getElementById("resDesError");
    const phoneError = document.getElementById("resPhoneError");

    if(infoForm){
    infoForm.addEventListener("submit", function (event) {
        let valid = true;
        if (newResName.value.trim() === "" || newResName.value.length < 4) {
            displayError(restaurantNameError, "Tên nhà hàng phải ít nhất 4 ký tự");
            valid = false;
        } else {
            hideError(restaurantNameError);
        }
        if (newPhone.value.trim().length !== 10 || isNaN(newPhone.value.trim())) {
            displayError(phoneError, "SĐT phải là 10 chữ số");
            valid = false;
        } else {
            hideError(phoneError);
        }
        if (newDes.value.trim().length < 10 || /\d/.test(newDes.value)) {
            displayError(descriptionError, "ít nhất 10 chữ và không được nhập số");
            valid = false;
        } else {
            hideError(descriptionError);
        }
        if (!validateEmail(newEmail.value)) {
            displayError(emailError, "Email không hợp lệ. EX: example@.com");
            valid = false;
        } else {
            hideError(emailError);
        }

        if (!valid) {
            event.preventDefault(); 
        }
    });

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
}
});



// Check trùng file ảnh
function checkForDuplicateNames() {
    const form = document.getElementById("addProductForm");
    const imgInputs = form.querySelectorAll(".img-input");
    const errorDivs = form.querySelectorAll(".error-img-input");

    // Reset the error messages and start fade-out
    errorDivs.forEach((div) => {
        div.style.opacity = 0; 
        div.textContent = "";
    });

    // Reset the error messages
    for (let i = 0; i < imgInputs.length; i++) {
        imgInputs[i].setCustomValidity('');
    }

    let hasDuplicates = false;

    // Create a new Set for each submission
    const selectedFileNames = new Set();

    // Check for duplicates
    imgInputs.forEach((input, i) => {
        const file = input.files[0];
        if (file) {
            const fileName = file.name;
            if (selectedFileNames.has(fileName)) {
                const errorDiv = document.getElementById(`imgDup${i + 1}`);
                errorDiv.textContent = "File ảnh trùng tên.";
                setTimeout(() => {
                    errorDiv.style.opacity = 1; 
                }, 30); 

                setTimeout(() => {
                    errorDiv.style.opacity = 0;
                }, 4000); // 4 seconds delay
                hasDuplicates = true;
            } else {
                // Add the file name to the Set
                selectedFileNames.add(fileName);
            }
        }
    });

    if (hasDuplicates) {
        return;
    }
    form.submit();
}

// Add event listener to the form
const form = document.getElementById("addProductForm");
if(form){
form.addEventListener("submit", function (e) {
    e.preventDefault(); 
    checkForDuplicateNames(); 
});
}
document.addEventListener("DOMContentLoaded", function () {
    const addform = document.getElementById("addProductForm");

    if(addform){
    addform.addEventListener("submit", function (event) {
        event.preventDefault();
        clearErrorMessages();

        const productName = document.getElementById("pro_name");
        if (productName.value.trim() === "" || productName.value.length < 4) {
            displayError("Tên sản phẩm phải ít nhất 4 ký tự", "nameFail");
            return;
        }

        // Validate mô tả sản phẩm
        const productDescription = document.getElementById("pro_description");
        if (productDescription.value.trim().length < 20 || /\d/.test(productDescription.value)) {
            displayError("Có ít nhất 20 chữ và không được nhập số", "descriptionFail");
            return;
        }
        // Validate giá sp
        const productPrice = document.getElementById("pro_price");
        if (productPrice.value.trim().length < 4 || !/^\d{1,3}(,\d{3})*(\.\d+)?$/.test(productPrice.value.trim())) {
            displayError("Giá sản phẩm không đúng yêu cầu. Ex: 1.000", "priceFail");
            return;
        }
        // Validate số lượng sp
        const productQuantity = document.getElementById("pro_quantity");
        if (
            productQuantity.value.trim() === "" ||
            isNaN(productQuantity.value.trim()) ||
            parseInt(productQuantity.value.trim()) < 1 ||
            parseInt(productQuantity.value.trim()) > 999
        ) {
            displayError("Số lượng sản phẩm phải là từ 1 đến 999", "quantityFail");
            return;
        }
        addform.submit();
    });
    }
    // Function to display error messages
    function displayError(message, errorElementId) {
        const errorElement = document.getElementById(errorElementId);
        errorElement.textContent = message;
        errorElement.style.opacity = 1;
    }

    // Function to clear all error messages
    function clearErrorMessages() {
        const errorElements = document.querySelectorAll(".fail-validation");
        errorElements.forEach((errorElement) => {
            errorElement.textContent = "";
            errorElement.style.opacity = 0;
        });
    }
});


document.addEventListener("DOMContentLoaded", function () {
    const editForm = document.querySelector(".edit_pro_form");
    if(editForm){
        editForm.addEventListener("submit", function (event) {
            // Reset all error messages
            clearErrorMessages();

            const productName = document.getElementById("editName");
            if (productName.value.trim() === "" || productName.value.length < 4) {
                event.preventDefault(); // Prevent the form from submitting
                displayError("Tên sản phẩm phải ít nhất 4 ký tự", "failEditName");
                return;
            }

            // Validate product description
            const productDescription = document.getElementById("editDescription");
            if (productDescription.value.trim().length < 20 || /\d/.test(productDescription.value)) {
                event.preventDefault(); // Prevent the form from submitting
                displayError("Có ít nhất 20 chữ và không được nhập số", "failEditDes");
                return;
            }

            // Validate product price
            const productPrice = document.getElementById("editPrice");
            if (
                productPrice.value.trim().length < 4 ||
                !/^\d{1,3}(,\d{3})*(\.\d+)?$/.test(productPrice.value.trim())
            ) {
                event.preventDefault(); // Prevent the form from submitting
                displayError("Giá sản phẩm không đúng yêu cầu.", "failEditPrice");
                return;
            }

            // Validate product quantity
            const productQuantity = document.getElementById("editQuantity");
            if (
                productQuantity.value.trim() === "" ||
                isNaN(productQuantity.value.trim()) ||
                parseInt(productQuantity.value.trim()) < 1 ||
                parseInt(productQuantity.value.trim()) > 999
            ) {
                event.preventDefault(); // Prevent the form from submitting
                displayError("Số lượng sản phẩm phải là từ 1 đến 999", "failEditQuantity");
                return;
            }
        });
    }
});

// Function to display error messages with a slide-down effect
function displayError(message, errorElementId) {
    const errorElement = document.getElementById(errorElementId);
    errorElement.textContent = message;
    errorElement.style.opacity = 1;

    setTimeout(() => {
        errorElement.style.opacity = 0;
    }, 3000);
}

function clearErrorMessages() {
    const errorElements = document.querySelectorAll(".fail-valid");
    errorElements.forEach((errorElement) => {
        errorElement.textContent = "";
        errorElement.style.opacity = 0;
    });
}


function checkForDuplicateNames(event) {
    const form = document.getElementById("addProductForm");
    const imgInputs = form.querySelectorAll(".img-input");
    const errorDivs = form.querySelectorAll(".error-img-input");

    // Reset the error messages and start fade-out
    errorDivs.forEach((div) => {
        div.style.opacity = 0; // Start fade-out
        div.textContent = "";
    });

    // Reset the error messages
    for (let i = 0; i < imgInputs.length; i++) {
        imgInputs[i].setCustomValidity('');
    }

    let hasDuplicates = false;

    // Create a new Set for each submission
    const selectedFileNames = new Set();

    // Check for duplicates and blank inputs
    imgInputs.forEach((input, i) => {
        const file = input.files[0];
        if (!file) {
            const errorDiv = document.getElementById(`imgDup${i + 1}`);
            errorDiv.textContent = "Vui lòng chọn một tệp ảnh.";
            errorDiv.style.opacity = 1; // Show the error message
            hasDuplicates = true;
        } else {
            const fileName = file.name;
            if (selectedFileNames.has(fileName)) {
                const errorDiv = document.getElementById(`imgDup${i + 1}`);
                errorDiv.textContent = "File ảnh trùng tên.";

                // Apply fade-in effect
                setTimeout(() => {
                    errorDiv.style.opacity = 1; // Start fade-in
                }, 30); // Adjust the delay as needed

                // Start fade-out effect after about 4 seconds (4000ms)
                setTimeout(() => {
                    errorDiv.style.opacity = 0; // Start fade-out
                }, 4000); // 4 seconds delay
                hasDuplicates = true;
            } else {
                // Add the file name to the Set
                selectedFileNames.add(fileName);
            }
        }
    });

    if (hasDuplicates) {
        event.preventDefault(); // Prevent form submission if there are duplicates or blank inputs
    }
}

function openModal() {
    var modal = document.getElementById("myModal");
    modal.style.display = "block";
}

function closeModal() {
    var modal = document.getElementById("myModal");
    modal.style.display = "none";
}

function applyChange() {
    var inputElement = document.getElementById("product_id");
    var newPartInput = document.getElementById("newPart");
    var currentValue = inputElement.value;
    var parts = currentValue.split("-");
    
    if (parts.length === 2) {
        var prefix = parts[0];
        var userInput = newPartInput.value.trim(); // Trim any leading/trailing spaces
        
        if (userInput !== "") {
            inputElement.value = prefix + "-" + userInput;
            closeModal(); // Close the modal after applying the change
        }
    }
}

$(document).on('change', 'select', function() {
    var selectedStatus = $(this).val();
    var order_id = $(this).data('order-id');
    var product_id = $(this).data('product-id');
    
    $.ajax({
        url: 'update_order_status.php',
        type: 'POST',
        data: {
            order_id: order_id,
            product_id: product_id,
            new_status: selectedStatus
        },
        dataType: 'json',
        success: function(response) {
            var showToast = $("#toastMessage");
            showToast.show();
            if (response.status === 'success') {
                showToast.text(response.message);
                showToast.addClass("success-toast");
            } else {
                showToast.text(response.message);
                showToast.addClass("error-toast");
            }
            setTimeout(function() {
                showToast.hide();
                showToast.removeClass("success-toast error-toast"); // Remove styling classes
            }, 3500);
        },
        error: function(xhr, status, error) {
            alert("Error: " + status + " - " + error);
        }
    });
});

document.addEventListener('DOMContentLoaded', function() {
    var selectElements = document.querySelectorAll('select[name="order_status"]');

    selectElements.forEach(function(selectElement) {
        // Check if the initial value is "Finished" and disable the select
        if (selectElement.value === 'Finished') {
            selectElement.disabled = true;
            insertIcon(selectElement);
        }

        // Add change event listener
        selectElement.addEventListener('change', function() {
            if (this.value === 'Finished') {
                this.disabled = true;
                insertIcon(this);
            } else {
                removeIcon(this);
            }
        });
    });

    function insertIcon(selectElement) {
        var icon = document.createElement('i');
        icon.className = 'fa-solid fa-circle-check';
        icon.style.fontSize = '1.3rem'; 
        icon.style.color = '#16FF00';
        selectElement.insertAdjacentElement('afterend', icon);
    }

    function removeIcon(selectElement) {
        var icon = selectElement.nextElementSibling;
        if (icon && icon.classList.contains('fa-circle-check')) {
            icon.remove();
        }
    }
});











