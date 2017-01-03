<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\PunchRecord;
use App\GeneralPurpose\GeneralPurpose;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use GeneralPurpose;

    public $today;
    public $dateTime;
    public $currentTime;
    public $punchType;

    public function showList(Request $request)
    {
        $request->flash();
        $currentUrl = $request->path();
        // $getSearchPeriod = $request->input('getSearchPeriod');
        $getJJANID = $request->input('getJJANID');
        $getMemberName = $request->input('getMemberName');

        $currentUser = Auth::user()->jjanID;
        $currentUserName = $this->currentUserName($currentUser);

        $users = DB::table('users')
            ->select(
                'users.jjanID'
                , 'users.firstNm'
                , 'users.firstNm'
                , 'users.lastNm'
            )
            ->get();

        // for dropdown menu
        $users2 = DB::table('users')
            ->select(
                'users.jjanID'
                , 'users.firstNm'
                , 'users.firstNm'
                , 'users.lastNm'
            )
            ->get();

        return view('admin.users.userMain')
            ->with(compact(
                    'users'
                    , 'users2'
                    , 'currentUser'
                    , 'currentUserName'
                    , 'currentUrl'
                    , 'getJJANID'
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


        return redirect('/history')->with('message', 'deleted!');
    }

}
