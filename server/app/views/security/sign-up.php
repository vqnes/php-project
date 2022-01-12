<?php

$content = <<<HTML
    <form action="/security/store" class="mt-5" method="post">
        <div class="col-6 col-sm-4 mx-auto">
            <div class="mb-3">
                <label class="form-label" for="username">Имя</label>
                <input id="username" class="form-control" name="username" type="text" placeholder="Введите Ваше имя" required>
            </div>
            <div class="mb-4">
                <label class="form-label" for="user-password">Пароль</label>
                <input id="user-password" class="form-control" name="userPassword" type="password" placeholder="Введите Ваш пароль" required>
            </div>
            <div class="d-flex justify-content-evenly mb-4">
                <button id="submit-form" type="submit" class="btn btn-primary col-5">Зарегестрироваться</button>
                <button type="reset" class="btn btn-danger col-5">Очистить форму</button>
            </div>
            <div class="text-center">
                <span>Есть аккаунт? </span>
                <a href="/sign-in">Войдите!</a>
            </div>
        </div>
        <input type="hidden" name="signUpForm">
        <input type="hidden" name="csrfToken" value="$csrfToken">
    </form>
HTML;
$title = 'Sign Up';

require_once APP . '/views/layouts/sign.php';
