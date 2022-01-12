<?php

$content = <<<HTML
    <form action="/info/insert" class="mt-5" method="post">
        <div class="col-6 col-sm-4 mx-auto">
            <div class="mb-3">
                <label class="form-label" for="txt-info">Файл .txt</label>
                <div class="input-group mb-3">
                    <input id="txt-info" class="form-control" name="txtInfo" type="text">
                    <div class="input-group-text">
                        <input class="form-check-input mt-0" type="checkbox" name="isTxt" value="1" checked>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label" for="csv-info">Файл .csv</label>
                <div class="input-group mb-3">
                    <input id="csv-info" class="form-control" name="csvInfo" type="text">
                    <div class="input-group-text">
                        <input class="form-check-input mt-0" type="checkbox" name="isCsv" value="1" checked>
                    </div>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label" for="db-info">База данных</label>
                <div class="input-group mb-3">
                    <input id="db-info" class="form-control" name="dbInfo" type="text">
                    <div class="input-group-text">
                        <input class="form-check-input mt-0" type="checkbox" name="isDb" value="1" checked>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-evenly mb-4">
                <button id="submit-form" type="submit" class="btn btn-primary col-5">Записать</button>
                <button type="reset" class="btn btn-danger col-5">Очистить форму</button>
            </div>
        </div>
        <input type="hidden" name="addInfoForm">
        <input type="hidden" name="csrfToken" value="$csrfToken">
    </form>
HTML;

$title = 'Info | Add';

require_once APP . '/views/layouts/infoAdd.php';
