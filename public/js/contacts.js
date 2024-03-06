document.getElementById('contactForm').addEventListener('submit', function (event) {
    event.preventDefault();

    const submitButton = this.querySelector('button[type="submit"]');
    const successMessage = document.querySelector('.success');
    const errorMessage = document.querySelector('.error-message');

    submitButton.disabled = true; // Disable the button to prevent multiple submissions

    const formData = new FormData(this);
    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(json => {
                throw new Error(json.error || 'Server responded with an error');
            });
        }
        return response.json();
    })
    .then(data => {
        successMessage.textContent = data.message;
        successMessage.style.display = 'block';
        errorMessage.style.display = 'none'; // Hide error message

        // Hide the success message after 5 seconds
        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 5000);

        // Reset form fields except email
        document.getElementById('title').value = '';
        document.getElementById('message').value = '';
    })
    .catch(error => {
        console.error('Error:', error);
        errorMessage.textContent = error.message;
        errorMessage.style.display = 'block';
        successMessage.style.display = 'none'; // Hide success message

        // Hide the error message after 5 seconds
        setTimeout(() => {
            errorMessage.style.display = 'none';
        }, 5000);
    })
    .finally(() => {
        submitButton.disabled = false; // Re-enable the button after the request is completed
    });
});
