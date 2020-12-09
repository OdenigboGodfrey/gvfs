<?php

namespace App\Utilities;
use Illuminate\Support\Facades\Validator;

class Utility
{
    /** display positive message to user compulsory, higher power to neutral **/
    public static $positive = 1;
    /** display message to user not compulsory, equivalent to bool true **/
    public static $neutral = 0;
    /** display negative message to user compulsory, equivalent to bool false **/
    public static $negative = -1;
    /** display error message to user compulsory **/
    public static $error = -2;

    public static $_500 = 500;
    public static $_422 = 422;
    public static $_201 = 201;
    public static $_400 = 400;

    public static function validator(array $data, $fields)
    {
        $validator =  Validator::make($data, $fields);
        if ($validator->fails()) {
            return \validator_result(true, $validator->errors()->all());
        }

        return \validator_result(false);
    }

    /**
     * @param $rating
     * @return string
     */
    public static function get_rating($rating)
    {
        $stars = "";

        if($rating == 0){
            for($s = 1; $s <= 5; $s++){
                $stars .= '<span class="fa fa-star col-gray">&nbsp;</span>';
            }
        }

        $whole = floor($rating);
        $fraction = $rating - $whole;

        if($fraction < .25){
            $dec=0;
        }elseif($fraction >= .25 && $fraction < .75){
            $dec=.50;
        }elseif($fraction >= .75){
            $dec=1;
        }
        $r = $whole + $dec;

        //As we sometimes round up, we split again
        $newwhole = floor($r);
        $fraction = $r - $newwhole;

        for($s=1;$s<=$newwhole;$s++){
            $stars .= '<span class="fa fa-star col-orange">&nbsp;</span>';
        }
        if($fraction==.5){
            $stars .= '<span class="fa fa-star-half col-orange">&nbsp;</span>';
        }

        return $stars;
    }

    /**
     * @param $rating
     * @return float|int
     */
    public static function get_average_rating($rating)
    {
        if($rating->count() == 0){
            return 0;
        }

        $max = 0;

        foreach($rating as $rate => $count) { // iterate through array
            $max = $max + $count->rating;
        }

        return $max / $rating->count();
    }


}
