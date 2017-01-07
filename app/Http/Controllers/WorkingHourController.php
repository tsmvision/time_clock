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

    public function mainQuery($startingDate, $endingDate)
    {
        $punchRecords = DB::table('punchRecords as records')
            //  ->join('users','records.jjanID','=','users.jjanID')
            //  ->distinct()
            ->select(
                'jjanID'
                , 'punchDate'
                , DB::raw("(CASE WHEN punchType=1 THEN punchTime ELSE 0 END) as startWork")  // 0 is character
                , DB::raw("(CASE WHEN punchType=2 THEN punchTime ELSE 0 END) as endWork")
                , DB::raw("(CASE WHEN punchType=3 and punchTypePairNo =1 THEN punchTime ELSE 0 END) as startMealBreak01")
                , DB::raw("(CASE WHEN punchType=4 and punchTypePairNo =1 THEN punchTime ELSE 0 END) as endMealBreak01")
                , DB::raw("(CASE WHEN punchType=3 and punchTypePairNo =2 THEN punchTime ELSE 0 END) as startMealBreak02")
                , DB::raw("(CASE WHEN punchType=4 and punchTypePairNo =2 THEN punchTime ELSE 0 END) as endMealBreak02")
            )
            ->where('records.punchDate', '>=', $startingDate)
            ->where('records.punchDate', '<=', $endingDate);

        return $punchRecords;
    }

    /*
    public function insertValueToArray($query,$date,$jjanID)
    {


        // add the values to result if the value is not '0' from query
        foreach ($query as $query1) {

            if ($query1->startWork !== '0')
                $result[$user->jjanID][$date]['startWork'] = $query1->startWork;

            if ($query1->endWork !== '0')
                $result[$user->jjanID][$date]['endWork'] = $query1->endWork;

            if ($query1->startMealBreak01 !== '0')
                $result[$user->jjanID][$date]['startMealBreak01'] = $query1->startMealBreak01;

            if ($query1->endMealBreak01 !== '0')
                $result[$user->jjanID][$date]['endMealBreak01'] = $query1->endMealBreak01;

            if ($query1->startMealBreak02 !== '0')
                $result[$user->jjanID][$date]['startMealBreak02'] = $query1->startMealBreak02;

            if ($query1->endMealBreak02 !== '0')
                $result[$user->jjanID][$date]['endMealBreak02'] = $query1->endMealBreak02;
        }

        return $result;
    }
    */

    public function diffInMinutes($startTime, $endTime)
    {
        return Carbon::parse($startTime)->diffInMinutes(Carbon::parse($endTime));
    }

    public function showList(Request $request)
    {
        $request->flash();
        $currentUrl = $request->path();
        $getSearchPeriod = $request->input('getSearchPeriod');

        $currentUserJJANID = AUTH::user()->jjanID;
        $currentUserInfo = $this->currentUserInfo($currentUserJJANID);

        // change the search period using dropdown menu.

        $searchPeriod = $this->searchPeriod($getSearchPeriod);
        $startingDate = $searchPeriod['startingDate'];
        $endingDate = $searchPeriod['endingDate'];

        $startingDate = '2016-12-01';
        $endingDate = '2017-01-31';

        // for displaying jjanID, firstNm, lastNm in the table in the view.

        $punchRecords = DB::table('punchRecords as record')
                            ->join('users','record.jjanID','=','users.jjanID')
                           // ->distinct()
                            ->where('record.jjanID',$currentUserJJANID)
                            ->where('record.punchDate','>=', $startingDate)
                            ->where('record.punchDate','<=', $endingDate)
                            ->select(
                                'record.jjanID'
                                ,'record.punchDate'
                                ,'record.punchTime'
                                )
                            ->groupBy('record.jjanID','record.punchDate','record.punchTime')
                            ->get()
                            ;

        // create each days in between $startingDate and $endingDate
        $dateRangeArray = $this->getDatesFromRange($startingDate, $endingDate);
        //
        $result = [];
        //

        dd($punchRecords->where('punchDate',$punchRecords)
                ->min('punchTime'));

        // looping users

        $dailyOrderNo = 0;
        $currentDate = 0;

        foreach ($dateRangeArray as $index => $date) {
            $date2 = Carbon::parse($date)->format('Y-m-d');


            $result[] = [
                'jjanID' => $currentUserJJANID
                , 'date' => $date
                , 'date2' => $date2
                , 'beginWork' => $punchRecords->where('punchDate',$date2)
                                    ->where('punchDate',$punchRecords
                                    ->min('punchTime'))
                , 'endWork' => $punchRecords->where('punchDate',$date2)->where('punchDate',$punchRecords->max('punchTime'))
                , 'startrBreak' => 0
                , 'endBreak' => 0
                , 'workingMinutes' => 0
                , 'totalWorkingMin' => 0
                , 'dailyOrderNo' => 1
            ];

        }

        dd($result);


/*
            if ($dailyOrderNo === 0 and $date === $currentDate)
                $dailyOrderNo++;
            else $dailyOrderNo = 1;

            // convert date format from Ymd to Y-m-d to fit the MariaDB date format.
            $date2 = Carbon::parse($date)->format('Y-m-d');



            $currentDate = $date;
        }

        dd($result);
*/
        /*

        // Query -  get punch time for startWork for single day($date)

        $query = $this->mainQuery($startingDate, $endingDate)
            ->where('records.punchDate', $date2)
            ->where('records.jjanID', $user->jjanID)
            ->get();

        // add the values to result if the value is not '0' from query
        foreach ($query as $query1) {

            if ($query1->startWork !== '0')
                $result[$user->jjanID][$date]['startWork'] = $query1->startWork;

            if ($query1->endWork !== '0')
                $result[$user->jjanID][$date]['endWork'] = $query1->endWork;

            if ($query1->startMealBreak01 !== '0')
                $result[$user->jjanID][$date]['startMealBreak01'] = $query1->startMealBreak01;

            if ($query1->endMealBreak01 !== '0')
                $result[$user->jjanID][$date]['endMealBreak01'] = $query1->endMealBreak01;

            if ($query1->startMealBreak02 !== '0')
                $result[$user->jjanID][$date]['startMealBreak02'] = $query1->startMealBreak02;

            if ($query1->endMealBreak02 !== '0')
                $result[$user->jjanID][$date]['endMealBreak02'] = $query1->endMealBreak02;
        }

        // count as valid minutes only when StartWork and endWork, both of them punched.

        if ($result[$user->jjanID][$date]['startWork'] !== 0 and $result[$user->jjanID][$date]['endWork'] !== 0) {
            $result[$user->jjanID][$date]['workingMin'] =
                $this->diffInMinutes($result[$user->jjanID][$date]['startWork']
                    , $result[$user->jjanID][$date]['endWork']);
        }

        // if $startMealBreak01Array's value and $endMealBreak01Array's value exist then calculate otherwise set to 0.
        if ($result[$user->jjanID][$date]['startMealBreak01'] !== 0 and $result[$user->jjanID][$date]['endMealBreak01'] !== 0) {
            $result[$user->jjanID][$date]['mealBreak01Min'] = $this->diffInMinutes(
                $result[$user->jjanID][$date]['startMealBreak01']
                , $result[$user->jjanID][$date]['endMealBreak01']
            );
        }

        // if startMealBreak02 and endMealBreak02, both of them are not 0 then calculate the value, otherwise set to 0
        if ($result[$user->jjanID][$date]['startMealBreak02'] !== 0 and $result[$user->jjanID][$date]['endMealBreak02'] !== 0) {
            $result[$user->jjanID][$date]['mealBreak02Min']
                = $this->diffInMinutes($result[$user->jjanID][$date]['startMealBreak02'], $result[$user->jjanID][$date]['endMealBreak02']);
        }

        // if endWork - startWork != 0
        if ($result[$user->jjanID][$date]['workingMin'] !== 0) {
            $result[$user->jjanID][$date]['totalWorkingMin'] =
                $result[$user->jjanID][$date]['workingMin']
                - $result[$user->jjanID][$date]['mealBreak01Min']
                - $result[$user->jjanID][$date]['mealBreak02Min'];
        }

        // reduce the array dimensions to use collection helpers.
        $result2[] = $result[$user->jjanID][$date];
    }

    // convert $result array to collection to use collection helpers.
$result3 = collect($result2);

    // calculate working hours per user.
$totalWorkingHours[$user->jjanID] = round($result3->where('jjanID', $user->jjanID)->sum('totalWorkingMin') / 60, 2);
}
        */

// for workingHours for general user
return view('workingHours.hourMain')
    ->with(compact(
            'users'
            , 'currentUserInfo'
            , 'currentUrl'
            , 'getSearchPeriod'
            , 'getJJANID'
            , 'getMemberName'
            , 'result'
            , 'totalWorkingHours'
        )
    );

}

public
function showList2(Request $request)
{
    $request->flash();
    $currentUrl = $request->path();
    $getSearchPeriod = $request->input('getSearchPeriod');
    $getJJANID = $request->input('getJJANID');
    $getMemberName = $request->input('getMemberName');

    $currentUserJJANID = AUTH::user()->jjanID;
    $currentUserInfo = $this->currentUserInfo($currentUserJJANID);

    // change the search period using dropdown menu.

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
        ->where('jjanID', $currentUserJJANID)
        ->get();

    //set where Cluase with jjanID unless $getJJANID == '0'
    if ($currentUrl == 'admin/workingHours') {
        if ($getJJANID !== null and $getJJANID !== '0')
            $users = $users->where('records.jjanID', $getJJANID);

        $users2 = DB::table('users')
            ->select(
                'users.jjanID'
                , 'users.firstNm'
                , 'users.lastNm'
            )
            ->get();
    }

    // add searchByMemberName
    //    $searchByMemberName = new GeneralPurpose;
    $users = $this->searchByMemberName($users, $getMemberName);

    // create each days in between $startingDate and $endingDate
    $dateRangeArray = $this->getDatesFromRange($startingDate, $endingDate);
    //
    $result = [];
    //
    $result2 = [];

    // looping users

    foreach ($users as $user) {
        $totalWorkingHours[$user->jjanID] = 0;

        foreach ($dateRangeArray as $index => $date) {

            // convert date format from Ymd to Y-m-d to fit the MariaDB date format.
            $date2 = Carbon::parse($date)->format('Y-m-d');

            $result[$user->jjanID][$date] = [
                'jjanID' => $user->jjanID
                , 'date' => $date
                , 'startWork' => 0
                , 'endWork' => 0
                , 'startMealBreak01' => 0
                , 'endMealBreak01' => 0
                , 'startMealBreak02' => 0
                , 'endMealBreak02' => 0
                , 'workingMin' => 0
                , 'mealBreak01Min' => 0
                , 'mealBreak02Min' => 0
                , 'totalWorkingMin' => 0
            ];

            // Query -  get punch time for startWork for single day($date)

            $query = $this->mainQuery($startingDate, $endingDate)
                ->where('records.punchDate', $date2)
                ->where('records.jjanID', $user->jjanID)
                ->get();

            // add the values to result if the value is not '0' from query
            foreach ($query as $query1) {

                if ($query1->startWork !== '0')
                    $result[$user->jjanID][$date]['startWork'] = $query1->startWork;

                if ($query1->endWork !== '0')
                    $result[$user->jjanID][$date]['endWork'] = $query1->endWork;

                if ($query1->startMealBreak01 !== '0')
                    $result[$user->jjanID][$date]['startMealBreak01'] = $query1->startMealBreak01;

                if ($query1->endMealBreak01 !== '0')
                    $result[$user->jjanID][$date]['endMealBreak01'] = $query1->endMealBreak01;

                if ($query1->startMealBreak02 !== '0')
                    $result[$user->jjanID][$date]['startMealBreak02'] = $query1->startMealBreak02;

                if ($query1->endMealBreak02 !== '0')
                    $result[$user->jjanID][$date]['endMealBreak02'] = $query1->endMealBreak02;
            }

            // count as valid minutes only when StartWork and endWork, both of them punched.

            if ($result[$user->jjanID][$date]['startWork'] !== 0 and $result[$user->jjanID][$date]['endWork'] !== 0) {
                $result[$user->jjanID][$date]['workingMin'] =
                    $this->diffInMinutes($result[$user->jjanID][$date]['startWork']
                        , $result[$user->jjanID][$date]['endWork']);
            }

            // if $startMealBreak01Array's value and $endMealBreak01Array's value exist then calculate otherwise set to 0.
            if ($result[$user->jjanID][$date]['startMealBreak01'] !== 0 and $result[$user->jjanID][$date]['endMealBreak01'] !== 0) {
                $result[$user->jjanID][$date]['mealBreak01Min'] = $this->diffInMinutes(
                    $result[$user->jjanID][$date]['startMealBreak01']
                    , $result[$user->jjanID][$date]['endMealBreak01']
                );
            }

            // if startMealBreak02 and endMealBreak02, both of them are not 0 then calculate the value, otherwise set to 0
            if ($result[$user->jjanID][$date]['startMealBreak02'] !== 0 and $result[$user->jjanID][$date]['endMealBreak02'] !== 0) {
                $result[$user->jjanID][$date]['mealBreak02Min']
                    = $this->diffInMinutes($result[$user->jjanID][$date]['startMealBreak02'], $result[$user->jjanID][$date]['endMealBreak02']);
            }

            // if endWork - startWork != 0
            if ($result[$user->jjanID][$date]['workingMin'] !== 0) {
                $result[$user->jjanID][$date]['totalWorkingMin'] =
                    $result[$user->jjanID][$date]['workingMin']
                    - $result[$user->jjanID][$date]['mealBreak01Min']
                    - $result[$user->jjanID][$date]['mealBreak02Min'];
            }

            // reduce the array dimensions to use collection helpers.
            $result2[] = $result[$user->jjanID][$date];
        }

        // convert $result array to collection to use collection helpers.
        $result3 = collect($result2);

        // calculate working hours per user.
        $totalWorkingHours[$user->jjanID] = round($result3->where('jjanID', $user->jjanID)->sum('totalWorkingMin') / 60, 2);
    }

    // for workingHours for general user
    return view('workingHours.hourMain')
        ->with(compact(
                'users'
                , 'currentUserInfo'
                , 'currentUrl'
                , 'getSearchPeriod'
                , 'getJJANID'
                , 'getMemberName'
                , 'result'
                , 'totalWorkingHours'
            )
        );

}

public
function adminShowList(Request $request)
{
    $request->flash();
    $currentUrl = $request->path();
    $getSearchPeriod = $request->input('getSearchPeriod');
    $getJJANID = $request->input('getJJANID');
    $getMemberName = $request->input('getMemberName');

    $currentUserJJANID = AUTH::user()->jjanID;
    $currentUserInfo = $this->currentUserInfo($currentUserJJANID);

    // change the search period using dropdown menu.

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
        //   ->where('jjanID', $currentJJANID)
        ->get();

    //set where Cluase with jjanID unless $getJJANID == '0'

    if ($getJJANID !== null and $getJJANID !== '0')
        $users = $users->where('records.jjanID', $getJJANID);

    $users2 = DB::table('users')
        ->select(
            'users.jjanID'
            , 'users.firstNm'
            , 'users.lastNm'
        )
        ->get();

    // add searchByMemberName
    //    $searchByMemberName = new GeneralPurpose;
    $users = $this->searchByMemberName($users, $getMemberName);

    // create each days in between $startingDate and $endingDate
    $dateRangeArray = $this->getDatesFromRange($startingDate, $endingDate);
    //
    $result = [];
    //
    $result2 = [];

    // looping users

    foreach ($users as $user) {
        $totalWorkingHours[$user->jjanID] = 0;

        foreach ($dateRangeArray as $index => $date) {

            // convert date format from Ymd to Y-m-d to fit the MariaDB date format.
            $date2 = Carbon::parse($date)->format('Y-m-d');

            $result[$user->jjanID][$date] = [
                'jjanID' => $user->jjanID
                , 'date' => $date
                , 'startWork' => 0
                , 'endWork' => 0
                , 'startMealBreak01' => 0
                , 'endMealBreak01' => 0
                , 'startMealBreak02' => 0
                , 'endMealBreak02' => 0
                , 'workingMin' => 0
                , 'mealBreak01Min' => 0
                , 'mealBreak02Min' => 0
                , 'totalWorkingMin' => 0
            ];

            // Query -  get punch time for startWork for single day($date)

            $query = $this->mainQuery($startingDate, $endingDate)
                ->where('records.punchDate', $date2)
                ->where('records.jjanID', $user->jjanID)
                ->get();

            // add the values to result if the value is not '0' from query
            foreach ($query as $query1) {

                if ($query1->startWork !== '0')
                    $result[$user->jjanID][$date]['startWork'] = $query1->startWork;

                if ($query1->endWork !== '0')
                    $result[$user->jjanID][$date]['endWork'] = $query1->endWork;

                if ($query1->startMealBreak01 !== '0')
                    $result[$user->jjanID][$date]['startMealBreak01'] = $query1->startMealBreak01;

                if ($query1->endMealBreak01 !== '0')
                    $result[$user->jjanID][$date]['endMealBreak01'] = $query1->endMealBreak01;

                if ($query1->startMealBreak02 !== '0')
                    $result[$user->jjanID][$date]['startMealBreak02'] = $query1->startMealBreak02;

                if ($query1->endMealBreak02 !== '0')
                    $result[$user->jjanID][$date]['endMealBreak02'] = $query1->endMealBreak02;
            }

            // count as valid minutes only when StartWork and endWork, both of them punched.

            if ($result[$user->jjanID][$date]['startWork'] !== 0 and $result[$user->jjanID][$date]['endWork'] !== 0) {
                $result[$user->jjanID][$date]['workingMin'] =
                    $this->diffInMinutes($result[$user->jjanID][$date]['startWork']
                        , $result[$user->jjanID][$date]['endWork']);
            }

            // if $startMealBreak01Array's value and $endMealBreak01Array's value exist then calculate otherwise set to 0.
            if ($result[$user->jjanID][$date]['startMealBreak01'] !== 0 and $result[$user->jjanID][$date]['endMealBreak01'] !== 0) {
                $result[$user->jjanID][$date]['mealBreak01Min'] = $this->diffInMinutes(
                    $result[$user->jjanID][$date]['startMealBreak01']
                    , $result[$user->jjanID][$date]['endMealBreak01']
                );
            }

            // if startMealBreak02 and endMealBreak02, both of them are not 0 then calculate the value, otherwise set to 0
            if ($result[$user->jjanID][$date]['startMealBreak02'] !== 0 and $result[$user->jjanID][$date]['endMealBreak02'] !== 0) {
                $result[$user->jjanID][$date]['mealBreak02Min']
                    = $this->diffInMinutes($result[$user->jjanID][$date]['startMealBreak02'], $result[$user->jjanID][$date]['endMealBreak02']);
            }

            // if endWork - startWork != 0
            if ($result[$user->jjanID][$date]['workingMin'] !== 0) {
                $result[$user->jjanID][$date]['totalWorkingMin'] =
                    $result[$user->jjanID][$date]['workingMin']
                    - $result[$user->jjanID][$date]['mealBreak01Min']
                    - $result[$user->jjanID][$date]['mealBreak02Min'];
            }

            // reduce the array dimensions to use collection helpers.
            $result2[] = $result[$user->jjanID][$date];
        }

        // convert $result array to collection to use collection helpers.
        $result3 = collect($result2);

        // calculate working hours per user.
        $totalWorkingHours[$user->jjanID] = round($result3->where('jjanID', $user->jjanID)->sum('totalWorkingMin') / 60, 2);
    }

    // for workingHours for admin.
    return view('admin.workingHours.hourMain')
        ->with(compact(
                'users'
                , 'users2'
                , 'currentUserInfo'
                , 'currentUrl'
                , 'getSearchPeriod'
                , 'getJJANID'
                , 'getMemberName'
                , 'result'
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