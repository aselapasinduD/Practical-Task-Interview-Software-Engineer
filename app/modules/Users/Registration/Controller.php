<?php

namespace App\Modules\Users\Registration;

class Controller extends \Core\Controller{
    public function view(){
        return $this->views::render(__DIR__, ["name" => "Asela Pasindu"]);
    }
}