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

            foreach ($dateRangeArray as $index => $date) {

                $date2 = Carbon::parse($date)->format('Y-m-d');

                $startWork = clone $searchPeriod;
                $endWork = clone $searchPeriod;
                $startMealBreak01 = clone $searchPeriod;
                $endMealBreak01 = clone $searchPeriod;
                $startMealBreak02 = clone $searchPeriod;
                $endMealBreak02 = clone $searchPeriod;

                $workingHourArray['startWork'] = 0;
                $workingHourArray['endWork'] = 0;
                $workingHourArray['startMealBreak01'] = 0;
                $workingHourArray['endMealBreak01'] = 0;
                $workingHourArray['startMealBreak02'] = 0;
                $workingHourArray['endMealBreak02'] = 0;

                $startWorkArray = [];
                $endWorkArray = [];
                $startMealBreak01Array = [];
                $endMealBreak01Array = [];
                $startMealBreak02Array = [];
                $endMealBreak02Array = [];

                $startWork = $startWork
                    ->where('punchType', 1)
                    ->where('punchDate', $date2)
                  //  ->select('punchTime')
                    ->get()
                    ;

               // dd($startWork->all());
                $startWorkArray[$jjanID][$date2] = 0;
                foreach ($startWork as $startWork1) {
                    $startWorkArray[$jjanID][$date2] = $startWork1->punchTime;

                }

                $endWork = $endWork
                    ->where('punchType', 2)
                    ->where('punchDate',$date2)
                    ->get()
                    ;

               // dd($endWork->all());
                $endWorkArray[$jjanID][$date2] = 0;
                foreach ($endWork as $endWork1) {
                    $endWorkArray[$jjanID][$date2] = $endWork1->punchTime;
                }

                $startMealBreak01 = $startMealBreak01
                    ->where('punchType', 3)
                    ->where('punchTypePairNo', 1)
                    ->where('punchDate',$date2)
                    ->get();

               // dd($startMealBreak01->all() );

                foreach ($startMealBreak01 as $startMealBreak1) {
                    $startMealBreak01Array[$jjanID][$date2] = $startMealBreak1->punchTime;
                }

                $endMealBreak01 = $endMealBreak01
                    ->where('punchType', 4)
                    ->where('punchTypePairNo', 1)
                    ->where('punchDate',$date2)
                    ->get();

                //dd($endMealBreak01->all());

                foreach ($endMealBreak01 as $endMealBreak1) {
                    $endMealBreak01Array[$jjanID][$date2] = $endMealBreak1->punchTime;
                }

                $startMealBreak02 = $startMealBreak02->where('punchType', 3)
                    ->where('punchTypePairNo', 2)
                    ->where('punchDate', $date2)
                    ->get();

               // dd($startMealBreak02->all());
                $startMealBreak02Array[$jjanID][$date] = 0;

                foreach ($startMealBreak02 as $startMealBreak1) {
                    $startMealBreak02Array[$jjanID][$date] = $startMealBreak1->punchTime;
                }

                $endMealBreak02 = $endMealBreak02->where('punchType', 4)
                    ->where('punchTypePairNo', 2)
                    ->where('punchDate',$date2)
                    ->get();

                $endMealBreak02Array[$jjanID][$date] = 0;
                foreach ($endMealBreak02 as $endMealBreak1) {
                    $endMealBreak02Array[$jjanID][$date] = $endMealBreak1->punchTime;
                }

                              if ($startWorkArray[$jjanID][$date2] == 1 and $endWorkArray[$jjanID][$date2] == 1) {
                                  $workingHours = Carbon::parse($startWorkArray[$jjanID][$date2])->diffInMinutes(Carbon::parse($endWorkArray[$jjanID][$date2]));

                                  $mealBreakHours01 = Carbon::parse($startMealBreak01Array[$jjanID][$date2])->diffInMinutes(Carbon::parse($endMealBreak01Array[$jjanID][$date2]));

                                  $mealBreakHours02 = Carbon::parse($startMealBreak02Array[$jjanID][$date2])->diffInMinutes(Carbon::parse($endMealBreak02Array[$jjanID][$date2]));
                              }


                $totalWorkingHours = round(($workingHours - $mealBreakHours01 - $mealBreakHours02) / 60, 2);

                $workingHourArray['workingHours'] = $workingHours;

              //  dd($workingHourArray);


            }



        // Please choose


        return view('hours.hourMain')
            ->with(compact(
                    'list'
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
