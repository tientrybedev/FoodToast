function confirmDelete(button) {
    if (confirm("Bạn có chắc muốn xóa sản phẩm này khỏi danh sách yêu thích?")) {
        // If the user confirms, the form will be submitted
        button.closest('form').submit();
    }
}