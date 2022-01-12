<?php

namespace app\Controllers;

class MainController extends AppController
{
    public function indexAction()
    {
        if (!$this->authorize->isLoggedIn()) {
            header('Location: \sign-in', true, 303);
            die();
        }

        header('Location: \info', true, 303);
        die();
    }
}
