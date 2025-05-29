<?php

namespace App\Modules\Admin\Dashboard;

class Controller extends \Core\Controller{
    public function view(){
        return $this->views::render(__DIR__, ["name" => "Asela Pasindu"]);
    }
}