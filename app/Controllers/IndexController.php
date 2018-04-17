<?php
/**
 * Created by PhpStorm.
 * User: xiodine
 * Date: 2018-04-17
 * Time: 21:45
 */

namespace Controllers;

class IndexController
{
    public function index()
    {
        return view('index', ['hello' => 'world']);
    }
}