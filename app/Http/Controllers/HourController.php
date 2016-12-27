<?php

namespace App\Http\Controllers;

use App\GeneralPurpose\GeneralPurpose;
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
        $this->date = Carbon::now()->format('Y-m-d');
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

        $startingDate = 0;
        $endingDate = 0;

        $users = DB::table('users')
            ->select(
                'users.jjanID'
                , 'users.firstNm'
                , 'users.lastNm'
            )
            ->get();

        // today calculation

        $startingDate = 0;
        $endingDate = 0;

        $jjanID = 'namjoong';

        // change the search period depending on the value of search menu interface.
        $getSearchPeriod = 'today';

        if ($getSearchPeriod === null || $getSearchPeriod === 'today') {

            $startingDate = Carbon::now()->format('Ymd');
            $endingDate = Carbon::now()->format('Ymd');

        } elseif ($getSearchPeriod === null || $getSearchPeriod === 'yesterday') {

            $startingDate = Carbon::now()->subDay()->format('Y-m-d');
            $endingDate = Carbon::now()->subDay()->format('Y-m-d');

        } elseif ($getSearchPeriod === 'thisWeek') {

            $startingDate = Carbon::now()->startOfWeek()->format('Y-m-d');
            $endingDate = Carbon::now()->format('Y-m-d');

        } elseif ($getSearchPeriod === 'lastWeek') {

            $startingDate = Carbon::now()->subWeek()->startOfWeek()->format('Y-m-d');
            $endingDate = Carbon::now()->subWeek()->endOfWeek()->format('Y-m-d');

        } elseif ($getSearchPeriod === 'thisMonth') {

            $startingDate = Carbon::now()->firstOfMonth()->format('Y-m-d');
            $endingDate = Carbon::now()->format('Y-m-d');

        } elseif ($getSearchPeriod === 'lastMonth') {
            $startingDate = Carbon::now()->subMonth()->firstOfMonth()->format('Y-m-d');
            $endingDate = Carbon::now()->subMonth()->lastOfMonth()->format('Y-m-d');

        } elseif ($getSearchPeriod === 'customPeriod') {

        }

        $searchPeriod = DB::table('punchRecords as records')
            ->select(
                 'records.jjanID'
                ,'records.punchTime'
                ,'records.punchDate'
                ,'records.punchType'
                ,'records.punchTypePairNo'
            )
        ;

        $workingHours = 0;
        $mealBreakHours01 = 0;
        $mealBreakHours02 = 0;

        // get all the dates (Y-m-d) in between $startindDate through $endingDate (including startind Date and ending Date).

        $dateRangeArray = new GeneralPurpose;
        $dateRangeArray = $dateRangeArray->getDatesFromRange($startingDate, $endingDate);

        $workingHourArray = [];

        // looping from $startingDate through $endingDate.

        foreach ($users as $user) {

            foreach ($dateRangeArray as $index => $date) {

                $date2 = Carbon::parse($date)->format('Y-m-d');

                $startWork = clone $searchPeriod;
                $endWork = clone $searchPeriod;
                $startMealBreak01 = clone $searchPeriod;
                $endMealBreak01 = clone $searchPeriod;
                $startMealBreak02 = clone $searchPeriod;
                $endMealBreak02 = clone $searchPeriod;


                $startWorkArray = [];
                $endWorkArray = [];
                $startMealBreak01Array = [];
                $endMealBreak01Array = [];
                $startMealBreak02Array = [];
                $endMealBreak02Array = [];

                $startWork = $startWork
                    ->where('punchType', 1)
                    ->where('punchDate', $date2)
                    ->where('jjanID',$user->jjanID)
                    //  ->select('punchTime')
                    ->get();

                // dd($startWork->all());
                $startWorkArray[$user->jjanID][$date2] = 0;
                foreach ($startWork as $startWork1) {
                    $startWorkArray[$user->jjanID][$date2] = $startWork1->punchTime;

                }


                $endWork = $endWork
                    ->where('punchType', 2)
                    ->where('punchDate', $date2)
                    ->where('jjanID',$user->jjanID)
                    ->get();

                // dd($endWork->all());
                $endWorkArray[$user->jjanID][$date2] = 0;
                foreach ($endWork as $endWork1) {
                    $endWorkArray[$user->jjanID][$date2] = $endWork1->punchTime;
                }

                $startMealBreak01 = $startMealBreak01
                    ->where('punchType', 3)
                    ->where('punchTypePairNo', 1)
                    ->where('punchDate', $date2)
                    ->where('jjanID',$user->jjanID)
                    ->get();

                // dd($startMealBreak01->all() );

                $startMealBreak01Array[$user->jjanID][$date2] = 0;
                foreach ($startMealBreak01 as $startMealBreak1) {
                    $startMealBreak01Array[$user->jjanID][$date2] = $startMealBreak1->punchTime;
                }

                $endMealBreak01 = $endMealBreak01
                    ->where('punchType', 4)
                    ->where('punchTypePairNo', 1)
                    ->where('punchDate', $date2)
                    ->where('jjanID',$user->jjanID)
                    ->get();

                //dd($endMealBreak01->all());

                $endMealBreak01Array[$user->jjanID][$date2] = 0;
                foreach ($endMealBreak01 as $endMealBreak1) {
                    $endMealBreak01Array[$user->jjanID][$date2] = $endMealBreak1->punchTime;
                }

                $startMealBreak02 = $startMealBreak02->where('punchType', 3)
                    ->where('punchTypePairNo', 2)
                    ->where('punchDate', $date2)
                    ->where('jjanID',$user->jjanID)
                    ->get();

                // dd($startMealBreak02->all());

                $startMealBreak02Array[$user->jjanID][$date] = 0;
                foreach ($startMealBreak02 as $startMealBreak1) {
                    $startMealBreak02Array[$user->jjanID][$date] = $startMealBreak1->punchTime;
                }

                $endMealBreak02 = $endMealBreak02->where('punchType', 4)
                    ->where('punchTypePairNo', 2)
                    ->where('punchDate', $date2)
                    ->where('jjanID',$user->jjanID)
                    ->get();

                $endMealBreak02Array[$user->jjanID][$date] = 0;
                foreach ($endMealBreak02 as $endMealBreak1) {
                    $endMealBreak02Array[$user->jjanID][$date] = $endMealBreak1->punchTime;
                }

              //  dd($startWorkArray[$user->jjanID][$date2],$endWorkArray[$user->jjanID][$date2]);



                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                if ($startWorkArray[$user->jjanID][$date2] !== 0 and $endWorkArray[$user->jjanID][$date2] !== 0) {
                    $workingHours = Carbon::parse($startWorkArray[$user->jjanID][$date2])->diffInMinutes(Carbon::parse($endWorkArray[$user->jjanID][$date2]));

                    if ($startMealBreak01Array[$user->jjanID][$date2] === 0 or $endMealBreak01Array[$user->jjanID][$date2] === 0)
                    {$mealBreakHours01 = 0;}else {
                        $mealBreakHours01 = Carbon::parse($startMealBreak01Array[$user->jjanID][$date2])->diffInMinutes(Carbon::parse($endMealBreak01Array[$user->jjanID][$date2]));
                    }

                    if ($startMealBreak02Array[$user->jjanID][$date2] === 0 or $endMealBreak02Array[$user->jjanID][$date2] === 0)
                    {
                        $mealBreakHours02 = 0;
                    }else {
                        $mealBreakHours02 = Carbon::parse($startMealBreak02Array[$user->jjanID][$date2])->diffInMinutes(Carbon::parse($endMealBreak02Array[$user->jjanID][$date2]));
                    }
                    dd($mealBreakHours02);
                }


                $totalWorkingHours[$date2] = round(($workingHours - $mealBreakHours01 - $mealBreakHours02) / 60, 2);



            }
            dd($totalWorkingHours);
            $workingHourArray[$user->jjanID] = array_sum($totalWorkingHours);

        }

//dd($workingHourArray);

        // Please choose


        return view('hours.hourMain')
            ->with(compact(
                    'users'
                    , 'currentUrl'
                    , 'getSearchPeriod'
                    , 'getMemberName'
                    , 'workingHourArray'
                    , 'totalWorkingHours'
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
