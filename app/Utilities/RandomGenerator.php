<?php

namespace App\Utilities;

use App\Staff;
use Illuminate\Database\Eloquent\Model;

class RandomGenerator
{
    /**
     * @param $length
     * @return string
     */
    public static function generate_random_string($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet);

        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[random_int(0, $max-1)];
        }

        return $token;
    }

    /**
     * @param int $length
     * @return string
     */
    public static function generate_random_numbers($length)
    {
        $salt = "0123456789";
        $len = strlen($salt);
        $makepass = '';

        mt_srand(10000000*(double)microtime());

        for ($i = 0; $i < $length; $i++) {
            $makepass .= $salt[mt_rand(0,$len - 1)];
        }

        return $makepass;
    }

    /**
     * @return string
     */
    public static function generate_unique_uuid()
    {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x',
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
            mt_rand( 0, 0xffff ),
            mt_rand( 0, 0x0C2f ) | 0x4000,
            mt_rand( 0, 0x3fff ) | 0x8000,
            mt_rand( 0, 0x2Aff ), mt_rand( 0, 0xffD3 ), mt_rand( 0, 0xff4B )
        );
    }

    /**
     * @param Model $model
     * @return string
     */
    public static function generate_personal_code(Model $model)
    {
        $model = new $model;

        $code = self::generate_unique_uuid();

        $exists = $model->withTrashed()->whereCode($code)->first();
        if($exists){
            self::generate_staff_code();
        }

        return $code;
    }
}
