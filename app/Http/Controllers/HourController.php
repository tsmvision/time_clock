<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\PunchRecord;

class HourController extends Controller
{
    public $dateTime;
    public $currentTime;

    public function __construct()
    {
        $this->dateTime = Carbon::now()->format('Y-m-d H:i:s');
        $this->currentTime = Carbon::now()->format('H:m:s');
    }


    public function display(Request $request)
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
                'records.id'
                ,'records.jjanID'
                , 'users.firstNm'
                , 'users.lastNm'
                , DB::raw("SUM(records.clockTime) as workingHours")

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
            ->paginate(15);
        //    ->toArray();

        //dd($history);

        return view('hours.hourMain')
            ->with(compact(
                    'history'
                    , 'currentUrl'
                    , 'getSearchPeriod'
                    , 'getMemberName'
                )
            );

    }

    public function update($id)
    {

    }


    public function delete($id)
    {

        $punchRecord = PunchRecord::find($id);

        $punchRecord->delete();


        return redirect('/history/list')->with('message', 'deleted!');
    }


}
