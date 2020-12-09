<?php

namespace App\Utilities;

use Carbon\Carbon;
use DatePeriod;
use DateTime;
use DateInterval;

class DateManipulator
{
    /**
     * @param $months
     * @param DateTime $dateObject
     * @return bool|DateInterval
     * @throws \Exception
     */
    private static function add_months($months, DateTime $dateObject)
    {
        $next = new DateTime($dateObject->format('Y-m-d'));
        $next->modify('last day of +'.$months.' month');

        return $dateObject->format('d') > $next->format('d') ? $dateObject->diff($next) : new DateInterval('P'.$months.'M');
    }

    /**
     * @param $d1
     * @param $months
     * @return string
     * @throws \Exception
     */
    public static function endCycle($d1, $months)
    {
        $date = new DateTime($d1);

        // call second function to add the months
        $newDate = $date->add(self::add_months($months, $date));

        //formats final date to Y-m-d form
        $dateReturned = $newDate->format('Y-m-d H:i:s');

        return $dateReturned;
    }

    /**
     * @param $date
     * @return mixed
     */
    public static function time_remaining($date)
    {
        $now = new DateTime();

        $future_date = new DateTime($date);

        $interval = $future_date->diff($now);

        return $interval->format("%a days, %h hours, %i minutes, %s seconds");
    }

    /**
     * @param $datetime
     * @param bool $full
     * @return string
     */
    public static function time_ago($datetime, $full = false)
    {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if(!$full){
            $string = array_slice($string, 0, 1);
        }

        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }

    /**
     * @param $date
     * @return string
     */
    public static function age($date)
    {
        if($date == null){
            return 'Date of birth not provided';
        }

        $birth_date = date('Y-m-d', strtotime($date));

        $year = date("Y") - date("Y", strtotime($birth_date));

        if($year > 0){
            $suffix = $year == 1 ? ' old' : 's old';
            return $year . ' year'.$suffix;
        }
        else{
            $month = date("m") - date("m", strtotime($birth_date));

            if($month > 0){
                $suffix = $month == 1 ? ' old' : 's old';
                return $month . ' month'.$suffix;
            }
            else{
                $day = date("d") - date("d", strtotime($birth_date));

                if($day > 0){
                    $suffix = $day == 1 ? ' old' : 's old';
                    return $day . ' day'.$suffix;
                }
                else{
                    return "Few hours old";
                }
            }
        }
    }

    /**
     * @param $datetime1
     * @param $datetime2
     * @return float
     */
    public static function date_percent_diff($datetime1, $datetime2)
    {
        $start = Carbon::parse($datetime1)->timestamp;

        $end = Carbon::parse($datetime2)->timestamp;

        $timespan = $end - $start;

        $current = Carbon::now()->timestamp - $start;

        $progress = $current / $timespan;

        $remaining = (1 - $progress) * 100;

        return round($remaining, 2);
    }

    /**
     * @param $date
     * @return false|int|string
     *
     */
    public static function get_age($date)
    {
        $birthDate = date('m/d/Y', strtotime($date));
        $birthDate = explode("/", $birthDate);

        $age = (date("md", date("U", mktime(0, 0, 0, $birthDate[0], $birthDate[1], $birthDate[2]))) > date("md")
            ? ((date("Y") - $birthDate[2]) - 1)
            : (date("Y") - $birthDate[2]));

        return $age;
    }

    /**
     * @param $start
     * @param $end
     * @param string $format
     * @return array
     */
    public static function getDatesFromRange($start, $end, $format = 'Y-m-d')
    {
        // Declare an empty array
        $array = array();

        // Variable that store the date interval
        // of period 1 day
        try {
            $interval = new DateInterval('P1D');
        }
        catch (\Exception $e) {
        }

        $realEnd = new DateTime($end);
        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

        // Use loop to store date into array
        foreach ($period as $date)
        {
            $array[] = $date->format($format);
        }

        // Return the array elements
        return $array;
    }

    /**
     * @param $startDate
     * @param $endDate
     * @return int
     */
    public static function getWorkingDays($startDate, $endDate)
    {
        $begin = strtotime($startDate);
        $end   = strtotime($endDate);
        if ($begin > $end) {
            return 0;
        }
        else {
            $no_days = 0;
            while ($begin <= $end) {
                $what_day = date("N", $begin);
                if(!in_array($what_day, [6,7])) // 6 and 7 are weekend
                    $no_days++;
                $begin += 86400; // +1 day
            };

            return $no_days;
        }
    }

    /**
     * @param $date
     * @return array
     */
    public static function listWorkingDays($date)
    {
        $workdays = array();
        $type = CAL_GREGORIAN;
        $month = date('n', strtotime($date)); // Month ID, 1 through to 12.
        $year = date('Y', strtotime($date)); // Year in 4 digit 2009 format.
        $day_count = cal_days_in_month($type, $month, $year); // Get the amount of days

        //loop through all days
        for ($i = 1; $i <= $day_count; $i++) {

            $date = $year.'/'.$month.'/'.$i; //format date
            $get_name = date('l', strtotime($date)); //get week day
            $day_name = substr($get_name, 0, 3); // Trim day name to 3 chars

            //if not a weekend add day to array
            if($day_name != 'Sun' && $day_name != 'Sat'){
                $workdays[] = date('Y-m-d', strtotime($year.'-'.$month.'-'.$i));
            }

        }

        return $workdays;
    }

    /**
     * @param $start
     * @param $end
     * @param string $format
     * @return array
     */
    public static function getWorkingDaysFromRange($start, $end, $format = 'Y-m-d')
    {
        // Declare an empty array
        $array = array();

        // Variable that store the date interval
        // of period 1 day
        try {
            $interval = new DateInterval('P1D');
        }
        catch (\Exception $e) {
        }

        $realEnd = new DateTime($end);
        $realEnd->add($interval);

        $period = new DatePeriod(new DateTime($start), $interval, $realEnd);

        // Use loop to store date into array
        foreach ($period as $date)
        {
            $day_name = Carbon::parse($date)->getTranslatedDayName();
            if($day_name != 'Sunday' && $day_name != 'Saturday') {
                $array[] = $date->format($format);
            }
        }

        // Return the array elements
        return $array;
    }
}
