document.addEventListener('DOMContentLoaded', function() {
    var forms = document.querySelectorAll('.ajax-form');

    forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            var formData = new FormData(form);
            var xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);

            xhr.onload = function () {
                if (xhr.status === 200) {
                    alert(xhr.responseText);
                } else {
                    alert('Request failed. Status: ' + xhr.status);
                }
            };

            xhr.send(formData);
        });
    });
});

