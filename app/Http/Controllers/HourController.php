<?php

namespace App\Http\Controllers;

use App\GeneralPurpose\GeneralPurpose;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\PunchRecord;

class HourController extends Controller
{

    public function __construct()
    {

    }

    public function display(Request $request)
    {
        $request->flash();
        $currentUrl = $request->path();
        $getSearchPeriod = $request->input('getSearchPeriod');
        $getMemberName = $request->input('getMemberName');


        $users = DB::table('users')
            ->select(
                'users.jjanID'
                , 'users.firstNm'
                , 'users.lastNm'
            )
            ->get();

        // initiate $startindDate and $endingDate
        $startingDate = 0;
        $endingDate = 0;


        // change the search period using dropdown menu.

        //set the $getSearchPeriod manually for testing purpose.
        $getSearchPeriod = 'thisMonth';


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

        //
        $punchRecords = DB::table('punchRecords as records')
            ->select(
                'records.jjanID'
                , 'records.punchTime'
                , 'records.punchDate'
                , 'records.punchType'
                , 'records.punchTypePairNo'
            );

        // Define some arrays for working hour calculation
        $workingHours = [];
        $mealBreakHours01 = [];
        $mealBreakHours02 = [];
        $workingHourArray = [];
        $totalWorkingMinutes = [];

        $startWorkArray = [];
        $endWorkArray = [];
        $startMealBreak01Array = [];
        $endMealBreak01Array = [];
        $startMealBreak02Array = [];
        $endMealBreak02Array = [];

        // get all the dates (Y-m-d) in between $startindDate through $endingDate (including startind Date and ending Date).

        // get all the days from $startingDate to $endingDate
        // for example, if $startingDate = 2016-01-01, $endingDate = 2016-03-01, then create array as [2016-01-01, 2016-01-02 ... 2016-03-01].
        $dateRangeArray = new GeneralPurpose;
        $dateRangeArray = $dateRangeArray->getDatesFromRange($startingDate, $endingDate);


        // looping users

        foreach ($users as $user) {

            // get the working minutes per date
            foreach ($dateRangeArray as $index => $date) {

                // convert date format from Ymd to Y-m-d to fit the MariaDB date format.
                $date2 = Carbon::parse($date)->format('Y-m-d');

                // clone the query object
                $startWork = clone $punchRecords;
                $endWork = clone $punchRecords;
                $startMealBreak01 = clone $punchRecords;
                $endMealBreak01 = clone $punchRecords;
                $startMealBreak02 = clone $punchRecords;
                $endMealBreak02 = clone $punchRecords;

                $startWork = $startWork
                    ->where('punchType', 1)
                    ->where('punchDate', $date2)
                    ->where('jjanID', $user->jjanID)
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
                    ->where('jjanID', $user->jjanID)
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
                    ->where('jjanID', $user->jjanID)
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
                    ->where('jjanID', $user->jjanID)
                    ->get();

                //dd($endMealBreak01->all());

                $endMealBreak01Array[$user->jjanID][$date2] = 0;
                foreach ($endMealBreak01 as $endMealBreak1) {
                    $endMealBreak01Array[$user->jjanID][$date2] = $endMealBreak1->punchTime;
                }

                $startMealBreak02 = $startMealBreak02->where('punchType', 3)
                    ->where('punchTypePairNo', 2)
                    ->where('punchDate', $date2)
                    ->where('jjanID', $user->jjanID)
                    ->get();

                // dd($startMealBreak02->all());

                $startMealBreak02Array[$user->jjanID][$date2] = 0;
                foreach ($startMealBreak02 as $startMealBreak1) {
                    $startMealBreak02Array[$user->jjanID][$date] = $startMealBreak1->punchTime;
                }

                $endMealBreak02 = $endMealBreak02->where('punchType', 4)
                    ->where('punchTypePairNo', 2)
                    ->where('punchDate', $date2)
                    ->where('jjanID', $user->jjanID)
                    ->get();

                $endMealBreak02Array[$user->jjanID][$date2] = 0;
                foreach ($endMealBreak02 as $endMealBreak1) {
                    $endMealBreak02Array[$user->jjanID][$date2] = $endMealBreak1->punchTime;
                }


                // Calculating working hours per date per user.
                if ($startWorkArray[$user->jjanID][$date2] !== 0 and $endWorkArray[$user->jjanID][$date2] !== 0) {
                    $workingMinutes[$user->jjanID] = Carbon::parse($startWorkArray[$user->jjanID][$date2])->diffInMinutes(Carbon::parse($endWorkArray[$user->jjanID][$date2]));

                    if ($startMealBreak01Array[$user->jjanID][$date2] === 0 or $endMealBreak01Array[$user->jjanID][$date2] === 0) {
                        $mealBreakHours01[$user->jjanID] = 0;
                    } else {
                        $mealBreakHours01[$user->jjanID] = Carbon::parse($startMealBreak01Array[$user->jjanID][$date2])->diffInMinutes(Carbon::parse($endMealBreak01Array[$user->jjanID][$date2]));
                    }

                    if ($startMealBreak02Array[$user->jjanID][$date2] === 0 or $endMealBreak02Array[$user->jjanID][$date2] === 0) {
                        $mealBreakHours02[$user->jjanID] = 0;
                    } else {
                        $mealBreakHours02[$user->jjanID] = Carbon::parse($startMealBreak02Array[$user->jjanID][$date2])->diffInMinutes(Carbon::parse($endMealBreak02Array[$user->jjanID][$date2]));
                    }

                    $totalWorkingMinutes[$user->jjanID][$date2] = round(($workingHours[$user->jjanID] - $mealBreakHours01[$user->jjanID] - $mealBreakHours02[$user->jjanID]), 2);

                } else {
                    $totalWorkingMinutes[$user->jjanID][$date2] = 0;
                }
            }

            // sum all the minutes for the jjanID in this period and convert minutes to hours.

            if (array_sum($totalWorkingMinutes[$user->jjanID]) === 0) {
                $workingHourArray[$user->jjanID] = 0;
            } else {
                $workingHourArray[$user->jjanID] = round(array_sum($totalWorkingMinutes[$user->jjanID]) / 60, 2);
            }
        }


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