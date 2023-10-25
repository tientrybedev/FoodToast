const checkboxes = document.querySelectorAll('input[type="checkbox"]');

const showChoices = document.getElementById("showCategoryChoices");

checkboxes.forEach(checkbox => {
    checkbox.addEventListener('change', () => {
        const selectedChoices = [];
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selectedChoices.push(document.querySelector(`label[for="${checkbox.id}"]`).textContent);
            }
        });
        if(selectedChoices.length>0){
            showChoices.textContent = `${selectedChoices.join(', ')}`;
        }else{
            showChoices.textContent = 'Bạn chưa chọn gì';
        }
    });
});

$(document).ready(function () {
    $('#advancedSearchForm').submit(function (e) {
        e.preventDefault(); 
        const selectedMeals = [];
        $('input[name="meals[]"]:checked').each(function () {
            selectedMeals.push($(this).val());
        });

        if (selectedMeals.length === 0) {
            $('#validationMessage').text('Hãy chọn ít nhất 1 phân loại');
        } else {
            $('#validationMessage').text('');
            $.ajax({
                type: 'POST',
                url: 'advance_search_process.php',
                data: { meals: selectedMeals },
                success: function (data) {
                    $('.filter-result').html(data);
                }
            });
        }
    });
});

$(document).ready(function () {
    $('input[name="meals[]"]').click(function () {
        $(this).parent().toggleClass('selected');
    });
});

