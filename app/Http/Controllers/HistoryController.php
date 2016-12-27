<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\PunchRecord;

class HistoryController extends Controller
{
    public $dateTime;
    public $currentTime;
    public $punchType;

    public function __construct()
    {
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

    public function punchNow(Request $request, $punchType)
    {
        $request->flash();
        $currentUrl = $request->path();

        $today = Carbon::now()->format('Y-m-d');
        $currentTime = Carbon::now()->format('h:i:s');

        $numberOfPreviousStartWorkToday = PunchRecord::where('punchDate',$today)
            ->where('punchType', 1)
            ->where('jjanID', 'namjoong')
            ->select('id')
            ->get()
            ->count();

        $numberOfPreviousEndWorkToday = PunchRecord::where('punchDate',$today)
            ->where('punchType', 2)
            ->where('jjanID', 'namjoong')
            ->select('id')
            ->get()
            ->count();

        $numberOfPreviousStartMealBreakToday = PunchRecord::where('punchDate',$today)
            ->where('punchType', 3)
            ->where('jjanID', 'namjoong')
            ->select('id')
            ->get()
            ->count();

        $numberOfPreviousEndMealBreakToday = PunchRecord::where('punchDate', $today)
            ->where('punchType', 4)
            ->where('jjanID', 'namjoong')
            ->select('id')
            ->get()
            ->count();

        // dd($numberOfPreviousStartWorkToday, $numberOfPreviousEndWorkToday, $numberOfPreviousStartMealBreakToday, $numberOfPreviousEndMealBreakToday);

        $user = new PunchRecord;
        $user->jjanID = 'namjoong';

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

            } elseif ($numberOfPreviousStartMealBreakToday === 2) {
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

        public function display(Request $request)
        {
            $request->flash();
            $currentUrl = $request->path();
            $getSearchPeriod = $request->input('getSearchPeriod');
            $getMemberName = $request->input('getMemberName');


            $today = Carbon::now()->format('Y-m-d');

            $month = Carbon::now()->format('m');
            $year = Carbon::now()->format('Y');
            $lastMonth = Carbon::now()->subMonth()->format('m');

            $punchType = $this->punchType;

            $history = DB::table('punchRecords as records ')
                ->join('users', 'records.jjanID', '=', 'users.jjanID')
                ->distinct()
                ->select(
                    'records.id'
                    , 'records.jjanID'
                    , 'users.firstNm'
                    , 'users.lastNm'
                    , 'records.punchTime'
                    , 'records.punchDate'
                    , 'records.punchType'
                );


            // dd($history->get()->all());

            if ($getSearchPeriod === null || $getSearchPeriod === 'today') {
                $history = $history
                    ->where('punchDate',$today);


            } elseif ($getSearchPeriod === 'thisMonth') {
                $history = $history
                    ->whereRaw("MONTH(records.punchTime) = $month")
                    ->whereRaw("YEAR(records.punchTime) = $year");

            } elseif ($getSearchPeriod === 'lastMonth') {
                $history = $history
                    ->whereRaw("MONTH(records.punchTime) = $lastMonth")
                    ->whereRaw("YEAR(records.punchTime) = $year");

            } elseif ($getSearchPeriod === 'customPeriod') {

            }

            $history = $history
                ->orderBy('records.punchDate', 'DESC')
                ->orderBy('records.punchTime', 'DESC')
                ->get()
                ;
              //  ->paginate(15);
            //    ->toArray();

            //dd($history);

            if ($getSearchPeriod === null) {
                $getSearchPeriod = 'today';
            }

          //  dd($history->get())


            return view('history.historyMain')
                ->with(compact(
                        'history'
                        , 'currentUrl'
                        , 'getSearchPeriod'
                        , 'getMemberName'
                        , 'punchType'
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
