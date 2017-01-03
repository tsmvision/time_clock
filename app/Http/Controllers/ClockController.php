<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use App\PunchRecord;
use Illuminate\Support\Facades\Auth;
use App\GeneralPurpose\GeneralPurpose;

class clockController extends Controller
{
    use GeneralPurpose;

    public $dateTime;
    public $currentTime;


    public function __construct()
    {
        $this->dateTime = Carbon::now()->format('Y-m-d H:i:s');
        $this->currentTime = Carbon::now()->format('H:m:s');

    }

    public function clock(Request $request)
    {


        $currentUrl = $request->path();
        $currentUser = AUTH::user()->jjanID;

        $currentUserName = $this->currentUserName($currentUser);

        return view('clock.clockMain')
            ->with(
                compact('currentUrl'
                        ,'currentUser'
                        ,'currentUserName'
                        )
            );
    }

}
