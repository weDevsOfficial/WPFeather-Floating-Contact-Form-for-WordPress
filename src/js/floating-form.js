import '../css/floating-form.scss';

;(function($) {
    const form = $('#wpfeather-form');
    const toggleBtn = $('.wpfeather-toggler');
    const contactForm = $('.wpfeather-contact');
    const siteKey = $('.cf-turnstile').data('sitekey');
    // const siteKey = '0x4AAAAAAADQdgiADHUielUbUBtzJ4raQXk';

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

        $.ajax({
            url: wpFeatherForm.ajaxUrl,
            type: 'POST',
            data: {
                action: wpFeatherForm.actionKey,
                fullName: fullName.val().trim(),
                email: email.val().trim(),
                message: message.val().trim(),
                nonce: wpFeatherForm.nonce
            },
            success: function (response) {
                if (response.success) {
                    $('#wpfeather-form button, #wpfeather-form .contact-input-group').css('display', 'none');
                    $('.contact-thanks-msg').css('display', 'block');
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


    // if (typeof turnstile === 'undefined') {
    //     return;
    // }
    //
    // turnstile.render('#wpfeather-form', {
    //     sitekey: siteKey,
    //     callback: function(token) {
    //         console.log(`Challenge Success ${token}`);
    //     },
    // });
    //
    // // if using synchronous loading, will be called once the DOM is ready
    // turnstile.ready(onloadTurnstileCallback);

})(jQuery);