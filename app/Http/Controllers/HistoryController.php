<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\PunchRecord;
use App\GeneralPurpose\GeneralPurpose;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    use GeneralPurpose;

    public $today;
    public $dateTime;
    public $currentTime;
    public $punchType;

    public function __construct()
    {

    }

    public function punchTypeName($punchType)
    {
        $punchTypeName = '';

        if ($punchType === 1) $punchTypeName = 'Starting Work';
        elseif ($punchType === 2) $punchTypeName = 'Ending Work';
        elseif ($punchType === 3) $punchTypeName = 'Leave Office';
        elseif ($punchType === 4) $punchTypeName = 'Back to Office';

        return $punchTypeName;
    }

    public function punchNow(Request $request)
    {
        $request->flash();
        $currentUrl = $request->path();

        $today = Carbon::now()->format('Y-m-d');

        $currentTime = Carbon::now()->format('H:i:s');

        $currentUser = Auth::user()->jjanID;

        $user = new PunchRecord;
        $user->jjanID = $currentUser;
        $user->punchTime = $currentTime;
        $user->punchDate = $today;
        $user->save();

        return redirect('clock')->with('message', 'Punch completed successfully!');
    }

    public function punchNow02(Request $request)
    {

        // $request->flash();
        // $currentUrl = $request->path();

        $today = Carbon::now()->format('Y-m-d');
        $currentTime = $this->currentTime;
        $currentUser = Auth::user()->jjanID;

        $punchHistory = PunchRecord::where('punchDate', $today)
            ->where('jjanID', $currentUser)
            ->select('id', 'jjanID', 'punchDate', 'punchTime')
            ->orderBy('punchTime')
            ->get();

        $numberOfPunchToday = PunchRecord::where('punchDate', $today)
            ->where('jjanID', $currentUser)
            ->select('id')
            ->get()
            ->count();

        $punchHistory2 = [];

        $i = 1;
        foreach ($punchHistory as $punchHistory1) {
            $punchHistory2[] = [
                'id' => $punchHistory1->id
                , 'jjanID' => $punchHistory1->jjanID
                , 'punchDate' => $punchHistory1->punchDate
                , 'punchTime' => $punchHistory1->punchTime
                , 'order' => $i++
            ];

        }

        if ($numberOfPunchToday <= 2) {
            return 'incomplete today';
        }

        /*

        $numberOfPreviousEndWorkToday = PunchRecord::where('punchDate', $today)
            ->where('punchType', 2)
            ->where('jjanID', $currentUser)
            ->select('id')
            ->get()
            ->count();

        $numberOfPreviousStartMealBreakToday = PunchRecord::where('punchDate', $today)
            ->where('punchType', 3)
            ->where('jjanID', $currentUser)
            ->select('id')
            ->get()
            ->count();

        $numberOfPreviousEndMealBreakToday = PunchRecord::where('punchDate', $today)
            ->where('punchType', 4)
            ->where('jjanID', $currentUser)
            ->select('id')
            ->get()
            ->count();

        // dd($numberOfPreviousStartWorkToday, $numberOfPreviousEndWorkToday, $numberOfPreviousStartMealBreakToday, $numberOfPreviousEndMealBreakToday);

        $user = new PunchRecord;
        $user->jjanID = $currentUser;

        // when start work, insert 0 to punchTypePairNo
        if ($punchType === '1') {

            // if start work is already registered today, then got error.
            if ($numberOfPreviousStartWorkToday !== 0) {
                return redirect('clock')->with('message1', 'Duplicated Start Work');
            }

            $user->punchTypePairNo = 0;

            // when end Work, insert 1 to punchTypePairNo that means start work and end work using same pair No.
        } elseif ($punchType === '2') {

            if ($numberOfPreviousStartWorkToday === 0) {
                return redirect('clock')->with('message1', 'Start Work not registered yet');
            } elseif ($numberOfPreviousEndWorkToday !== 0) {
                return redirect('clock')->with('message1', 'Duplicated End Work');
            }

            $user->punchTypePairNo = 0;

            // when 'start meal break', if not existing meal history03 then insert 2 else 3. ???
        } elseif ($punchType === '3') {

            if ($numberOfPreviousStartWorkToday === 0) {
                return redirect('clock')->with('message1', 'Start Work not registered yet');
            } elseif ($numberOfPreviousEndWorkToday === 1) {
                return redirect('clock')->with('message1', 'End Work registered already');
            } //when number of start meal and end meal doesn't matched then get error.
            elseif ($numberOfPreviousStartMealBreakToday !== $numberOfPreviousEndMealBreakToday) {
                return redirect('clock')->with('message1', 'No End Meal registered yet');

            } elseif ($numberOfPreviousStartMealBreakToday === 6) {
                return redirect('clock')->with('message1', 'No More Meal Break Registration available');
            }

            $user->punchTypePairNo = $numberOfPreviousStartMealBreakToday + 1;

        } elseif ($punchType === '4') {

            if ($numberOfPreviousStartWorkToday === 0) {
                return redirect('clock')->with('message1', 'Start Work not registered yet');

            } elseif ($numberOfPreviousEndWorkToday === 1) {
                return redirect('clock')->with('message1', 'End Work registered already');
            } elseif ($numberOfPreviousStartMealBreakToday === $numberOfPreviousEndMealBreakToday) {
                return redirect('clock')->with('message1', 'Start Meal not registered yet');
            } else {
                $user->punchTypePairNo = $numberOfPreviousStartMealBreakToday;
            }
        }

        $user->punchTime = $currentTime;
        $user->punchDate = $today;
        $user->punchType = $punchType;

        $user->save();


        $request->session()->flash('alert-success', 'Punch is successfully completed.');
*/
        return redirect('clock')->with('message', 'Punch completed successfully!');

    }

    public function showList03(Request $request)
    {
        $request->flash();
        $currentUrl = $request->path();
        $getSearchPeriod = $request->input('getSearchPeriod');
        $getJJANID = $request->input('getJJANID');
        $getMemberName = $request->input('getMemberName');

        $currentUserJJANID = Auth::user()->jjanID;
        $currentUserInfo = $this->currentUserInfo($currentUserJJANID);

        //$startingDate = 0;
        //$endingDate = 0;

        $punchType = $this->punchType;

        $history = DB::table('punchRecords as records ')
            ->join('users', 'records.jjanID', '=', 'users.jjanID')
            ->distinct()
            //  ->where('records.jjanID',$currentUser)
            ->select(
                'records.id'
                , 'records.jjanID'
                , 'users.firstNm'
                , 'users.lastNm'
                , 'records.punchTime'
                , 'records.punchDate'
                , 'records.punchType'
            );

        $searchPeriod = $this->searchPeriod($getSearchPeriod);

        $startingDate = $searchPeriod['startingDate'];
        $endingDate = $searchPeriod['endingDate'];

        // dd($startingDate, $endingDate);

        $history = $history
            ->where('records.punchDate', '>=', $startingDate)
            ->where('records.punchDate', '<=', $endingDate)
            ->orderBy('records.punchDate', 'DESC')
            ->orderBy('records.punchTime', 'DESC');


        // history03 for general users
        if ($currentUrl === 'history03') {
            $history = $history
                ->where('records.jjanID', $currentUserJJANID)
                ->get();

            foreach ($history as $history1) {
                $punchTypeName[$history1->id] = $this->punchTypeName($history1->punchType);
            }


            return view('history03.historyMain')
                ->with(compact(
                    //  'users2'
                        'history'
                        , 'currentUrl'
                        , 'currentUserInfo'
                        , 'getSearchPeriod'
                        , 'getJJANID'
                        , 'getMemberName'
                        , 'punchType'
                        , 'punchTypeName'
                    )
                );


        }

        // for history03 for admin

        //for dropdown menu in the search box.
        $users2 = DB::table('users')
            ->select(
                'users.jjanID'
                , 'users.firstNm'
                , 'users.lastNm'
            )
            ->get();

        if ($getJJANID !== null and $getJJANID !== '0') {
            $history = $history
                ->where('users.jjanID', $getJJANID);
        }

        // search by name
        $history = $this->searchByMemberName($history, $getMemberName)
            ->get();

        foreach ($history as $history1) {
            $punchTypeName[$history1->id] = $this->punchTypeName($history1->punchType);
        }

        return view('admin.history03.historyMain')
            ->with(compact(
                    'users2'
                    , 'currentUserInfo'
                    , 'history'
                    , 'currentUrl'
                    , 'getSearchPeriod'
                    , 'getJJANID'
                    , 'getMemberName'
                    , 'punchType'
                    , 'punchTypeName'
                )
            );


    }

    public function showList(Request $request)
    {
        $request->flash();
        $currentUrl = $request->path();
        $getSearchPeriod = $request->input('getSearchPeriod');
        $getJJANID = $request->input('getJJANID');
        $getMemberName = $request->input('getMemberName');

        $currentUserJJANID = Auth::user()->jjanID;
        $currentUserInfo = $this->currentUserInfo($currentUserJJANID);

        //$startingDate = 0;
        //$endingDate = 0;

        $searchPeriod = $this->searchPeriod($getSearchPeriod);

        $startingDate = $searchPeriod['startingDate'];
        $endingDate = $searchPeriod['endingDate'];

        $history = DB::table('punchRecords as records ')
            ->join('users', 'records.jjanID', '=', 'users.jjanID')
            ->distinct()
            ->where('records.jjanID', $currentUserJJANID)
            ->where('records.punchDate', '>=', $startingDate)
            ->where('records.punchDate', '<=', $endingDate)
            ->select(
                'records.id'
                , 'records.jjanID'
                , 'users.firstNm'
                , 'users.lastNm'
                , 'records.punchTime'
                , 'records.punchDate'
            )
            ->orderBy('punchDate')
            ->orderBy('punchTime')
            ->get()
            ;

        //dd($startingDate, $endingDate);

        $historyArray = [];

        $i = 1;
        $date = 0;
        foreach ($history as $history1) {
            if ($date === 0 or $date !== $history1->punchDate)
                $i = 1;

            $historyArray[] = [
                'id' => $history1->id
                , 'jjanID' => $history1->jjanID
                , 'firstNm' => $history1->firstNm
                , 'lastNm' => $history1->lastNm
                , 'punchDate' => $history1->punchDate
                , 'punchTime' => $history1->punchTime
                , 'dailyOrder' => $i++
            ];

            $date = $history1->punchDate;
        }

        $history = collect($historyArray);

        return view('history.historyMain')
            ->with(compact(
                //  'users2'
                    'history'
                    , 'currentUrl'
                    , 'currentUserInfo'
                    , 'getSearchPeriod'
                    , 'getJJANID'
                    , 'getMemberName'
                )
            );

    }

    public function showListForAdmin(Request $request)
    {
        $request->flash();
        $currentUrl = $request->path();
        $getSearchPeriod = $request->input('getSearchPeriod');
        $getJJANID = $request->input('getJJANID');
        $getMemberName = $request->input('getMemberName');

        $currentUserJJANID = Auth::user()->jjanID;
        $currentUserInfo = $this->currentUserInfo($currentUserJJANID);

        //$startingDate = 0;
        //$endingDate = 0;

        $searchPeriod = $this->searchPeriod($getSearchPeriod);

        $startingDate = $searchPeriod['startingDate'];
        $endingDate = $searchPeriod['endingDate'];

        $history = DB::table('punchRecords as records ')
            ->join('users', 'records.jjanID', '=', 'users.jjanID')
            ->distinct()
            ->where('records.jjanID', $currentUserJJANID)
            ->where('records.punchDate', '>=', $startingDate)
            ->where('records.punchDate', '<=', $endingDate)
            ->select(
                'records.id'
                , 'records.jjanID'
                , 'users.firstNm'
                , 'users.lastNm'
                , 'records.punchTime'
                , 'records.punchDate'
            )
            ->orderBy('punchDate')
            ->orderBy('punchTime')
            ->get()
        ;

        //dd($startingDate, $endingDate);

        $historyArray = [];

        $i = 1;
        $date = 0;
        foreach ($history as $history1) {
            if ($date === 0 or $date !== $history1->punchDate)
                $i = 1;

            $historyArray[] = [
                'id' => $history1->id
                , 'jjanID' => $history1->jjanID
                , 'firstNm' => $history1->firstNm
                , 'lastNm' => $history1->lastNm
                , 'punchDate' => $history1->punchDate
                , 'punchTime' => $history1->punchTime
                , 'dailyOrder' => $i++
            ];

            $date = $history1->punchDate;
        }

        $history = collect($historyArray);

        return view('admin.history.historyMain')
            ->with(compact(
                //  'users2'
                    'history'
                    , 'currentUrl'
                    , 'currentUserInfo'
                    , 'getSearchPeriod'
                    , 'getJJANID'
                    , 'getMemberName'
                )
            );

    }



    public function update(Request $request)
    {
        $request->flash();
        //  $currentUrl = $request->path();

        $id = $request->input('getID');

        $punchTime = $request->input('punchTime');
        $punchTime = Carbon::parse($punchTime)->format('H:i:s');

        if ($punchTime === null or $punchTime === '') {
            return redirect('/history')->with('message', 'No Changes!');
        }

        $punchRecords = PunchRecord::find($id);

        $punchRecords->punchTime = Carbon::parse($punchTime)->format('H:i:s');
        $punchRecords->save();

        return redirect('/history')->with('message', 'Updated!');
    }


    public function delete($id)
    {

        $punchRecord = PunchRecord::find($id);

        $punchRecord->delete();


        return redirect('/history')->with('message', 'deleted!');
    }

    public function add(Request $request)
    {
        $request->flash();
        //  $currentUrl = $request->path();

        $id = $request->input('getID');
        $currentUserJJANID = Auth::user()->jjanID;
        $date = $request->input('getDate');
        $time = $request->input('getTime');

        $today = Carbon::now()->format('Ymd');
        $date2 = Carbon::parse($date)->format('Ymd');

        $date = Carbon::parse($date)->format('Y-m-d');
        $time = Carbon::parse($time)->format('H:i:s');

        if ($date2 > $today)
            return redirect('/history')->with('message', 'You are not allowed to punch date & time in advance');


        $punchRecords = new PunchRecord;
        $punchRecords->jjanID = $currentUserJJANID;
        $punchRecords->punchDate = $date;
        $punchRecords->punchTime = $time;
        $saved = $punchRecords->save();

        if (!$saved)
        {
            return 'Not Saved';
        }


        return redirect('/history')->with('message', 'Created!');

    }


}
