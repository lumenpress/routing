<?php

namespace LumenPress\Routing\Tests\Controllers;

use Laravel\Lumen\Routing\Controller;

class TestController extends Controller
{
    public function home()
    {
        return 'Hello World';
    }
}
