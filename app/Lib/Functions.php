<?php

namespace App\Lib;

use Exception;

class Functions
{
    /**
     * 時間文字列を分に変換します。
     *
     * @param string $time '1 day(s) 00:00:00'のような時間文字列
     * @return int 分
     */
    public static function TimeStringToMinutes($time)
    {
        $minute = 0;
        if (empty($time)) {
            return $minute;
        }

        try {

            if (strpos($time, ' days ')) {
                $dayTimes = explode(' days ', $time);
            }
            else {
                $dayTimes = explode(' day ', $time);
            }
            if (count($dayTimes) == 2) {
                // 1 days 00:30:00
                $minute += (intval(array_shift($dayTimes)) * 24 * 60);
            }

            $times = explode(':', $dayTimes[0]);
            $minute += (intval(array_shift($times)) * 60);
            $minute += (intval(array_shift($times)));
        }
        catch(Exception $e) {
        }
        return $minute;
    }

    /**
     * 時間文字列の時間フォーマットを返却します。
     *
     * @param string $time '1 day(s) 00:00:00'のような時間文字列
     * @return string xxx分 (xxx時間xx分)
     */
    public static function FormatTime($time)
    {
        $minutes = Functions::TimeStringToMinutes($time);
        $hours = floor($minutes / 60);
        $minute = floor($minutes % 60);

        $f_minutes = number_format($minutes);
        $f_hours = number_format($hours);

        return "{$f_minutes}分 ({$f_hours}時間{$minute}分)";
    }
}