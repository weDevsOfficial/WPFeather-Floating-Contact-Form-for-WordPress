import '../css/floating-form.scss';

;(function($) {
    const form = document.querySelector('#wpfeather-form');
    const toggleBtn = document.querySelector('.toggler');
    const contactForm = document.querySelector('.contact');

    // handle form submit
    form.addEventListener('submit', (e) => {
        e.preventDefault();

        const fullName = document.querySelector('#wpfeather-form #full_name');
        const email = document.querySelector('#wpfeather-form #email');
        const message = document.querySelector('#wpfeather-form #message');
        const errors = [];

        if (fullName.value === '') {
            fullName.classList.add('has-error');

            errors.push({
                type: 'required',
                field: fullName
            });
        } else {
            fullName.classList.remove('has-error');
        }

        if (email.value === '') {
            email.classList.add('has-error');

            errors.push({
                type: 'required',
                field: email
            });
        } else {
            email.classList.remove('has-error');
        }

        if (message.value === '') {
            message.classList.add('has-error');

            errors.push({
                type: 'required',
                field: message
            });
        } else {
            message.classList.remove('has-error');
        }

        if (errors.length) {
            return false;
        }

        $.ajax({
            url: wpFeatherForm.ajaxUrl,
            type: 'POST',
            data: {
                action: wpFeatherForm.actionKey,
                fullName: fullName.value,
                email: email.value,
                message: message.value,
                nonce: wpFeatherForm.nonce
            },
            success: function (response) {
                if (response.success) {
                    $('#wpfeather-form button, #wpfeather-form .contact-input-group').css('display', 'none');
                    $('.contact-thanks-msg').css('display', 'block');
                }
            },
            error: function (err) {
                alert('Something went wrong');
                console.log(err.responseText);
            },
        });

    });

    // Handle toggler button
    toggleBtn.addEventListener('click', () => {
        contactForm.classList.toggle('contact-open');
        toggleBtn.classList.toggle('toggler-close');
    });
})(jQuery);