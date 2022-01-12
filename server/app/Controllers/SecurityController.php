<?php

namespace app\Controllers;

use core\Registration\RegistrationInterface;
use core\Auth\AuthorizeInterface;
use core\Auth\AuthInterface;
use core\FileSystem\FileSystemInterface;

class SecurityController extends AppController
{
    protected RegistrationInterface $registration;
    protected AuthInterface $auth;
    protected FileSystemInterface $fileSystem;

    public function __construct(
        RegistrationInterface $registration,
        AuthorizeInterface $authorize,
        AuthInterface $auth,
        FileSystemInterface $fileSystem
    ) {
        parent::__construct($authorize);
        $this->registration = $registration;
        $this->auth = $auth;
        $this->fileSystem = $fileSystem;
    }

    public function signInAction()
    {
        if ($this->authorize->isLoggedIn()) {
            header('Location: \info\sources');
            die();
        }

        $csrfToken = $this->createCsrf();

        $_SESSION['csrfToken'] = $csrfToken;

        $this->setVars(compact('csrfToken'));
        $this->getView('sign-in');
    }

    public function signUpAction()
    {
        if ($this->authorize->isLoggedIn()) {
            header('Location: \info\sources');
            die();
        }

        $csrfToken = $this->createCsrf();

        $_SESSION['csrfToken'] = $csrfToken;

        $this->setVars(compact('csrfToken'));
        $this->getView('sign-up');
    }

    public function storeAction()
    {
        if ($this->authorize->isLoggedIn()) {
            header('Location: \info\sources');
            die();
        }

        if (!$this->checkCsrf()) {
            debug($_SESSION);
            debug($_POST);
            $this->failedCsrf();

            return;
        }

        if (
            !isset($_POST['signUpForm']) ||
            !isset($_POST['userPassword']) ||
            !isset($_POST['username']) ||
            !$this->registration->store([
                'username' => $_POST['username'],
                'password' => $_POST['userPassword'],
            ])
        ) {
            header('Refresh: 3; url=/sign-up');
            $this->setVars([
                'title' => 'Ошибка!',
                'message' => 'Регистрация не удалась :(',
            ]);
            $this->getView('sign-result');
            die();
        }

        header('Refresh: 3; url=/sign-in');
        $this->setVars([
            'title' => 'Успешно!',
            'message' => 'Регистрация прошла успешно!',
        ]);
        $this->getView('sign-result');
        die();
    }

    public function authenticateAction()
    {
        if ($this->authorize->isLoggedIn()) {
            header('Location: \info\sources');
            die();
        }

        if (!$this->checkCsrf()) {
            $this->failedCsrf();

            return;
        }

        if (
            isset($_POST['signInForm']) &&
            isset($_POST['username']) &&
            isset($_POST['userPassword']) &&
            $this->authorize->getAuthenticate()->login([
                'username' => $_POST['username'],
                'password' => $_POST['userPassword'],
            ])
        ) {
            header('Location: \info\sources');
            die();
        }

        header('Refresh: 3; url=/sign-in');

        $this->setVars([
            'title' => 'Ошибка!',
            'message' => 'Вход не удался :(',
        ]);
        $this->getView('sign-result');
        die();
    }

    public function logoutAction()
    {
        if ($this->authorize->isLoggedIn()) {
            $this->removeSources();
            unset($_SESSION['sources']);

            $this->auth->forget();
        }

        header('Location: \sign-in');
        die();
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

    protected function removeSources(): void
    {
        $path = ROOT . '/config/configFiles.php';
        $content = "<?php\n\nreturn [\r\t'txt' => null,\r\t'csv' => null,\r];\n";

        $this->fileSystem->put($path, $content);

        $this->fileSystem->put($path, $content);

        $path = WWW . '/files/File.txt';
        if ($this->fileSystem->exists($path)) {
            $this->fileSystem->delete($path);
        }

        $path = WWW . '/files/File.csv';
        if ($this->fileSystem->exists($path)) {
            $this->fileSystem->delete($path);
        }
    }
}
