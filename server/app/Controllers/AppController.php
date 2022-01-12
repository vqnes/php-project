<?php

namespace app\Controllers;

use core\base\Controller;
use core\Auth\AuthorizeInterface;

class AppController extends Controller
{
    protected AuthorizeInterface $authorize;

    public function __construct(AuthorizeInterface $authorize)
    {
        parent::__construct();
        $this->authorize = $authorize;
    }
}
