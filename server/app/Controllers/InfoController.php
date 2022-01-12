<?php

namespace app\Controllers;

use app\Models\Info;
use core\Auth\AuthorizeInterface;
use core\FileSystem\FileSystemInterface;

class InfoController extends AppController
{
    protected FileSystemInterface $fileSystem;
    protected Info $infoModel;

    public function __construct(
        FileSystemInterface $fileSystem,
        AuthorizeInterface $authorize,
        Info $infoModel
    ) {
        parent::__construct($authorize);
        $this->fileSystem = $fileSystem;
        $this->infoModel = $infoModel;
    }

    public function indexAction(): void
    {
        if (!$this->authorize->isLoggedIn()) {
            $this->failedAccess();

            return;
        }

        if (!$this->checkSources()) {
            header('Location: \info\sources');
            die();
        }

        $dbInfo = $this->infoModel->findAll();
        $filesInfo = [];

        foreach ($_SESSION['sources'] as $file => $source) {
            if ($source === 'link') {
                $fileSources = $this->fileSystem->getRequire(ROOT . 'config/configFiles.php');
                $filesInfo[$file] = $this->fileSystem->lines($fileSources[$file]);
            } elseif ($source === 'file') {
                $filesInfo[$file] = $this->fileSystem->lines(WWW . '/files/File.' . $file);
            }
        }

        $this->setVars(compact('dbInfo', 'filesInfo'));
        $this->getView('index');
    }

    public function sourcesAction(): void
    {
        if (!$this->authorize->isLoggedIn()) {
            $this->failedAccess();

            return;
        }

        if ($this->checkSources()) {
            header('Location: \info');
            die();
        }

        $csrfToken = $this->createCsrf();

        $_SESSION['csrfToken'] = $csrfToken;

        $this->setVars(compact('csrfToken'));
        $this->getView('sources');
    }

    public function storeAction(): void
    {
        if (!$this->authorize->isLoggedIn()) {
            header('Location: \info\sources');
            die();
        }

        if (!$this->checkCsrf()) {
            $this->failedCsrf();

            return;
        }

        if (
            !isset($_POST['sourcesForm']) ||
            !isset($_POST['username']) ||
            !isset($_POST['userPassword']) ||
            !isset($_POST['dbHost']) ||
            !isset($_POST['dbName']) ||
            (!isset($_POST['linkTxt']) && !is_uploaded_file($_FILES['txtFile']['tmp_name'])) ||
            (!isset($_POST['linkCsv']) && !is_uploaded_file($_FILES['csvFile']['tmp_name']))
        ) {
            header('Refresh: 3; url=\info\sources');

            $this->setVars([
                'title'   => 'Ошибка',
                'message' => 'Сохранение не удалось!',
            ]);
            $this->getView('sign-result', 'security');
            die();
        }

        if (
            isset($_POST['linkTxt']) &&
            !$this->fileSystem->exists($_POST['linkTxt'])
        ) {
            header('Refresh: 3; url=\info\sources');

            $this->setVars([
                'title'   => 'Ошибка',
                'message' => 'Сохранение не удалось! Некорректная ссылка на файл .txt',
            ]);
            $this->getView('sign-result', 'security');
            die();
        }

        if (
            isset($_POST['linkCsv']) &&
            !$this->fileSystem->exists($_POST['linkCsv'])
        ) {
            header('Refresh: 3; url=\info\sources');

            $this->setVars([
                'title'   => 'Ошибка',
                'message' => 'Сохранение не удалось! Некорректная ссылка на файл .csv',
            ]);
            $this->getView('sign-result', 'security');
            die();
        }

        $path = ROOT . '/config/configDb.php';
        $dbParams = $this->fileSystem->getRequire($path);
        $userDbParams = [
            'dsn'      => 'mysql:host=' . $_POST['dbHost'] . ';dbname=' . $_POST['dbName'],
            'user'     => $_POST['username'],
            'password' => $_POST['userPassword'],
        ];

        foreach ($dbParams as $key => $dbParam) {
            if ($userDbParams[$key] !== $dbParam) {
                header('Refresh: 3; url=\info\sources');

                $this->setVars([
                    'title'   => 'Ошибка',
                    'message' => 'Сохранение не удалось! Некорректные данные для подключения к БД',
                ]);
                $this->getView('sign-result', 'security');
                die();
            }
        }

        if (
            !isset($_POST['linkTxt']) &&
            $_FILES['txtFile']['type'] === 'text/plain' &&
            $_FILES['txtFile']['size'] < 5000000
        ) {
            $path = WWW . '/files/File.txt';

            move_uploaded_file($_FILES['txtFile']['tmp_name'], $path);

            $_SESSION['sources']['txt'] = 'file';
        }

        if (
            !isset($_POST['linkCsv']) &&
            $_FILES['csvFile']['type'] === 'text/csv' &&
            $_FILES['csvFile']['size'] < 5000000
        ) {
            $path = WWW . '/files/File.csv';

            move_uploaded_file($_FILES['csvFile']['tmp_name'], $path);
            $_SESSION['sources']['csv'] = 'file';
        }

        $path = ROOT . '/config/configFiles.php';

        $content = "<?php\n\nreturn [\r\t'txt' => '";
        if (isset($_POST['linkTxt'])) {
            $content .= $_POST['linkTxt'];
            $_SESSION['sources']['txt'] = 'link';
        }

        $content .= "',\r\t'csv' => '";
        if (isset($_POST['linkCsv'])) {
            $content .= $_POST['linkCsv'];
            $_SESSION['sources']['csv'] = 'link';
        }

        $content .= "',\r];\n";

        $this->fileSystem->put($path, $content);

        header('Location: \info');
        die();
    }

    public function addAction(): void
    {
        if (!$this->authorize->isLoggedIn()) {
            $this->failedAccess();

            return;
        }

        if (!$this->checkSources()) {
            header('Location: \info\sources');
            die();
        }

        $csrfToken = $this->createCsrf();

        $_SESSION['csrfToken'] = $csrfToken;

        $this->setVars(compact('csrfToken'));
        $this->getView('addInfo');
    }

    public function insertAction(): void
    {
        if (!$this->authorize->isLoggedIn()) {
            header('Location: \info\add');
            die();
        }

        if (!$this->checkSources()) {
            header('Location: \info\sources');
            die();
        }

        if (!$this->checkCsrf()) {
            $this->failedCsrf();

            return;
        }

        if (
            !isset($_POST['isTxt']) &&
            !isset($_POST['isCsv']) &&
            !isset($_POST['isDb'])
        ) {
            header('Location: \info');
            die();
        }

        if (
            !isset($_POST['addInfoForm']) ||
            !isset($_POST['txtInfo']) ||
            !isset($_POST['csvInfo']) ||
            !isset($_POST['dbInfo'])
        ) {
            header('Refresh: 3; url=\info\sources');

            $this->setVars([
                'title'   => 'Ошибка',
                'message' => 'Не все поля были отправлены!',
            ]);
            $this->getView('sign-result', 'security');
            die();
        }

        if (isset($_POST['isTxt']) && strlen($_POST['txtInfo']) !== 0) {
            if ($_SESSION['sources']['txt'] === 'link') {
                $fileSources = $this->fileSystem->getRequire(ROOT . 'config/configFiles.php');
                $this->fileSystem->append($fileSources['txt'], $_POST['txtInfo'] . "\n\r");
            } elseif ($_SESSION['sources']['txt'] === 'file') {
                $this->fileSystem->append(WWW . '/files/File.txt', $_POST['txtInfo'] . "\n\r");
            }
        }

        if (isset($_POST['isCsv']) && strlen($_POST['csvInfo']) !== 0) {
            if ($_SESSION['sources']['csv'] === 'link') {
                $fileSources = $this->fileSystem->getRequire(ROOT . 'config/configFiles.php');
                $this->fileSystem->append($fileSources['csv'], $_POST['csvInfo'] . "\n\r");
            } elseif ($_SESSION['sources']['csv'] === 'file') {
                $this->fileSystem->append(WWW . '/files/File.csv', $_POST['csvInfo'] . "\n\r");
            }
        }

        if (isset($_POST['isDb']) && strlen($_POST['dbInfo']) !== 0) {
            $this->infoModel->insertOne([$_POST['dbInfo']], ['information']);
        }

        header('Location: \info');
        die();
    }

    public function checkSources(): bool
    {
        $files = $this->fileSystem->getRequire(ROOT . '/config/configFiles.php');

        $existsSources = [];

        foreach ($files as $file) {
            if ($file !== null && strlen($file) !== 0) {
                $existsSources[] = true;
            }
        }

        $paths = [
            WWW . '/files/File.csv',
            WWW . '/files/File.txt',
        ];
        foreach ($paths as $path) {
            if ($this->fileSystem->exists($path)) {
                $existsSources[] = true;
            }
        }

        if (count($existsSources) < 2) {
            return false;
        }

        return true;
    }

    public function failedAccess(): void
    {
        http_response_code(403);

        $this->setVars([
            'title'   => 'Forbidden',
            'code'    => 403,
            'message' => 'Unauthorized',
        ]);
        $this->getView('http-error', 'error');
    }

    protected function checkCsrf(): bool
    {
        if (
            !isset($_POST['csrfToken']) ||
            !isset($_SESSION['csrfToken']) ||
            $_SESSION['csrfToken'] !== $_POST['csrfToken']
        ) {
            return false;
        }

        return true;
    }

    public function createCsrf(int $length = 32): string
    {
        if ($length > 32 || $length < 6) {
            $length = 32;
        }

        return bin2hex(random_bytes($length));
    }

    protected function failedCsrf(): void
    {
        http_response_code(419);

        $this->setVars([
            'title'   => 'Page Expired',
            'code'    => 419,
            'message' => 'Page Expired',
        ]);
        $this->getView('http-error', 'error');
    }
}
