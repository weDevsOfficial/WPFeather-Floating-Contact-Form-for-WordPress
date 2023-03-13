(function() {
    const form = document.querySelector('#form');
    const toggleBtn = document.querySelector('.toggler');
    const contactForm = document.querySelector('.contact');

    // handle form submit
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const fullName = document.querySelector('#full_name');
        const email = document.querySelector('#email');
        const message = document.querySelector('#message');

        if (fullName.value === '') {
            fullName.classList.add('has-error')
        } else {
            fullName.classList.remove('has-error')
        }

        if (email.value === '') {
            email.classList.add('has-error')
        } else {
            email.classList.remove('has-error')
        }

        if (message.value === '') {
            message.classList.add('has-error')
        } else {
            message.classList.remove('has-error')
        }
    })

    // Handle toggler button
    toggleBtn.addEventListener('click', () => {
        contactForm.classList.toggle('contact-open');
        toggleBtn.classList.toggle('toggler-close');
    })
})()