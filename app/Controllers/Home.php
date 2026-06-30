<?php
namespace App\Controllers;

class Home extends AppController {

    public function index() {
        return view('welcome_message');
    }
}
