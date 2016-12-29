<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use App\GeneralPurpose\GeneralPurpose;


class testController extends Controller
{
    use GeneralPurpose;

    public function test()
    {
        $a = DB::table('users');

       // dd($a->get()->all());

        $b = $this->getSql($a);

        dd($b);
    }
}
