<?php

/**
 * This is my custom helper function
 * Laravel keeps resetting it's global helper file
 * So I opened a new file of my own, damn you Laravel!
 */

use App\Utilities\Accent;
use App\Utilities\DateManipulator;
use App\Utilities\RandomGenerator;
use App\Utilities\Shopping;
use App\Utilities\StringModifier;
use App\Utilities\Utility;

if (! function_exists('generate_random_string')) {
    /**
     * @param $length
     * @return string
     */
    function generate_random_string($length)
    {
        return RandomGenerator::generate_random_string($length);
    }
}

if (! function_exists('get_rating')) {
    /**
     * @param $rating
     * @return string
     */
    function get_rating($rating)
    {
        return Utility::get_rating($rating);
    }
}

if (! function_exists('get_average_rating')) {
    /**
     * @param $rating
     * @return float|int
     */
    function get_average_rating($rating)
    {
        return Utility::get_average_rating($rating);
    }
}

if (! function_exists('discount')) {
    /**
     * @param $price
     * @param $discount
     * @return float|int|string
     */
    function discount($price, $discount)
    {
        return Shopping::discount($price, $discount);
    }
}

if (! function_exists('generate_unique_uuid')) {
    /**
     * @return mixed
     */
    function generate_unique_uuid()
    {
        return RandomGenerator::generate_unique_uuid();
    }
}

if (! function_exists('generate_random_numbers')) {
    /**
     * @param $length
     * @return string
     */
    function generate_random_numbers($length)
    {
        return RandomGenerator::generate_random_numbers($length);
    }
}

if (! function_exists('generate_personal_code')) {
    /**
     * @param $model
     * @return string
     */
    function generate_personal_code($model)
    {
        return RandomGenerator::generate_personal_code($model);
    }
}

if (! function_exists('time_ago')) {
    /**
     * @param $date
     * @return string
     */
    function time_ago($date)
    {
        return DateManipulator::time_ago($date);
    }
}

if (! function_exists('date_percent_diff')) {
    /**
     * @param $date1
     * @param $date2
     * @return float|int
     */
    function date_percent_diff($date1, $date2)
    {
        return DateManipulator::date_percent_diff($date1, $date2);
    }
}

if (! function_exists('get_working_days')) {
    /**
     * @param $date1
     * @param $date2
     * @return int
     */
    function get_working_days($date1, $date2)
    {
        return DateManipulator::getWorkingDays($date1, $date2);
    }
}

if (! function_exists('list_working_days')) {
    /**
     * @param $date
     * @return array
     */
    function list_working_days($date)
    {
        return DateManipulator::listWorkingDays($date);
    }
}

if (! function_exists('get_working_days_from_range')) {
    /**
     * @param $start
     * @param $end
     * @return mixed
     */
    function get_working_days_from_range($start, $end)
    {
        return DateManipulator::getWorkingDaysFromRange($start, $end);
    }
}

if (! function_exists('get_date_from_range')) {
    /**
     * @param $date1
     * @param $date2
     * @return array
     */
    function get_date_from_range($date1, $date2)
    {
        return DateManipulator::getDatesFromRange($date1, $date2);
    }
}

if (! function_exists('time_left')) {
    /**
     * @param $date
     * @return mixed
     */
    function time_left($date)
    {
        return DateManipulator::time_remaining($date);
    }
}

if (! function_exists('age')) {
    /**
     * @param $date
     * @return int
     */
    function age($date)
    {
        return DateManipulator::age($date);
    }
}

if (! function_exists('get_age')) {
    /**
     * @param $date
     * @return false|int|string
     */
    function get_age($date)
    {
        return DateManipulator::get_age($date);
    }
}

if (! function_exists('remove_accent')) {
    /**
     * @param $string
     * @return mixed
     */
    function remove_accent($string)
    {
        return Accent::remove_accent($string);
    }
}

if (! function_exists('ellipsis')) {
    /**
     * @param $string
     * @param $length
     * @return string
     */
    function ellipsis($string, $length)
    {
        return StringModifier::ellipsis($string, $length);
    }
}

if (! function_exists('abbreviate')) {
    /**
     * @param $string
     * @return string
     */
    function abbreviate($string)
    {
        return StringModifier::abbreviate($string);
    }
}

if (! function_exists('get_guard')) {
    /**
     * @return mixed
     */
    function get_guard()
    {
        $guard = auth()->guard();

        $sessionName = $guard->getName();

        $parts = explode('_', $sessionName);

        unset($parts[count($parts) -1]);

        unset($parts[0]);

        $guardName = implode('_', $parts);

        return $guardName;
    }
}

if (! function_exists('get_gender')) {
    /**
     * @param $string
     * @return string
     */
    function get_gender($string)
    {
        return $string == 'Male' ? 'his' : 'her';
    }
}

if (! function_exists('slug_reverse')) {
    /**
     * @param $string
     * @return string
     */
    function slug_reverse($string)
    {
        return title_case(str_replace('-', ' ', $string));
    }
}

if (! function_exists('me')) {
    /**
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    function me()
    {
        return auth()->user();
    }
}

if(! function_exists('notify')) {
    /**
     * @param $title
     * @param $state('primary', 'success', 'info', 'danger', 'warning', 'danger', 'secondary', 'default')
     * @param $content
     * @param $from $args['top', 'bottom']
     * @param $align $args['left', 'right', 'center'
     * @param $icon
     * @param $delay
     */
    function notify($title, $state, $content, $from, $align, $icon, $delay = null)
    {
        session()->flash('message', [
            'title' => $title,
            'content' => $content,
            'state' => $state,
            'from' => $from,
            'align' => $align,
            'icon' => $icon,
            'delay' => $delay
        ]);
    }
}

if(! function_exists('get_full_name')) {
    /**
     * @param $person
     * @return string
     */
    function get_full_name($person)
    {
        return $person->first_name .' '. $person->last_name;
    }
}

// API
if(! function_exists('prepare_json')) {
    /**
     * @param $status
     * @param $data
     * @param $msg
     * @return array
     */
    function prepare_json($status, $data, $msg='', $status_code = Symfony\Component\HttpFoundation\Response::HTTP_OK) {
        // $response = new Symfony\Component\HttpFoundation\Response;
        return response()->json(['status' => $status, 'data' => $data, 'message' => $msg], $status_code);
    }
}

if(! function_exists('validator_result')) {
    /**
     * @param $status
     * @param $messages
     * @return array
     */
    function validator_result($status, $messages=[]) {
        // $response = new Symfony\Component\HttpFoundation\Response;
        return ['failed'=>$status, 'messages'=> $messages];
    }
}

if(! function_exists('get_api_string')) {
    /**
     * @param $title
     * @return string
     */
    function get_api_string($title='', $plug_in_var='') {
        // $response = new Symfony\Component\HttpFoundation\Response;
        $app_strings = [
            'error_occurred' => 'An error Occurred.',
            'user_type_customer' => 'customer',
            'user_type_client' => 'client',
            'user_type_admin' => 'admin',
            'invalid_action' => 'Invalid action attempted'.(($plug_in_var != '') ? ':'.$plug_in_var : $plug_in_var ).'.',
            'wrong_password' => 'Incorrect Password.',
            'login_ok' => 'Login Successful.',
            'signup_error' => 'An error occurred during signup. Please try again later.',
            'signup_ok' => 'Signup successful.',
            'profile_edit_ok' => $plug_in_var."'s profile editted.",
            'profile_edit_not_ok' => $plug_in_var."'s profile failed to edit.",
            'generic_ok' => "Action Successful",
            'invalid_file' => 'Invalid file upload',
            'unauthorized' => 'Unauthorized to perform that action.',
            'no_files' => 'No files found for upload',
            'files_uploaded' => 'Files uploaded',
            'file_not_ploaded' => 'Files not uploaded',
            'no_record' => $plug_in_var.": no record to show.",
            'created' => $plug_in_var . ' saved.',
            'not_created' => $plug_in_var . ' not saved.',
            'record_exist' => $plug_in_var . ' already saved.',
            'item_image_required' => 'Product Image/(s) required for Submission.',
            'account_not_found' => 'Account details incorrect',
            'no_items' => 'No Items to display.',
            'old_password_dont_match' => "Old Password incorrect.",
            'passwords_dont_match' => "Passwords don't match.",
            'password_changed' => "Passwords updated.",
            'enter_reset_code' => 'Please enter code sent to your email.',
            'code_resent' => 'Code resent.',
            'reset_code_valid' => 'Reset Code validated.',
            'reset_code_wrong' => 'Code incorrect.',
            'file' => 'file',
            'folder' => 'folder',

        ];
        return ($title == '') ? $app_strings['generic_ok'] : $app_strings[$title];
    }
}

if(! function_exists('get_user_type')) {
    /**
     * @param mixed $user_type
     * @param string|int $type
     * @return int|mixed|string
     */

    function get_user_type($passed_user_type, $type='string') {
        $user_types = [
            get_api_string('user_type_customer')=>0,
            get_api_string('user_type_client') =>1,
            get_api_string('user_type_admin')=>2
        ];
        foreach ($user_types as $user_type => $value) {
            if ($user_type === $passed_user_type) {
                if ($type == 'string') return $user_type;
                if ($type == 'int') return $value;
            }
        }
    }
}