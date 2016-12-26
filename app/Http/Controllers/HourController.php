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

        $startingDate = 0;
        $endingDate = 0;



        $list = DB::table('punchRecords as records ')
            ->join('users', 'records.jjanID', '=', 'users.jjanID')
            ->distinct()
            ->select(
                'records.id'
                ,'records.jjanID'
                , 'users.firstNm'
                , 'users.lastNm'
                , 'records.punchTime'
                , 'records.punchType'
                , 'records.punchTypePairNo'
            );



       // dd($list->get()->toArray());

        if ($getSearchPeriod === null || $getSearchPeriod === 'today') {
            $list = $list
                ->whereRaw("DAY(records.punchTime) = $day")
                ->whereRaw("MONTH(records.punchTime) = $month")
                ->whereRaw("YEAR(records.punchTime) = $year");



        } elseif ($getSearchPeriod === 'thisMonth') {
            $list = $list
                ->whereRaw("MONTH(records.punchTime) = $month")
                ->whereRaw("YEAR(records.punchTime) = $year");

        } elseif ($getSearchPeriod === 'lastMonth') {
            $list = $list
                ->whereRaw("MONTH(records.punchTime) = $lastMonth")
                ->whereRaw("YEAR(records.punchTime) = $year");

        } elseif ($getSearchPeriod === 'customPeriod') {

        }

      //  $a = $list->get();

      //  $list = $list
      //      ->orderBy('records.punchTime', 'DESC')
      //      ->orderBy('records.id', 'DESC')
      //      ->paginate(15);

        // get the first day and last day.
        // calculating per jjanID
        // calculating day by day

       // foreach()
       // {
//
  //      }

        $workingHourArray = [];
        $countPerJJANID = [];
        $numberOfWorkingHourPairs = [];
        $year1 = 0;
        $month1 = 0;
        $day1 = 0;
    /*    foreach($a as $a1)
        {
            foreach()
            {
                //first day: calculating working hours, second day: working hours ........ last day: calculating working Hours
                //foreach()
                $year1 = Carbon::parse($a1->punchTime)->format('Y');
                $month1 = Carbon::parse($a1->punchTime)->format('m');
                $day1 = Carbon::parse($a1->punchTime)->format('d');
                $workingHourArray[$a1->jjanID][$a1->punchTime] = $a1->punchTime;
            }
        }

        // Calculate the number of
        foreach($a as $a1)
        {
            $countPerJJANID[$a1->jjanID] = collect($workingHourArray[$a1->jjanID])->count();
           // $numberOfWorkingHourPairs[$a1->jjanID] =
        }
*/

        //dd($countPerJJANID);


//        $startTime = Carbon::parse($this->start_time);
//        $finishTime = Carbon::parse($this->finish_time);
//        $finishTime->diff($startTime)->format('%H:%i');


        $punchRecords = DB::table('punchRecords')
                            ->select('jjanID','punchTime','punchType')
                            ->get()
                            ;

        $array = [];
        $startingDate = 0;
        $endingDate = 0;



            foreach ($punchRecords as $punchRecords1)
            {
                {
                    $year = Carbon::parse($punchRecords1->punchTime)->format('Y');
                    $month = Carbon::parse($punchRecords1->punchTime)->format('m');
                    $day = Carbon::parse($punchRecords1->punchTime)->format('d');
                    $time = Carbon::parse($punchRecords1->punchTime)->format('H:i');

                  //  $array[$userList1->jjanID][$year][$month][$day][] =  $time;

                }
            }


       // dd($array);

        // today calculation

        $year = Carbon::now()->format('Y');
        $month = Carbon::now()->format('m');
        $day = Carbon::now()->format('d');


        $today = DB::table('punchRecords')
                        ->where('jjanID','namjoong')
                        ->whereRaw("YEAR(punchTime) = $year")
                        ->whereRaw("MONTH(punchTime) = $month")
                        ->whereRaw("DAY(punchTime) = $day")
                        ->select(
                            'jjanID'
                            ,'punchTime'
                            ,'punchType'
                            ,'punchTypePairNo'
                        )->get()
                        ;

        $startWork = $today->where('punchType',1);

        $workingHourArray['startWork'] = 0;
        $workingHourArray['endWork'] = 0;
        $workingHourArray['startMealBreak01'] = 0;
        $workingHourArray['endMealBreak01'] = 0;
        $workingHourArray['startMealBreak02'] = 0;
        $workingHourArray['endMealBreak02'] = 0;

        $workingHours = 0;
        $mealBreakHours01 = 0;
        $mealBreakHours02 = 0;


        foreach ($startWork as $startWork1)
        {
            $workingHourArray['startWork'] = $startWork1->punchTime;
        }

        $endWork = $today->where('punchType',2);

        foreach ($endWork as $endWork1)
        {
          $workingHourArray['endWork'] = $endWork1->punchTime;
        }

        $startMealBreak01 = $today->where('punchType',3)
                                ->where('punchTypePairNo',1);

        foreach ($startMealBreak01 as $startMealBreak1)
        {
            $workingHourArray['startMealBreak01'] = $startMealBreak1->punchTime;
        }

        $endMealBreak01 = $today->where('punchType',4)
            ->where('punchTypePairNo',1);

        foreach ($endMealBreak01 as $endMealBreak1)
        {
            $workingHourArray['endMealBreak01'] = $endMealBreak1->punchTime;
        }

        $startMealBreak02 = $today->where('punchType',3)
            ->where('punchTypePairNo',2);

        foreach ($startMealBreak02 as $startMealBreak1)
        {
            $workingHourArray['startMealBreak02'] = $startMealBreak1->punchTime;
        }

        $endMealBreak02 = $today->where('punchType',4)
            ->where('punchTypePairNo',2);

        foreach ($endMealBreak02 as $startMealBreak1)
        {
            $workingHourArray['endMealBreak02'] = $startMealBreak1->punchTime;
        }

        if ($workingHourArray['startWork'] !== 0 and $workingHourArray['endWork'] !== 0)
        {
            $workingHours = Carbon::parse($workingHourArray['startWork'])->diffInMinutes(Carbon::parse($workingHourArray['endWork']));
        }


        if ($workingHourArray['startMealBreak01'] !== 0 and $workingHourArray['endMealBreak01'] !== 0 ) {

            $mealBreakHours01 = Carbon::parse($workingHourArray['startMealBreak01'])->diffInMinutes(Carbon::parse($workingHourArray['endMealBreak01']));
        }
        if ($workingHourArray['startMealBreak02'] !== 0 and $workingHourArray['endMealBreak02'] !== 0 ) {

            $mealBreakHours02 = Carbon::parse($workingHourArray['startMealBreak02'])->diffInMinutes(Carbon::parse($workingHourArray['endMealBreak02']));
        }

        dd($workingHours, $mealBreakHours01,$mealBreakHours02);

      //  $workingHours1- $mealBreakHours01



        // Please choose

        $list = DB::table('users')
            ->select(
                'users.jjanID'
                ,'users.firstNm'
                ,'users.lastNm'
            );

        $list = $list->get();

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
