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
        $this->dateTime = Carbon::now()->format('Y-m-d H:i:s');
        $this->currentTime = Carbon::now()->format('H:m:s');
    }

    public function clock(Request $request)
    {

        $currentUrl = $request->path();
        return view('clock.clockMain')
            ->with(
                compact('currentUrl')
            );
    }

    public function checkIfInOut()
    {
        dd($this->today->format('m/d/Y'), $this->currentTime);
    }



    public function HRHistory(Request $request)
    {

        $currentUrl = $request->path();

        $history = PunchRecord::get()->all();


        return view('history.historyMain')
            ->with(
                compact('history', 'currentUrl')
            );
    }

    public function test()
    {
        $test = DB::table('punchRecords as records')
            ->distinct()
            ->join('users', 'records.jjanID', '=', 'users.jjanID')
            ->select(
                'records.jjanID'
                , 'users.firstNm'
                , 'users.lastNm'
                , 'records.punchTime'
            )
            ->get()
            ->toArray();

        $test = DB::table('punchRecords as records')
            ->select(DB::raw('CURTIME()'))
            ->get()
        ;
        dd($test->all());

    }
}
