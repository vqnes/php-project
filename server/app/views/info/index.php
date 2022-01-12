<?php

$content = <<<HTML
    <div class="d-flex m-5">
        <div class="container col-4 border border-2">
            <div class="text-center fw-bold border-bottom mb-3">База данных</div>
HTML;

foreach ($dbInfo as $info) {
    $content .= "<div>{$info['id']}. {$info['information']}</div>";
}
$content .= '</div>';

$content .= '<div class="container col-4 border border-2 scrollspx">';
$content .= '<div class="text-center fw-bold border-bottom mb-3">Файл .txt</div>';
foreach ($filesInfo['txt'] as $txtInfo) {
    $content .= "<div>$txtInfo</div>";
}
$content .= '</div>';

$content .= '<div class="container col-4 border border-2">';
$content .= '<div class="text-center fw-bold border-bottom mb-3">Файл .csv</div>';
foreach ($filesInfo['csv'] as $csvInfo) {
    $content .= "<div>$csvInfo</div>";
}
$content .= '</div>';

$content .= '</div>';

$title = 'Info';

require_once APP . '/views/layouts/info.php';
