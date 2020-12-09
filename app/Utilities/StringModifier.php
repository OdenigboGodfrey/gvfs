<?php

namespace App\Utilities;

class StringModifier
{
    /**
     * @param $text
     * @param int $max
     * @param string $append
     * @return string
     */
    public static function ellipsis($text, $max = 100, $append = '&hellip;')
    {
        if(strlen($text) <= $max){
            return $text;
        }

        $out = substr($text, 0, $max);

        if(strpos($text,' ') === FALSE){
            return $out.$append;
        }

        return preg_replace('/\w+$/','',$out).$append;
    }

    /**
     * @param $string
     * @return string
     */
    public static function abbreviate($string)
    {
        $abbreviation = "";
        $string = ucwords($string);
        $words = explode(" ", "$string");

        foreach($words as $word){
            if($word){
                $abbreviation .= $word[0];
            }
        }

        return $abbreviation;
    }
}
