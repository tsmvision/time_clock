<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\PunchRecord;
use Illuminate\Support\Facades\Auth;

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
        // I think I found solution for combining several query results to single array.
       $a = [];
       $a[] = [ 'jjanID' => 'namjoong',
                'firstNm' => 'Luke',
                'lastNm' => 'Lee',
                'counting' => 2];
       $a[]= [ 'jjanID' => 'jane',
                'firstNm' => 'Jane',
                'lastNm' => 'Amanda',
                'counting' => 0];

       $a = collect($a)
            ->where('jjanID','namjoong')
           ->toArray();
            ;

        //$a['0'] += ['merong' => 'true'] ;
        $a['0']['counting'] += 5;

        $a = collect($a);
       dd($a);
     //  $a = $a->merge(['gender' => 'm']);

       dd($a);

    }
}
