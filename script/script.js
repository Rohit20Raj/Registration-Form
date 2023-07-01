document.getElementById("myForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent form submission

    var form = document.getElementsByClassName("form-detail");
    var formData = new FormData(form);

    // Make an AJAX request to submit the form data
    var xhr = new XMLHttpRequest();
    xhr.open("POST", form.action, true);
    xhr.onload = function () {
        if (xhr.status === 200) {
            alert("Form submitted successfully!");
            form.reset(); // Reset the form after successful submission
        } else {
            alert("Form submission failed. Please try again later.");
        }
    };
    xhr.send(formData);
});
