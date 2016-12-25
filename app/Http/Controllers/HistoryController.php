<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\PunchRecord;

class HistoryController extends Controller
{
    public $dateTime;
    public $currentTime;

    public function __construct()
    {
        $this->dateTime = Carbon::now()->format('Y-m-d H:i:s');
        $this->currentTime = Carbon::now()->format('H:m:s');
    }

    public function punchNow(Request $request)
    {
        $request->flash();
        $currentUrl = $request->path();

        $user = new PunchRecord;

        $user->jjanID = 'namjoong';
        $user->clockTime = $this->dateTime;

        $user->save();


        return view('clock.clockMain', compact(
                                        'dateTime'
                                        ,'currentUrl'
                                        )
        );

    }

    public function read(Request $request)
    {
        $request->flash();
        $currentUrl = $request->path();
        $getSearchPeriod = $request->input('getSearchPeriod');
        $getMemberName = $request->input('getMemberName');

        $day = Carbon::now()->format('d');
        $month = Carbon::now()->format('m');
        $year = Carbon::now()->format('Y');
        $lastMonth = Carbon::now()->subMonth()->format('m');

        $history = DB::table('punchRecords as records ')
            ->join('users', 'records.jjanID', '=', 'users.jjanID')
            ->distinct()
            ->select(
                'records.jjanID'
                , 'users.firstNm'
                , 'users.lastNm'
                , 'records.clockTime'
            );

        if ($getSearchPeriod === null || $getSearchPeriod === 'today') {
            $history = $history
                ->whereRaw("DAY(records.clockTime) = $day")
                ->whereRaw("MONTH(records.clockTime) = $month")
                ->whereRaw("YEAR(records.clockTime) = $year");

        } elseif ($getSearchPeriod === 'thisMonth') {
            $history = $history
                ->whereRaw("MONTH(records.clockTime) = $month")
                ->whereRaw("YEAR(records.clockTime) = $year");

        } elseif ($getSearchPeriod === 'lastMonth') {
            $history = $history
                ->whereRaw("MONTH(records.clockTime) = $lastMonth")
                ->whereRaw("YEAR(records.clockTime) = $year");

        } elseif ($getSearchPeriod === 'customPeriod') {

        }

        $history = $history
            ->orderBy('records.clockTime', 'DESC')
            ->orderBy('records.id', 'DESC')
            ->get();
        //    ->toArray();

        //dd($history);

        return view('history.historyMain')
            ->with(compact(
                    'history'
                    , 'currentUrl'
                    , 'getSearchPeriod'
                    , 'getMemberName'
                )
            );

    }

    public function update()
    {

    }


    public function delete()
    {

    }


}
