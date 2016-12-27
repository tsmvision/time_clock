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
        $getSearchPeriod = 'thisWeek';


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

        $mealBreakHours01 = [];
        $mealBreakHours02 = [];
        $workingHourArray = [];
        $workingMinutes = [];
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
                $startWorkQuery = clone $punchRecords;
                $endWorkQuery = clone $punchRecords;
                $startMealBreak01Query = clone $punchRecords;
                $endMealBreak01Query = clone $punchRecords;
                $startMealBreak02Query = clone $punchRecords;
                $endMealBreak02Query = clone $punchRecords;

                // initiate
                $startWorkArray[$user->jjanID][$date2] = 0;
                $endWorkArray[$user->jjanID][$date2] = 0;
                $startMealBreak01Array[$user->jjanID][$date2] = 0;
                $endMealBreak01Array[$user->jjanID][$date2] = 0;
                $startMealBreak02Array[$user->jjanID][$date2] = 0;
                $endMealBreak02Array[$user->jjanID][$date2] = 0;


                // Query -  get punch time for startWork for single day($date)
                $startWorkQuery = $startWorkQuery
                    ->where('punchType', 1)
                    ->where('punchDate', $date2)
                    ->where('jjanID', $user->jjanID)
                    ->get();

                // get the punch time from the query above.
                foreach ($startWorkQuery as $startWork1) {
                    $startWorkArray[$user->jjanID][$date2] = $startWork1->punchTime;

                }

                // dd($startWorkArray[$user->jjanID][$date2]);

                // Query - get the punch time for endWork for single day ($date)
                $endWorkQuery = $endWorkQuery
                    ->where('punchType', 2)
                    ->where('punchDate', $date2)
                    ->where('jjanID', $user->jjanID)
                    ->get();

                // get the punch time from the query above.
                foreach ($endWorkQuery as $endWork1) {
                    $endWorkArray[$user->jjanID][$date2] = $endWork1->punchTime;
                }

                //dd($endWorkArray[$user->jjanID][$date2]);

                //if startWorkArray and endWorkArray, both of them are not 0 then proceed otherwise set to 0
                $startMealBreak01Array[$user->jjanID][$date2] = 0;

                // Query - get the punch time for startMealBreak01 for single day ($date)
                $startMealBreak01Query = $startMealBreak01Query
                    ->where('punchType', 3)
                    ->where('punchTypePairNo', 1)
                    ->where('punchDate', $date2)
                    ->where('jjanID', $user->jjanID)
                    ->get();

                // get the punch time for startMealBreak01 for single day ($date)
                foreach ($startMealBreak01Query as $startMealBreak1) {
                    $startMealBreak01Array[$user->jjanID][$date2] = $startMealBreak1->punchTime;
                }

                // dd($startMealBreak01Array[$user->jjanID][$date2]);

                // Query - get the punch time for endMealBreak01 for single day ($date)
                $endMealBreak01Query = $endMealBreak01Query
                    ->where('punchType', 4)
                    ->where('punchTypePairNo', 1)
                    ->where('punchDate', $date2)
                    ->where('jjanID', $user->jjanID)
                    ->get();


                // get the punch time for startMealBreak01 for single day ($date)
                foreach ($endMealBreak01Query as $endMealBreak1) {
                    $endMealBreak01Array[$user->jjanID][$date2] = $endMealBreak1->punchTime;
                }

                // Query - get the punch time for startMealBreak02Query for single day ($date)
                $startMealBreak02Query = $startMealBreak02Query->where('punchType', 3)
                    ->where('punchTypePairNo', 2)
                    ->where('punchDate', $date2)
                    ->where('jjanID', $user->jjanID)
                    ->get();

                // get the punch time for endMealBreak02 for single day ($date)
                foreach ($startMealBreak02Query as $startMealBreak1) {
                    $startMealBreak02Array[$user->jjanID][$date] = $startMealBreak1->punchTime;
                }


                // Query - get the punch time for endMealBreak02Query for single day ($date)
                $endMealBreak02Query = $endMealBreak02Query->where('punchType', 4)
                    ->where('punchTypePairNo', 2)
                    ->where('punchDate', $date2)
                    ->where('jjanID', $user->jjanID)
                    ->get();


                // get the punch time for endMealBreak02Query for single day ($date)
                foreach ($endMealBreak02Query as $endMealBreak1) {
                    $endMealBreak02Array[$user->jjanID][$date2] = $endMealBreak1->punchTime;
                }


                //initiate
                $workingMinutes[$user->jjanID] = 0;
                // Calculating working minutes per user per date.
                // count as valid minutes only when StartWork and endWork, both of them punched.

                if ($startWorkArray[$user->jjanID][$date2] !== 0 and $endWorkArray[$user->jjanID][$date2] !== 0) {
                    $workingMinutes[$user->jjanID] = Carbon::parse($startWorkArray[$user->jjanID][$date2])->diffInMinutes(Carbon::parse($endWorkArray[$user->jjanID][$date2]));
                }

                //initiate
                $mealBreakHours01[$user->jjanID] = 0;

                // if $startMealBreak01Array's value and $endMealBreak01Array's value exist then calculate otherwise set to 0.
                if ($startMealBreak01Array[$user->jjanID][$date2] !== 0 and $endMealBreak01Array[$user->jjanID][$date2] !== 0) {
                    $mealBreakHours01[$user->jjanID] = Carbon::parse($startMealBreak01Array[$user->jjanID][$date2])->diffInMinutes(Carbon::parse($endMealBreak01Array[$user->jjanID][$date2]));
                }

                // initiate
                $mealBreakHours02[$user->jjanID] = 0;

                // if startMealBreak02 and endMealBreak02, both of them are not 0 then calculate the value, otherwise set to 0
                if ($startMealBreak02Array[$user->jjanID][$date2] !== 0 and $endMealBreak02Array[$user->jjanID][$date2] !== 0) {
                    $mealBreakHours02[$user->jjanID] = Carbon::parse($startMealBreak02Array[$user->jjanID][$date2])->diffInMinutes(Carbon::parse($endMealBreak02Array[$user->jjanID][$date2]));
                }

                if ($startWorkArray[$user->jjanID][$date2] !== 0 and $endWorkArray[$user->jjanID][$date2] !== 0) {

                    $totalWorkingMinutes[$user->jjanID][$date2] = round(($workingMinutes[$user->jjanID] - $mealBreakHours01[$user->jjanID] - $mealBreakHours02[$user->jjanID]), 2);

                    // sum all the minutes for the jjanID in this period and convert minutes to hours.
                    $workingHourArray[$user->jjanID] = round(array_sum($totalWorkingMinutes[$user->jjanID]) / 60, 2);

                    // dd($workingHourArray[$user->jjanID]);

                } else {
                    $totalWorkingMinutes[$user->jjanID][$date2] = 0;
                    $workingHourArray[$user->jjanID] = 0;
                }
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

    public
    function update($id)
    {

    }


    public
    function delete($id)
    {

        $punchRecord = PunchRecord::find($id);

        $punchRecord->delete();


        return redirect('/history/list')->with('message', 'deleted!');
    }


}