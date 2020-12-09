<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Validator;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $_500 = 500;
    private $_422 = 422;
    private $date = '';
    /** display positive message to user compulsory **/
    private $positive = 1;
    /** display message to user not compulsory **/
    private $neutral = 0;
    /** display negative message to user compulsory **/
    private $negative = -1;
    /** display error message to user compulsory **/
    private $error = -2;


    public function __construct()
    {
        $this->out = new \Symfony\Component\Console\Output\ConsoleOutput();

        $this->date = Carbon::now();
    }

    protected function validator(array $data, $fields)
    {
        $validator =  Validator::make($data, $fields);
        if ($validator->fails()) {
            return \validator_result(true, $validator->errors()->all());
        }
        return \validator_result(false);

    }

    public function init_registration(Request $request) {
        /**
            user_types: 0=>customer,1=>client
         **/
        $validator = $this->validator($request->all(),[
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:3',
            'user_type' => 'required|numeric',
        ]);

        if ($validator['failed']) {
            return \prepare_json($this->negative, ['messages' => $validator['messages']],'',$status_code=$this->_422);
        }

        try {
            $data = $request->all();


            $user = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'user_type' => $data['user_type'],
                'account_type' => $data['account_type'],
            ]);



            if ($user) {
                //create access token
                $user['token'] = $user->createToken('new_user_'.$data['user_type'])->accessToken;
                if ($user->user_type == get_api_string('user_type_client')) {
                    $client = Client::create([
                       'user_id' => $user->id,
                       'business_count' => 0,
                    ]);

                    if(!isset($client)) {
                        return prepare_json($this->negative, [], \get_api_string('client_failed_signup_ok'));
                    }
                    /** get client info from client table */
                    $user['client'] = $client;
                }
                else if ($user->user_type == get_api_string('user_type_customer')) {
                    $customer = Customer::create([
                        'user_id' => $user->id,
                    ]);

                    if(!isset($customer)) {
                        return prepare_json($this->negative, [], \get_api_string('customer_failed_signup_ok'));
                    }
                    /** get customer info from customer table */
                    $user['customer'] = $customer;
                }
                else if ($user->user_type == get_api_string('user_type_admin')) {
                    $admin = Admin::create([
                        'user_id' => $user->id,
                    ]);

                    if(!isset($admin)) {
                        return prepare_json($this->negative, [], \get_api_string('customer_failed_signup_ok'));
                    }
                    /** get admin info from admin table */
                    $user['admin'] = $admin;
                }
                return prepare_json($this->positive, $user, \get_api_string('signup_ok'));
            }
            else {
                return prepare_json($this->negative, [], \get_api_string('signup_error'));
            }
        }
        catch (\Exception $ex) {
            return \prepare_json($this->error, [],\get_api_string('error_occurred').$ex->getMessage(), $this->_500);
        }
    }

    public function login(Request $request)
    {
        /**
         * user_types: 0=>customer,1=>client
         **/
        $validator = $this->validator($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:3',
            'user_type' => 'required|numeric',
        ]);

        if ($validator['failed']) {
            return \prepare_json($this->negative, ['messages' => $validator['messages']],'',$status_code=$this->_422);
        }

        try {
            $data = $request->all();

            $user = User::where("email", $request->email)->first();
            if($user) {
                if (get_user_type($user->user_type, 'int') != $data['user_type']) {
                    return \prepare_json($this->negative, ['email'=> $user->email]
                        , \get_api_string('wrong_user_type'));
                }
                if (Hash::check($data['password'], $user->password)) {
                    $user['token'] =  $user->createToken('new_login_'.$data['user_type'])->accessToken;
                    if ($user->user_type == get_api_string('user_type_client')) {
                        /** get client info from client table */
                        $user['client'] = $user->client();
                    }
                    else if ($user->user_type == get_api_string('user_type_customer')) {
                        /** get customer info from client table */
                        $user['customer'] = $user->customer();
                    }
                    else if ($user->user_type == get_api_string('user_type_admin')) {
                        /** get admin info from admin table */
                        $user['admin'] = $user->admin();
                    }
                    return \prepare_json($this->neutral, $user, 'Login Successful');
                }
                else {
                    return \prepare_json($this->negative, ['email'=> $user->email], \get_api_string('account_not_found'));
                }
            }
            else {
                return \prepare_json($this->negative, ['email'=> $data['email']], \get_api_string('account_not_found'));
            }
        }
        catch (\Exception $ex) {
            return \prepare_json($this->error, [],\get_api_string('error_occurred').$ex->getMessage(), $this->_500);
        }
    }
}
