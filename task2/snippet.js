
var typeSelect = document.querySelector('select[name="type_val"]');

if (typeSelect) {
    function updateFields() {
        var selectedValue = typeSelect.value;
        console.log("Выбранное значение:", selectedValue);
        var fields = document.querySelectorAll('input');
        fields.forEach(function(field) {
            if (field === typeSelect) {
                field.style.display = 'block';
                return;
            }

            if (field.name && field.name.includes(selectedValue)) {

                field.style.display = 'block';
                console.log("Показываю:", field.name);
            } else {

                field.style.display = 'none';
                console.log("Скрываю:", field.name);
            }
        });
    }

    updateFields();

    typeSelect.addEventListener('change', updateFields);
} else {
    console.error("Элемент с полем 'Тип' не найден.");
}
