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

        $startingDate = 0;
        $endingDate = 0;

        //    $list = DB::table('punchRecords as records ')
        //        ->join('users', 'records.jjanID', '=', 'users.jjanID')
        //        ->distinct()
        //        ->select(
        //            'records.id'
        //            , 'records.jjanID'
        //            , 'users.firstNm'
        //            , 'users.lastNm'
        //            , 'records.punchTime'
        //            , 'records.punchType'
        //            , 'records.punchTypePairNo'
        //        );

        $list = DB::table('users')
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

            $startingDate = Carbon::now()->format('Y-m-d');
            $endingDate = Carbon::now()->format('Y-m-d');

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
            ->join('users', 'records.jjanID', '=', 'users.jjanID')
            ->distinct()
            ->select(
                DB::raw('DATE(punchTime) AS date')
                , 'users.jjanID'
                , 'users.firstNm'
                , 'users.lastNm'
                , 'records.punchType'
                , 'records.punchTypePairNo'
            )
            ->whereRaw("DATE(punchTime) >= '$startingDate'")
            ->whereRaw("DATE(punchTime) <= '$endingDate'"))
        ;

        $workingHours = 0;
        $mealBreakHours01 = 0;
        $mealBreakHours02 = 0;


        // get all the dates (Y-m-d) in between $startindDate through $endingDate (including startind Date and ending Date).

        $dateRangeArray = new GeneralPurpose;
        $dateRangeArray = $dateRangeArray->getDatesFromRange($startingDate, $endingDate);

        // looping from $startingDate through $endingDate.

            foreach ($dateRangeArray as $index => $date) {

                $startWork = clone $searchPeriod;
                $endWork = clone $searchPeriod;
                $startMealBreak01 = clone $searchPeriod;
                $endMealBreak01 = clone $searchPeriod;
                $startMealBreak02 = clone $searchPeriod;
                $endMealBreak02 = clone $searchPeriod;


                $startWork = $startWork
                    ->where('punchType', 1)
                    ->whereRaw("DATE(records.punchTime) = '$date'")
                    ->get();

                foreach ($startWork as $startWork1) {
                    $workingHourArray[$index]['startWork'] = $startWork1->punchTime;

                }


                $endWork = $endWork
                    ->where('punchType', 2)
                    ->whereRaw("DATE(records.punchTime) = '$date'")
                    ->get();

                foreach ($endWork as $endWork1) {
                    $workingHourArray[$index]['endWorking'] = $endWork1->punchTime;
                }

                $startMealBreak01 = $startMealBreak01
                    ->where('punchType', 3)
                    ->where('punchTypePairNo', 1)
                    ->whereRaw("DATE(records.punchTime) = '$date'")
                    ->get();

                foreach ($startMealBreak01 as $startMealBreak1) {
                    $workingHourArray['startMealBreak01'] = $startMealBreak1->punchTime;
                }


                $endMealBreak01 = $endMealBreak01
                    ->where('punchType', 4)
                    ->where('punchTypePairNo', 1)
                    ->whereRaw("DATE(records.punchTime) = '$date'")
                    ->get();

                foreach ($endMealBreak01 as $endMealBreak1) {
                    $workingHourArray['endMealBreak01'] = $endMealBreak1->punchTime;
                }

                $startMealBreak02 = $startMealBreak02->where('punchType', 3)
                    ->where('punchTypePairNo', 2)
                    ->whereRaw("DATE(records.punchTime) = '$date'")
                    ->get();

                foreach ($startMealBreak02 as $startMealBreak1) {
                    $workingHourArray['startMealBreak02'] = $startMealBreak1->punchTime;
                }

                $endMealBreak02 = $endMealBreak02->where('punchType', 4)
                    ->where('punchTypePairNo', 2)
                    ->whereRaw("DATE(records.punchTime) = '$date'")
                    ->get();

                foreach ($endMealBreak02 as $startMealBreak1) {
                    $workingHourArray['endMealBreak02'] = $startMealBreak1->punchTime;
                }

                //              if ($workingHourArray['startWork'] !== 0 and $workingHourArray['endWork'] !== 0) {
                //                  $workingHours = Carbon::parse($workingHourArray['startWork'])->diffInMinutes(Carbon::parse($workingHourArray['endWork']));
                //              }
                //
                //              if ($workingHourArray['startMealBreak01'] !== 0 and $workingHourArray['endMealBreak01'] !== 0) {
                //                  $mealBreakHours01 = Carbon::parse($workingHourArray['startMealBreak01'])->diffInMinutes(Carbon::parse($workingHourArray['endMealBreak01']));
                //              }
                //              if ($workingHourArray['startMealBreak02'] !== 0 and $workingHourArray['endMealBreak02'] !== 0) {
                //                  $mealBreakHours02 = Carbon::parse($workingHourArray['startMealBreak02'])->diffInMinutes(Carbon::parse($workingHourArray['endMealBreak02']));
                //              }


                $workingHours = round(($workingHours - $mealBreakHours01 - $mealBreakHours02) / 60, 2);

                $workingHourArray['namjoong'][$date]['workingHours'] = $workingHours;

                dd($workingHourArray);


            }


        $workingHourCollection = collect($workingHourArray);


        // dd($workingHourArray);


        //  dd($workingHours);

        //  $workingHours1- $mealBreakHours01


        // Please choose


        $list = $list->get();

        return view('hours.hourMain')
            ->with(compact(
                    'list'
                    , 'currentUrl'
                    , 'getSearchPeriod'
                    , 'getMemberName'
                    , 'workingHourArray'
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
