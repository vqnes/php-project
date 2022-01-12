let username = document.getElementById('username');
let password = document.getElementById('user-password');
let submit = document.getElementById('submit-form');

username.addEventListener('input', usernameValidation);
password.addEventListener('input', passwordValidation);
submit.addEventListener('click', presendValidation);

function usernameValidation() {
    if (/[^\w]/.test(this.value)) {
        this.classList.add('border-danger');
        this.classList.add('shadow-danger');
        return;
    }

    if (this.classList.contains('border-danger')) {
        this.classList.remove('border-danger');
        this.classList.remove('shadow-danger');
    }
}

function passwordValidation() {
    if (/[^\w]/.test(this.value)) {
        this.classList.add('border-danger');
        this.classList.add('shadow-danger');
        return;
    }

    if (this.classList.contains('border-danger')) {
        this.classList.remove('border-danger');
        this.classList.remove('shadow-danger');
    }
}

function presendValidation(event)
{
    let inputs = document.querySelectorAll('form input');

    for (let input of inputs) {
        if (input.classList.contains('border-danger')) {
            event.preventDefault()
            input.focus();
        }
    }
}