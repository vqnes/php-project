<?php

$content = <<<HTML
    <h1>$code</h1>
    <h1>$message</h1>
HTML;

require_once APP . '/views/layouts/http-error.php';
