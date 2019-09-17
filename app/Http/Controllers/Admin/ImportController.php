<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\Import;
use Illuminate\Http\Request;
class ImportController extends Controller {

    public function import(){
        $list = Import::import();
        dd($list);
    }
}