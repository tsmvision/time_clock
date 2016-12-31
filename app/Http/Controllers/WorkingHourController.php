<?php

namespace App\Http\Controllers;

use App\GeneralPurpose\GeneralPurpose;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\PunchRecord;
use Illuminate\Support\Facades\Auth;

class WorkingHourController extends Controller
{
    use GeneralPurpose;

    public function __construct()
    {

    }

    public function punchRecords($startingDate,$endingDate)
    {
        $punchRecords = DB::table('punchRecords as records')
            //  ->join('users','records.jjanID','=','users.jjanID')
            //  ->distinct()
            ->select(
                'jjanID'
                ,'punchDate'
                ,DB::raw("(case when punchType=1 THEN punchTime END) as startWork")
                ,DB::raw("(case when punchType=2 then punchTime end) as endWork")
                ,DB::raw("(case when punchType=3 and punchTypePairNo =1 then punchTime end) as startMealBreak01")
                ,DB::raw("(case when punchType=4 and punchTypePairNo =1 then punchTime end) as endMealBreak01")
                ,DB::raw("(case when punchType=3 and punchTypePairNo =2 then punchTime end) as startMealBreak02")
                ,DB::raw("(case when punchType=4 and punchTypePairNo =2 then punchTime end) as endMealBreak02")
            )
            ->where('records.punchDate', '>=', $startingDate)
            ->where('records.punchDate', '<=', $endingDate);

        return $punchRecords;
    }

    public function showList(Request $request)
    {
        $request->flash();
        $currentUrl = $request->path();
        $getSearchPeriod = $request->input('getSearchPeriod');
        $getJJANID = $request->input('getJJANID');
        $getMemberName = $request->input('getMemberName');

        $currentJJANID = AUTH::user()->jjanID;


        // change the search period using dropdown menu.

        //set the $getSearchPeriod manually for testing purpose.
        //$getSearchPeriod = 'thisWeek';

        $searchPeriod = $this->searchPeriod($getSearchPeriod);
        $startingDate = $searchPeriod['startingDate'];
        $endingDate = $searchPeriod['endingDate'];

        $startingDate = '2016-12-01';
        $endingDate = '2016-12-31';

        // for displaying jjanID, firstNm, lastNm in the table in the view.
        $users = DB::table('users')
            ->select(
                'users.jjanID'
                , 'users.firstNm'
                , 'users.lastNm'
            )
            ->where('jjanID', $currentJJANID)
            ->get();

        //set where Cluase with jjanID unless $getJJANID == '0'
//        if ($getJJANID !== null and $getJJANID !== '0') {
//            $users = $users->where('records.jjanID', $getJJANID);

        if ($currentUrl === 'workingHours') {
            $punchRecords = $this->punchRecords($startingDate,$endingDate)
                ->where('records.jjanID', $currentJJANID)
                ->get();
        }

        // add searchByMemberName
        //    $searchByMemberName = new GeneralPurpose;
        //    $users = $searchByMemberName->searchByMemberName($users, $getMemberName);

        // $sql = new GeneralPurpose;

        // dd($sql->getSql($punchRecords));

        // get all the dates (Y-m-d) in between $startindDate through $endingDate (including startind Date and ending Date).

        // get all the days from $startingDate to $endingDate
        // for example, if $startingDate = 2016-01-01, $endingDate = 2016-03-01, then create array as [2016-01-01, 2016-01-02 ... 2016-03-01].
        //  $dateRangeArray = new GeneralPurpose;
        $dateRangeArray = $this->getDatesFromRange($startingDate, $endingDate);
        $result = [];

        // looping users

        foreach ($users as $user) {
            $totalWorkingHours[$user->jjanID] = 0;

            foreach ($dateRangeArray as $index => $date) {

                // convert date format from Ymd to Y-m-d to fit the MariaDB date format.
                $date2 = Carbon::parse($date)->format('Y-m-d');

                $result[$user->jjanID][$date]= [
                    'jjanID' => $user->jjanID
                    , 'date' => $date
                    , 'startWork' => 0
                    , 'endWork' => 0
                    , 'startMealBreak01' => 0
                    , 'endMealBreak01' => 0
                    , 'startMealBreak02' => 0
                    , 'endMealBreak02' => 0
                    , 'workMin' => 0
                    , 'mealBreak01Min' => 0
                    , 'mealBreak02Min' => 0
                    , 'totalWorkingMin' => 0
                    , 'totalWorkHour' => 0
                ];

                // Query -  get punch time for startWork for single day($date)
                $query = $this->punchRecords($startingDate,$endingDate)
                    ->where('records.punchDate', $date2)
                    ->where('records.jjanID', $user->jjanID)
                    ->get();


                //////////// += 쪽에 문제가 있음. 체크할 것.
                foreach( $query as $query1)
                {
                    $result[$user->jjanID][$date]['startWork'] += $query1->startWork;
                    $result[$user->jjanID][$date]['endWork'] += $query1->endWork;
                    $result[$user->jjanID][$date]['startMealBreak01'] += $query1->startMealBreak01;
                    $result[$user->jjanID][$date]['endMealBreak01'] += $query1->endMealBreak01;
                    $result[$user->jjanID][$date]['startMealBreak02'] += $query1->startMealBreak02;
                    $result[$user->jjanID][$date]['endMealBreak02'] += $query1->endMealBreak02;
                }

                // count as valid minutes only when StartWork and endWork, both of them punched.

                if ($result[$user->jjanID][$date]['startWork'] !== 0 and $result[$user->jjanID][$date]['endWork'] !== 0) {
                    $workingMinutes[$user->jjanID][$date]['workMin'] = Carbon::parse($result[$user->jjanID][$date]['startWork'])
                                                                        ->diffInMinutes(Carbon::parse($result[$user->jjanID][$date]['endWork']));
                }

                // if $startMealBreak01Array's value and $endMealBreak01Array's value exist then calculate otherwise set to 0.
                if ($result[$user->jjanID][$date]['startMealBreak01'] !== 0 and $result[$user->jjanID][$date]['endMealBreak01'] !== 0) {
                    $result[$user->jjanID][$date]['mealBreak01Min'] = Carbon::parse($result[$user->jjanID][$date]['startMealBreak01'])
                                                                        ->diffInMinutes(Carbon::parse($result[$user->jjanID][$date]['endMealBreak01']));
                }

                // if startMealBreak02 and endMealBreak02, both of them are not 0 then calculate the value, otherwise set to 0
                if ($result[$user->jjanID][$date]['startMealBreak02'] !== 0 and $result[$user->jjanID][$date]['endMealBreak02'] !== 0) {
                    $result[$user->jjanID][$date]['mealBreak02Min'] = Carbon::parse($result[$user->jjanID][$date]['startMealBreak02'])
                                                                        ->diffInMinutes(Carbon::parse($result[$user->jjanID][$date]['endMealBreak02']));
                }

                // if endWork - startWork != 0
                if ($result[$user->jjanID][$date]['workMin'] !== 0)
                {
                    $result[$user->jjanID][$date]['totalWorkingMin'] =
                        round(($result[$user->jjanID][$date] - $result[$user->jjanID][$date]['mealBreak01Min'] - $result[$user->jjanID][$date]['mealBreak01Min']), 2);

                }



            }

            dd($result);
            $result2 = collect($result)->where('jjanID',$user->jjanID);




            $totalWorkingHours[$user->jjanID] = ($result2->sum('workMin') - $result2->sum('mealBreakMin01') - $result2->sum('mealBreakMin02'));
        }






        if ($currentUrl === 'workingHours') {
            return view('workingHours.hourMain')
                ->with(compact(
                        'users'
                        , 'currentUrl'
                        , 'getSearchPeriod'
                        , 'getJJANID'
                        , 'getMemberName'
                        , 'result'
                        , 'totalWorkingHours'
                    )
                );
        } elseif ($currentUrl === 'admin/workingHours') {
            return view('admin.workingHours.hourMain')
                ->with(compact(
                        'users'
                        , 'users2'
                        , 'currentUrl'
                        , 'getSearchPeriod'
                        , 'getJJANID'
                        , 'getMemberName'
                        , 'workingHourArray'
                        , 'totalWorkingHours'
                    )
                );
        }

    }


    public function showListTest(Request $request)
    {
        $request->flash();
        $currentUrl = $request->path();
        $getSearchPeriod = $request->input('getSearchPeriod');
        $getJJANID = $request->input('getJJANID');
        $getMemberName = $request->input('getMemberName');

        // initiate $startindDate and $endingDate
        $startingDate = 0;
        $endingDate = 0;

        $currentJJANID = AUTH::user()->jjanID;


        // change the search period using dropdown menu.

        //set the $getSearchPeriod manually for testing purpose.
        //$getSearchPeriod = 'thisWeek';

        $searchPeriod = $this->searchPeriod($getSearchPeriod);
        $startingDate = $searchPeriod['startingDate'];
        $endingDate = $searchPeriod['endingDate'];


        // for displaying jjanID, firstNm, lastNm in the table in the view.
        $users = DB::table('users')
            ->select(
                'users.jjanID'
                , 'users.firstNm'
                , 'users.lastNm'
            )
            ->where('jjanID', $currentJJANID);

        //
        $punchRecords = DB::table('punchRecords as records')
            //  ->join('users','records.jjanID','=','users.jjanID')
            //  ->distinct()
            ->select(
                'records.jjanID'
                , 'records.punchTime'
                , 'records.punchDate'
                , 'records.punchType'
                , 'records.punchTypePairNo'
            )
            ->where('records.punchDate', '>=', $startingDate)
            ->where('records.punchDate', '<=', $endingDate);

        //set where Cluase with jjanID unless $getJJANID == '0'
//        if ($getJJANID !== null and $getJJANID !== '0') {
//            $users = $users->where('records.jjanID', $getJJANID);

        if ($currentUrl === 'workingHours') {
            $punchRecords = $punchRecords->where('records.jjanID', $currentJJANID);

        }


        // add searchByMemberName
        //    $searchByMemberName = new GeneralPurpose;
        //    $users = $searchByMemberName->searchByMemberName($users, $getMemberName);

        // $sql = new GeneralPurpose;

        // dd($sql->getSql($punchRecords));


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
        //  $dateRangeArray = new GeneralPurpose;
        $dateRangeArray = $this->getDatesFromRange($startingDate, $endingDate);

        // looping users

        $a = [];
        foreach ($users as $user) {
            $a[] = $user->jjanID;
        }

        dd($a);

        foreach ($users as $user) {

            // initiate working workingHours per user
            $workingHourArray[$user->jjanID] = 0;

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

                $totalWorkingMinutes[$user->jjanID][$date2] = 0;


                // Query -  get punch time for startWork for single day($date)
                $startWorkQuery = $startWorkQuery
                    ->where('records.punchType', 1)
                    ->where('records.punchDate', $date2)
                    ->where('records.jjanID', $user->jjanID)
                    ->get();

                // get the punch time from the query above.
                foreach ($startWorkQuery as $startWork1) {
                    $startWorkArray[$user->jjanID][$date2] = $startWork1->punchTime;

                }

                // dd($startWorkArray[$user->jjanID][$date2]);

                // Query - get the punch time for endWork for single day ($date)
                $endWorkQuery = $endWorkQuery
                    ->where('records.punchType', 2)
                    ->where('records.punchDate', $date2)
                    ->where('records.jjanID', $user->jjanID)
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
                    ->where('records.punchType', 3)
                    ->where('records.punchTypePairNo', 1)
                    ->where('records.punchDate', $date2)
                    ->where('records.jjanID', $user->jjanID)
                    ->get();

                // get the punch time for startMealBreak01 for single day ($date)
                foreach ($startMealBreak01Query as $startMealBreak1) {
                    $startMealBreak01Array[$user->jjanID][$date2] = $startMealBreak1->punchTime;
                }

                // dd($startMealBreak01Array[$user->jjanID][$date2]);

                // Query - get the punch time for endMealBreak01 for single day ($date)
                $endMealBreak01Query = $endMealBreak01Query
                    ->where('records.punchType', 4)
                    ->where('records.punchTypePairNo', 1)
                    ->where('records.punchDate', $date2)
                    ->where('records.jjanID', $user->jjanID)
                    ->get();


                // get the punch time for startMealBreak01 for single day ($date)
                foreach ($endMealBreak01Query as $endMealBreak1) {
                    $endMealBreak01Array[$user->jjanID][$date2] = $endMealBreak1->punchTime;
                }

                // Query - get the punch time for startMealBreak02Query for single day ($date)
                $startMealBreak02Query = $startMealBreak02Query->where('punchType', 3)
                    ->where('records.punchTypePairNo', 2)
                    ->where('records.punchDate', $date2)
                    ->where('records.jjanID', $user->jjanID)
                    ->get();

                // get the punch time for endMealBreak02 for single day ($date)
                foreach ($startMealBreak02Query as $startMealBreak1) {
                    $startMealBreak02Array[$user->jjanID][$date] = $startMealBreak1->punchTime;
                }


                // Query - get the punch time for endMealBreak02Query for single day ($date)
                $endMealBreak02Query = $endMealBreak02Query->where('punchType', 4)
                    ->where('records.punchTypePairNo', 2)
                    ->where('records.punchDate', $date2)
                    ->where('records.jjanID', $user->jjanID)
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


                //  dd($startWorkArray['namjoong']['2016-12-27'] !== 0);

                if ($startWorkArray[$user->jjanID][$date2] !== 0 and $endWorkArray[$user->jjanID][$date2] !== 0) {

                    $totalWorkingMinutes[$user->jjanID][$date2] = round(($workingMinutes[$user->jjanID] - $mealBreakHours01[$user->jjanID] - $mealBreakHours02[$user->jjanID]), 2);

                    // sum all the minutes for the jjanID in this period and convert minutes to workingHours.
                    $workingHourArray[$user->jjanID] += round($totalWorkingMinutes[$user->jjanID][$date2] / 60, 2);

                    // dd($workingHourArray[$user->jjanID]);

                }
            }
        }

        if ($currentUrl === 'workingHours') {
            return view('workingHours.hourMain')
                ->with(compact(
                        'users'
                        , 'currentUrl'
                        , 'getSearchPeriod'
                        , 'getJJANID'
                        , 'getMemberName'
                        , 'workingHourArray'
                        , 'totalWorkingHours'
                    )
                );
        } elseif ($currentUrl === 'admin/workingHours') {
            return view('admin.workingHours.hourMain')
                ->with(compact(
                        'users'
                        , 'users2'
                        , 'currentUrl'
                        , 'getSearchPeriod'
                        , 'getJJANID'
                        , 'getMemberName'
                        , 'workingHourArray'
                        , 'totalWorkingHours'
                    )
                );
        }
    }


    public function displayForAdmin(Request $request)
    {
        $request->flash();
        $currentUrl = $request->path();
        $getSearchPeriod = $request->input('getSearchPeriod');
        $getJJANID = $request->input('getJJANID');
        $getMemberName = $request->input('getMemberName');

        // initiate $startindDate and $endingDate
        $startingDate = 0;
        $endingDate = 0;

        $currentJJANID = AUTH::user()->jjanID;


        // change the search period using dropdown menu.

        //set the $getSearchPeriod manually for testing purpose.
        //$getSearchPeriod = 'thisWeek';


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

        // for displaying jjanID, firstNm, lastNm in the table in the view.
        $users = DB::table('users')
            ->select(
                'users.jjanID'
                , 'users.firstNm'
                , 'users.lastNm'
            )
            ->get();


        //for dropdown menu in the search box.
        $users2 = DB::table('users')
            ->select(
                'users.jjanID'
                , 'users.firstNm'
                , 'users.lastNm'
            )
            ->get();

        //
        $punchRecords = DB::table('punchRecords as records')
            //  ->join('users','records.jjanID','=','users.jjanID')
            //  ->distinct()
            ->select(
                'records.jjanID'
                , 'records.punchTime'
                , 'records.punchDate'
                , 'records.punchType'
                , 'records.punchTypePairNo'
            );

        // dd($getJJANID);

        //set where Cluase with jjanID unless $getJJANID == '0'
        if ($getJJANID !== null and $getJJANID !== '0') {
            $users = $users->where('records.jjanID', $getJJANID);
            $punchRecords = $punchRecords
                ->where('records.jjanID', $getJJANID)
                ->where('records.punchDate', '>=', $startingDate)
                ->where('records.punchDate', '<=', $endingDate);
        }

        //   $sql = new GeneralPurpose;
        //   $sql = $sql->getSql($users);
        //   dd($sql);


        // add searchByMemberName
        //    $searchByMemberName = new GeneralPurpose;
        //    $users = $searchByMemberName->searchByMemberName($users, $getMemberName);

        // $sql = new GeneralPurpose;

        // dd($sql->getSql($punchRecords));


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
        //  $dateRangeArray = new GeneralPurpose;
        $dateRangeArray = $this->getDatesFromRange($startingDate, $endingDate);

        // looping users

        foreach ($users as $user) {

            // initiate working workingHours per user
            $workingHourArray[$user->jjanID] = 0;

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

                $totalWorkingMinutes[$user->jjanID][$date2] = 0;


                // Query -  get punch time for startWork for single day($date)
                $startWorkQuery = $startWorkQuery
                    ->where('records.punchType', 1)
                    ->where('records.punchDate', $date2)
                    ->where('records.jjanID', $user->jjanID)
                    ->get();

                // get the punch time from the query above.
                foreach ($startWorkQuery as $startWork1) {
                    $startWorkArray[$user->jjanID][$date2] = $startWork1->punchTime;

                }

                // dd($startWorkArray[$user->jjanID][$date2]);

                // Query - get the punch time for endWork for single day ($date)
                $endWorkQuery = $endWorkQuery
                    ->where('records.punchType', 2)
                    ->where('records.punchDate', $date2)
                    ->where('records.jjanID', $user->jjanID)
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
                    ->where('records.punchType', 3)
                    ->where('records.punchTypePairNo', 1)
                    ->where('records.punchDate', $date2)
                    ->where('records.jjanID', $user->jjanID)
                    ->get();

                // get the punch time for startMealBreak01 for single day ($date)
                foreach ($startMealBreak01Query as $startMealBreak1) {
                    $startMealBreak01Array[$user->jjanID][$date2] = $startMealBreak1->punchTime;
                }

                // dd($startMealBreak01Array[$user->jjanID][$date2]);

                // Query - get the punch time for endMealBreak01 for single day ($date)
                $endMealBreak01Query = $endMealBreak01Query
                    ->where('records.punchType', 4)
                    ->where('records.punchTypePairNo', 1)
                    ->where('records.punchDate', $date2)
                    ->where('records.jjanID', $user->jjanID)
                    ->get();


                // get the punch time for startMealBreak01 for single day ($date)
                foreach ($endMealBreak01Query as $endMealBreak1) {
                    $endMealBreak01Array[$user->jjanID][$date2] = $endMealBreak1->punchTime;
                }

                // Query - get the punch time for startMealBreak02Query for single day ($date)
                $startMealBreak02Query = $startMealBreak02Query->where('punchType', 3)
                    ->where('records.punchTypePairNo', 2)
                    ->where('records.punchDate', $date2)
                    ->where('records.jjanID', $user->jjanID)
                    ->get();

                // get the punch time for endMealBreak02 for single day ($date)
                foreach ($startMealBreak02Query as $startMealBreak1) {
                    $startMealBreak02Array[$user->jjanID][$date] = $startMealBreak1->punchTime;
                }


                // Query - get the punch time for endMealBreak02Query for single day ($date)
                $endMealBreak02Query = $endMealBreak02Query->where('punchType', 4)
                    ->where('records.punchTypePairNo', 2)
                    ->where('records.punchDate', $date2)
                    ->where('records.jjanID', $user->jjanID)
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


                //  dd($startWorkArray['namjoong']['2016-12-27'] !== 0);

                if ($startWorkArray[$user->jjanID][$date2] !== 0 and $endWorkArray[$user->jjanID][$date2] !== 0) {

                    $totalWorkingMinutes[$user->jjanID][$date2] = round(($workingMinutes[$user->jjanID] - $mealBreakHours01[$user->jjanID] - $mealBreakHours02[$user->jjanID]), 2);

                    // sum all the minutes for the jjanID in this period and convert minutes to workingHours.
                    $workingHourArray[$user->jjanID] += round($totalWorkingMinutes[$user->jjanID][$date2] / 60, 2);

                    // dd($workingHourArray[$user->jjanID]);

                }
            }
        }


        return view('workingHours.hourMain')
            ->with(compact(
                    'users'
                    , 'users2'
                    , 'currentUrl'
                    , 'getSearchPeriod'
                    , 'getJJANID'
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