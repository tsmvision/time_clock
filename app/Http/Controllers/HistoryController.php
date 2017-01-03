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
        $this->today = $today = Carbon::now()->format('Y-m-d');
        $this->dateTime = Carbon::now()->format('Y-m-d H:i:s');
        $this->currentTime = Carbon::now()->format('H:m:s');
        $this->punchType = [
            0 => 'N/A'
            , 1 => 'Start Work'
            , 2 => 'End Work'
            , 3 => 'Start Meal Break'
            , 4 => 'End Meal Break'
        ];
    }

    public function punchTypeName($punchType)
    {
        $punchTypeName = '';

        if ($punchType === 1) $punchTypeName = 'Starting Work';
        elseif ($punchType === 2 ) $punchTypeName = 'Ending Work';
        elseif ($punchType === 3 ) $punchTypeName = 'Leave Office';
        elseif ($punchType === 4) $punchTypeName = 'Back to Office';

        return $punchTypeName;
    }

    public function punchNow(Request $request, $punchType)
    {
        $request->flash();
        $currentUrl = $request->path();

        $today = Carbon::now()->format('Y-m-d');
        $currentTime = $this->currentTime;
        $currentUser = Auth::user()->jjanID;

        $numberOfPreviousStartWorkToday = PunchRecord::where('punchDate', $today)
            ->where('punchType', 1)
            ->where('jjanID', $currentUser)
            ->select('id')
            ->get()
            ->count();

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

            // when 'start meal break', if not existing meal history then insert 2 else 3. ???
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

        return redirect('clock')->with('message', 'Punch completed successfully!');

    }

    public function showList(Request $request)
    {
        $request->flash();
        $currentUrl = $request->path();
        $getSearchPeriod = $request->input('getSearchPeriod');
        $getJJANID = $request->input('getJJANID');
        $getMemberName = $request->input('getMemberName');

        $currentUser = Auth::user()->jjanID;
        $currentUserType = $this->currentUserType($currentUser);

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
                ->where('records.punchDate','>=',$startingDate)
                ->where('records.punchDate','<=',$endingDate)
                ->orderBy('records.punchDate', 'DESC')
                ->orderBy('records.punchTime', 'DESC')
                ;




        // history for general users
        if ($currentUrl === 'history')
        {
            $history = $history
                ->where('records.jjanID',$currentUser)
                ->get();

            foreach ($history as $history1) {
                $punchTypeName[$history1->id] = $this->punchTypeName($history1->punchType);
            }


            return view('history.historyMain')
                ->with(compact(
                    //  'users2'
                        'history'
                        , 'currentUrl'
                        , 'currentUserType'
                        , 'getSearchPeriod'
                        , 'getJJANID'
                        , 'getMemberName'
                        , 'punchType'
                        , 'punchTypeName'
                    )
                );


        }

        // for history for admin

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
                ->where('users.jjanID', $getJJANID)
            ;
        }

        // search by name
       $history = $this->searchByMemberName($history, $getMemberName)
                        ->get();

        foreach ($history as $history1) {
            $punchTypeName[$history1->id] = $this->punchTypeName($history1->punchType);
        }

        return view('admin.history.historyMain')
            ->with(compact(
                  'users2'
                    , 'currentUserType'
                    ,'history'
                    , 'currentUrl'
                    , 'getSearchPeriod'
                    , 'getJJANID'
                    , 'getMemberName'
                    , 'punchType'
                    , 'punchTypeName'
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
