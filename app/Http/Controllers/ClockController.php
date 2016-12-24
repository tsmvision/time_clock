<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\PunchRecord;

class clockController extends Controller
{
    public $dateTime;
    public $currentTime;

    public function __construct()
    {
        $this->dateTime = Carbon::now()->format('Ymd');
        $this->currentTime = Carbon::now()->format('H:m:s');
    }

    public function clock()
    {
        return view('clock.clockMain');
    }

    public function checkIfInOut()
    {
        dd($this->today->format('m/d/Y'),$this->currentTime);
    }

    public function punchNow()
    {



        $user = new PunchRecord;

        $user = PunchRecord::get()->all();

        dd($user);

        $user->jjanID = 'namjoong';
        $user->clockTime = $this->dateTime;

        $user->save();

        dd('1');

        return view('clock.clockMain');

    }
}
