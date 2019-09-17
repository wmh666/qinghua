<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Model\Import;
use Illuminate\Http\Request;
use Excel;
class ImportController extends Controller {

    public function import(Request $requests){
        $file = $requests->file('file');
        $filePath = $file->getRealPath();
            excel::load($filePath, function($reader) {
            $data = $reader->all();
            dd($data);
        });
    }
}