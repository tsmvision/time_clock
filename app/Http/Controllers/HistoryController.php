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

    public function punchNow(Request $request, $punchType)
    {
        $request->flash();
        $currentUrl = $request->path();

        $user = new PunchRecord;

        $user->jjanID = 'namjoong';
        $user->punchTime = $this->dateTime;
        $user->punchType = $punchType;

        $user->save();


    //    return view('clock.clockMain', compact(
    //                                    'dateTime'
    //                                    ,'currentUrl'
    //                                    )
    //);

        $request->session()->flash('alert-success', 'Punch is successfully completed.');

         return redirect('clock')->with('message', 'Punch completed successfully!');



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
                , 'records.punchTime'
            );

       // dd($history->get()->all());

        if ($getSearchPeriod === null || $getSearchPeriod === 'today') {
            $history = $history
                ->whereRaw("DAY(records.punchTime) = $day")
                ->whereRaw("MONTH(records.punchTime) = $month")
                ->whereRaw("YEAR(records.punchTime) = $year");

        } elseif ($getSearchPeriod === 'thisMonth') {
            $history = $history
                ->whereRaw("MONTH(records.punchTime) = $month")
                ->whereRaw("YEAR(records.punchTime) = $year");

        } elseif ($getSearchPeriod === 'lastMonth') {
            $history = $history
                ->whereRaw("MONTH(records.punchTime) = $lastMonth")
                ->whereRaw("YEAR(records.punchTime) = $year");

        } elseif ($getSearchPeriod === 'customPeriod') {

        }

        $history = $history
            ->orderBy('records.punchTime', 'DESC')
            ->orderBy('records.id', 'DESC')
            ->paginate(15);
        //    ->toArray();

        //dd($history);

        if ($getSearchPeriod === null )
        {
            $getSearchPeriod = 'today';
        }


        return view('history.historyMain')
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


        return redirect('/history')->with('message', 'deleted!');
    }


}
