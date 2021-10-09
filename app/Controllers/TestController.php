<?php

namespace App\Controllers;

use App\Framework\Controller\Controller;
use App\Framework\Request\Request;

class TestController extends Controller
{
    public function index(): string
    {
        return "home";
    }
}
