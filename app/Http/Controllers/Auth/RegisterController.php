<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
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
     * Where to redirect users after registration.
     *
     * @var string
     */
    // protected $redirectTo = '/home';
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
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
            'reg_name'     => 'required|string|max:255',
            'reg_email'    => 'required|string|email|max:255|unique:' . (new User)->getTable() . ',email',
            'reg_password' => 'required|string|min:6|confirmed',
            'reg_gender'   => 'string|max:20',
            'reg_address1' => 'required|string|max:255',
        );
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {

        $user = User::create([
            'name'     => $data['reg_name'],
            'email'    => $data['reg_email'],
            'username' => $data['reg_email'],
            'password' => bcrypt($data['reg_password']),
            'gender'    => $data['reg_gender'],
            //'address1' => $data['reg_address1'],
            //'address2' => $data['reg_address2'],
        ]);
        // if ($user) {
        //     if (\Helper::configs()['welcome_customer']) {

        //         $checkContent = (new EmailTemplate)->where('group', 'welcome_customer')->where('status', 1)->first();
        //         if ($checkContent) {
        //             $content  = $checkContent->text;
        //             $dataFind = [
        //                 '/\{\{\$title\}\}/',
        //             ];
        //             $dataReplace = [
        //                 trans('email.welcome_customer.title'),
        //             ];
        //             $content   = preg_replace($dataFind, $dataReplace, $content);
        //             $data_mail = [
        //                 'content' => $content,
        //             ];

        //             $config = [
        //                 'to'      => $data['reg_email'],
        //                 'subject' => trans('email.welcome_customer.title'),
        //             ];

        //             \Helper::sendMail('mail.welcome_customer', $data_mail, $config, []);
        //         }

        //     }
        // } else {

        // }
        return $user;
    }
    public function showRegistrationForm()
    {
        return redirect()->route('register');
        // return view('auth.register');
    }

    protected function registered(Request $request, $user)
    {
        redirect()->route('home')->with(['message' => trans('account.register_success')]);
    }
}
