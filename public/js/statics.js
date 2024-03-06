document.addEventListener('DOMContentLoaded', function () {
    var faqItems = document.querySelectorAll('.faq-item');

    faqItems.forEach(function (item) {
        item.addEventListener('click', function () {
            this.classList.toggle('active');
        });
    });
});

