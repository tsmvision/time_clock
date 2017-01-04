<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\RegistersUsers;
//use App\GeneralPurpose\GeneralPurpose;

class RegisterController extends Controller
{
  //  use GeneralPurpose;
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/clock';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'jjanID' => 'required|max:255|unique:users',
            'firstNm' => 'required|max:255',
            'lastNm' => 'required|max:255',
            'password' => 'required|min:6|confirmed',
            'userType' => 'required|max:255',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'jjanID' => $data['jjanID'],
            'firstNm' => $data['firstNm'],
            'lastNm' => $data['lastNm'],
            'password' => bcrypt($data['password']),
            'userType' => $data['userType']
        ]);
    }

  //  protected function guard()
  //  {
  //      return Auth::guard('guard-name');
  //  }
}
