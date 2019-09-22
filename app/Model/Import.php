<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Import extends Model
{
    public static function listimport(){
        $list = DB::table('case')->paginate(10);
        return $list;
    }
}
