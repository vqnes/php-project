let txtBtn = document.getElementById('txt-btn');
let csvBtn = document.getElementById('csv-btn');

let linkTxt = document.getElementById('link-txt');
let linkCsv = document.getElementById('link-csv');

let files = {
    txt: document.getElementById('txt-file'),
    csv: document.getElementById('csv-file'),
};

files.txt.addEventListener('change', txtFileValid);
files.csv.addEventListener('change', csvFileValid);

txtBtn.addEventListener('click', txtBtnClick);
csvBtn.addEventListener('click', csvBtnClick);

let dbConfig = {
    host: document.getElementById('db-host'),
    name: document.getElementById('db-name'),
    username: document.getElementById('username'),
    userPassword: document.getElementById('user-password'),
};

for (let prop in dbConfig) {
    dbConfig[prop].addEventListener('input', dbInfoValid);
}

let btn = document.getElementById('submit-form');

btn.addEventListener('click', presendValidation);

function txtFileValid() {
    let file = files.txt.files[0];

    if (file.size > 5000000) {
        txtBtn.value = '';

        alert('Размер файла не должен превышать 5МБ!');
        linkTxt.focus();
        return;
    }

    if (file.type !== 'text/plain') {
        txtBtn.value = '';

        alert('Файл должен быть формата .txt');
        linkTxt.focus();
        return;
    }

    if (txtBtn.classList.contains('border-danger')) {
        txtBtn.classList.remove('border-danger');
    }

    if (txtBtn.classList.contains('shadow-danger')) {
        txtBtn.classList.remove('shadow-danger');
    }

    if (txtBtn.classList.contains('btn')) {
        txtBtn.classList.remove('btn');
    }

    txtBtn.classList.add('btn-close');
    txtBtn.textContent = '';

    linkTxt.value = file.name;
    linkTxt.disabled = true;
}

function txtBtnClick() {
    if (txtBtn.classList.contains('btn-close')) {
        txtBtn.value = '';
        txtBtn.textContent = '+';
        linkTxt.value = '';

        txtBtn.classList.remove('btn-close');

        if (!txtBtn.classList.contains('btn')) {
            txtBtn.classList.add('btn');
        }

        linkTxt.disabled = false;

        return;
    }

    files.txt.click();
}

function csvFileValid() {
    let file = files.csv.files[0];

    if (file.size > 5000000) {
        csvBtn.value = '';

        alert('Размер файла не должен превышать 5МБ!');
        linkCsv.focus();
        return;
    }

    if (file.type !== 'text/csv') {
        csvBtn.value = '';

        alert('Файл должен быть формата .csv');
        linkCsv.focus();
        return;
    }

    if (csvBtn.classList.contains('border-danger')) {
        csvBtn.classList.remove('border-danger');
    }

    if (csvBtn.classList.contains('shadow-danger')) {
        csvBtn.classList.remove('shadow-danger');
    }

    if (csvBtn.classList.contains('btn')) {
        csvBtn.classList.remove('btn');
    }

    csvBtn.classList.add('btn-close');
    csvBtn.textContent = '';

    linkCsv.value = file.name;
    linkCsv.disabled = true;
}

function csvBtnClick() {
    if (csvBtn.classList.contains('btn-close')) {
        csvBtn.value = '';
        csvBtn.textContent = '+';
        linkCsv.value = '';

        csvBtn.classList.remove('btn-close');

        if (!csvBtn.classList.contains('btn')) {
            csvBtn.classList.add('btn');
        }

        linkCsv.disabled = false;

        return;
    }

    files.csv.click();
}

function dbInfoValid() {
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

function presendValidation(event) {
    let inputs = document.querySelectorAll('form input');

    for (let input of inputs) {
        if (input.classList.contains('border-danger')) {
            event.preventDefault()
            input.focus();
        }
    }
}

function isValidURL(str) {
    let a = document.createElement('a');
    a.href = str;
    return (a.host && a.host != window.location.host);
}
