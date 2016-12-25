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

        $list = DB::table('punchRecords as records ')
            ->join('users', 'records.jjanID', '=', 'users.jjanID')
            ->distinct()
            ->select(
                'records.id'
                ,'records.jjanID'
                , 'users.firstNm'
                , 'users.lastNm'
                , 'records.clockTime'

            );

        if ($getSearchPeriod === null || $getSearchPeriod === 'today') {
            $list = $list
                ->whereRaw("DAY(records.clockTime) = $day")
                ->whereRaw("MONTH(records.clockTime) = $month")
                ->whereRaw("YEAR(records.clockTime) = $year");

        } elseif ($getSearchPeriod === 'thisMonth') {
            $list = $list
                ->whereRaw("MONTH(records.clockTime) = $month")
                ->whereRaw("YEAR(records.clockTime) = $year");

        } elseif ($getSearchPeriod === 'lastMonth') {
            $list = $list
                ->whereRaw("MONTH(records.clockTime) = $lastMonth")
                ->whereRaw("YEAR(records.clockTime) = $year");

        } elseif ($getSearchPeriod === 'customPeriod') {

        }

        $list = $list
            ->orderBy('records.clockTime', 'DESC')
            ->orderBy('records.id', 'DESC')
            ->paginate(15);



//        $startTime = Carbon::parse($this->start_time);
//        $finishTime = Carbon::parse($this->finish_time);
//        $finishTime->diff($startTime)->format('%H:%i');


        return view('hours.hourMain')
            ->with(compact(
                    'list'
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
