import '../css/floating-form.scss';

;(function($) {
    const form = $('#wpfeather-form');
    const toggleBtn = $('.wpfeather-toggler');
    const contactForm = $('.wpfeather-contact');
    const siteKey = $('#wpfeather-turnstile').data('sitekey');
    const resetBtn = $('#wpfeather-send-another');
    const turnstileWidget = '#wpfeather-turnstile';
    let token = '';

    if (typeof siteKey !== 'undefined') {
        window.onloadTurnstileCallback = () => {
            turnstile.render(turnstileWidget, {
                sitekey: siteKey,
                callback: (token) => {
                    token = token;

                    $('#wpfeather-form button[type="submit"]').removeAttr('disabled');
                },
            });
        };

        $( document ).ready(() => {
            turnstile.ready(onloadTurnstileCallback);
        });
    }

    // handle form submit
    form.on('submit', (e) => {
        e.preventDefault();

        const fullName = $('#wpfeather-form #full_name');
        const email = $('#wpfeather-form #email');
        const message = $('#wpfeather-form #message');
        const errors = [];

        if (fullName.val().trim() === '') {
            fullName.addClass('has-error');

            errors.push({
                type: 'required',
                field: fullName
            });
        } else {
            fullName.removeClass('has-error');
        }

        if (email.val().trim() === '') {
            email.addClass('has-error');

            errors.push({
                type: 'required',
                field: email
            });
        } else {
            email.removeClass('has-error');
        }

        if (message.val().trim() === '') {
            message.addClass('has-error');

            errors.push({
                type: 'required',
                field: message
            });
        } else {
            message.removeClass('has-error');
        }

        if (errors.length) {
            return false;
        }

        // show the loading spinner
        $('.lds-dual-ring').css('display', 'block');

        $.ajax({
            url: wpFeatherForm.ajaxUrl,
            type: 'POST',
            data: {
                action: wpFeatherForm.actionKey,
                fullName: fullName.val().trim(),
                email: email.val().trim(),
                message: message.val().trim(),
                nonce: wpFeatherForm.nonce,
                token: token
            },
            success: function (response) {
                if (response.success) {
                    $('#wpfeather-form button[type="submit"], #wpfeather-form .contact-input-group, #wpfeather-form .wpfeather-turnstile')
                        .css('display', 'none');
                    $('.contact-thanks-msg').css('display', 'block');
                    resetBtn.css('display', 'block');

                    // hide the loading spinner
                    $('.lds-dual-ring').css('display', 'none');
                } else {
                    errors.push({
                        type: 'required',
                        field: message
                    });
                }
            },
            error: function (err) {
                alert('Something went wrong');
                console.log(err.responseText);
            },
        });

    });

    // Handle toggler button
    toggleBtn.on('click', () => {
        contactForm.toggleClass('wpfeather-contact-open');
        toggleBtn.toggleClass('wpfeather-toggler-close');

        if ( $(contactForm).hasClass('wpfeather-contact-open') ) {}
    });

    // reset the form when clicking on send another button
    resetBtn.on('click', () => {
        $('.wpfeather-contact .contact-input-group')
            .css('display', 'flex');
        $('#wpfeather-form button[type="submit"], #wpfeather-form, #wpfeather-form .wpfeather-turnstile')
            .css('display', 'block');
        $('.contact-thanks-msg').css('display', 'none');

        if (typeof siteKey !== 'undefined') {
            turnstile.reset(turnstileWidget);
            $('#wpfeather-form button[type="submit"]').attr('disabled', 'disabled');
        }
    });

})(jQuery);