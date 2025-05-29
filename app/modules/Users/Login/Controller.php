<?php

namespace App\Modules\Users\Login;

class Controller extends \Core\Controller{
    public function view(){
        return $this->views::render(__DIR__, ["name" => "Asela Pasindu"]);
    }
}