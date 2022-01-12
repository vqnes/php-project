<?php

$content = <<<HTML
    <form action="/info/sources/store" class="mt-5" method="post" enctype="multipart/form-data">
        <div class="col-6 col-sm-4 mx-auto">
            <div class="mb-3">
                <label class="form-label" for="link-txt">Файл .txt</label>
                <div class="input-group">
                    <input id="link-txt" class="form-control" name="linkTxt" type="text" required>
                    <button id="txt-btn" class="btn btn-outline-secondary" type="button">
                        +
                    </button>
                    <input id="txt-file" class="visually-hidden" name="txtFile" type="file">
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label" for="link-csv">Файл .csv</label>
                <div class="input-group">
                    <input id="link-csv" class="form-control" name="linkCsv" type="text" required>
                        <button id="csv-btn" class="btn btn-outline-secondary" type="button">
                            +
                        </button>
                        <input id="csv-file" class="visually-hidden" name="csvFile" type="file">
                </div>
            </div>
            <div class="mb-4">
                    <label class="form-label" for="db-host">Хост БД</label>
                    <input id="db-host" class="form-control" name="dbHost" type="text" required>
            </div>
            <div class="mb-4">
                <label class="form-label" for="db-name">Имя БД</label>
                <input id="db-name" class="form-control" name="dbName" type="text" required>
            </div>
            <div class="mb-4">
                <label class="form-label" for="username">Имя пользователя</label>
                <input id="username" class="form-control" name="username" type="text" required>
            </div>
            <div class="mb-4">
                <label class="form-label" for="user-password">Пароль</label>
                <input id="user-password" class="form-control" name="userPassword" type="password" required>
            </div>
            <div class="d-flex justify-content-evenly mb-4">
                <button id="submit-form" type="submit" class="btn btn-primary col-5">Cохранить</button>
                <button type="reset" class="btn btn-danger col-5">Очистить форму</button>
            </div>
        </div>
        <input type="hidden" name="sourcesForm">
        <input type="hidden" name="csrfToken" value="$csrfToken">
    </form>
    <script src="/js/sourcesFormValidation.js"></script>
HTML;

$title = 'Регистрация источников';

require_once APP . '/views/layouts/default.php';
