<?php

namespace App\Helpers;


class JalaliDate
{

    // persion time
    public static function mds_date($format, $when = "now", $persianNumber = 0)
    {
        ///chosse your timezone
        $TZhours = 0;
        $TZminute = 0;
        $need = "";
        $result1 = "";
        $result = "";
        if ($when == "now") {
            $year = date("Y");
            $month = date("m");
            $day = date("d");
            list($Dyear, $Dmonth, $Dday) = self::gregorian_to_mds($year, $month, $day);
            $when = mktime(date("H") + $TZhours, date("i") + $TZminute, date("s"), date("m"), date("d"), date("Y"));
        } else {
            $when += $TZhours * 3600 + $TZminute * 60;
            $date = date("Y-m-d", $when);
            list($year, $month, $day) = preg_split('/-/', $date);

            list($Dyear, $Dmonth, $Dday) = self::gregorian_to_mds($year, $month, $day);
        }

        $need = $when;
        $year = date("Y", $need);
        $month = date("m", $need);
        $day = date("d", $need);
        $i = 0;
        $subtype = "";
        $subtypetemp = "";
        list($Dyear, $Dmonth, $Dday) = self::gregorian_to_mds($year, $month, $day);
        while ($i < strlen($format)) {
            $subtype = substr($format, $i, 1);
            if ($subtypetemp == "\\") {
                $result .= $subtype;
                $i++;
                continue;
            }

            switch ($subtype) {

                case "A":
                    $result1 = date("a", $need);
                    if ($result1 == "pm")
                        $result .= "&#1576;&#1593;&#1583;&#1575;&#1586;&#1592;&#1607;&#1585;";
                    else
                        $result .= "&#1602;&#1576;&#1604;&#8207;&#1575;&#1586;&#1592;&#1607;&#1585;";
                    break;

                case "a":
                    $result1 = date("a", $need);
                    if ($result1 == "pm")
                        $result .= "&#1576;&#46;&#1592;";
                    else
                        $result .= "&#1602;&#46;&#1592;";
                    break;
                case "d":
                    if ($Dday < 10)
                        $result1 = "0" . $Dday;
                    else
                        $result1 = $Dday;
                    if ($persianNumber == 1)
                        $result .= self::Convertnumber2farsi($result1);
                    else
                        $result .= $result1;
                    break;
                case "D":
                    $result1 = date("D", $need);
                    if ($result1 == "Thu")
                        $result1 = "&#1662;";
                    else if ($result1 == "Sat")
                        $result1 = "&#1588;";
                    else if ($result1 == "Sun")
                        $result1 = "&#1609;";
                    else if ($result1 == "Mon")
                        $result1 = "&#1583;";
                    else if ($result1 == "Tue")
                        $result1 = "&#1587;";
                    else if ($result1 == "Wed")
                        $result1 = "&#1670;";
                    else if ($result1 == "Thu")
                        $result1 = "&#1662;";
                    else if ($result1 == "Fri")
                        $result1 = "&#1580;";
                    $result .= $result1;
                    break;
                case "F":
                    $result .= self::monthname($Dmonth);
                    break;
                case "g":
                    $result1 = date("g", $need);
                    if ($persianNumber == 1)
                        $result .= self::Convertnumber2farsi($result1);
                    else
                        $result .= $result1;
                    break;
                case "G":
                    $result1 = date("G", $need);
                    if ($persianNumber == 1)
                        $result .= self::Convertnumber2farsi($result1);
                    else
                        $result .= $result1;
                    break;
                case "h":
                    $result1 = date("h", $need);
                    if ($persianNumber == 1)
                        $result .= self::Convertnumber2farsi($result1);
                    else
                        $result .= $result1;
                    break;
                case "H":
                    $result1 = date("H", $need);
                    if ($persianNumber == 1)
                        $result .= self::Convertnumber2farsi($result1);
                    else
                        $result .= $result1;
                    break;
                case "i":
                    $result1 = date("i", $need);
                    if ($persianNumber == 1)
                        $result .= self::Convertnumber2farsi($result1);
                    else
                        $result .= $result1;
                    break;
                case "j":
                    $result1 = $Dday;
                    if ($persianNumber == 1)
                        $result .= self::Convertnumber2farsi($result1);
                    else
                        $result .= $result1;
                    break;
                case "l":
                    $result1 = date("l", $need);
                    if ($result1 == "Saturday")
                        $result1 = "&#1588;&#1606;&#1576;&#1607;";
                    else if ($result1 == "Sunday")
                        $result1 = "&#1610;&#1603;&#1588;&#1606;&#1576;&#1607;";
                    else if ($result1 == "Monday")
                        $result1 = "&#1583;&#1608;&#1588;&#1606;&#1576;&#1607;";
                    else if ($result1 == "Tuesday")
                        $result1 = "&#1587;&#1607;&#32;&#1588;&#1606;&#1576;&#1607;";
                    else if ($result1 == "Wednesday")
                        $result1 = "&#1670;&#1607;&#1575;&#1585;&#1588;&#1606;&#1576;&#1607;";
                    else if ($result1 == "Thursday")
                        $result1 = "&#1662;&#1606;&#1580;&#1588;&#1606;&#1576;&#1607;";
                    else if ($result1 == "Friday")
                        $result1 = "&#1580;&#1605;&#1593;&#1607;";
                    $result .= $result1;
                    break;
                case "m":
                    if ($Dmonth < 10)
                        $result1 = "0" . $Dmonth;
                    else
                        $result1 = $Dmonth;
                    if ($persianNumber == 1)
                        $result .= self::Convertnumber2farsi($result1);
                    else
                        $result .= $result1;
                    break;
                case "n":
                    $result1 = $Dmonth;
                    if ($persianNumber == 1)
                        $result .= self::Convertnumber2farsi($result1);
                    else
                        $result .= $result1;
                    break;
                case "s":
                    $result1 = date("s", $need);
                    if ($persianNumber == 1)
                        $result .= self::Convertnumber2farsi($result1);
                    else
                        $result .= $result1;
                    break;
                case "S":
                    $result .= "&#1575;&#1605;";
                    break;
                case "t":
                    $result .= self::lastday($month, $day, $year);
                    break;
                case "w":
                    $result1 = date("w", $need);
                    if ($persianNumber == 1)
                        $result .= self::Convertnumber2farsi($result1);
                    else
                        $result .= $result1;
                    break;
                case "y":
                    $result1 = substr($Dyear, 2, 4);
                    if ($persianNumber == 1)
                        $result .= self::Convertnumber2farsi($result1);
                    else
                        $result .= $result1;
                    break;
                case "Y":
                    $result1 = $Dyear;
                    if ($persianNumber == 1)
                        $result .= self::Convertnumber2farsi($result1);
                    else
                        $result .= $result1;
                    break;
                case "U":
                    $result .= time();
                    break;
                case "Z":
                    $result .= self::days_of_year($Dmonth, $Dday, $Dyear);
                    break;
                case "L":
                    list($tyear, $tmonth, $tday) = self::mds_to_gregorian(1384, 12, 1);
                    echo $tday;
                    /*if(lastday($tmonth,$tday,$tyear)=="31")*/
                    break;
                default:
                    $result .= $subtype;
            }
            $subtypetemp = substr($format, $i, 1);
            $i++;
        }
        return $result;
    }

    // make time with add hour and minute
    public static function make_time($hour = "", $minute = "", $second = "", $Dmonth = "", $Dday = "", $Dyear = "")
    {
        if (!$hour && !$minute && !$second && !$Dmonth && !$Dmonth && !$Dday && !$Dyear)
            return time();
        if ($Dmonth > 11)
            die("Incorrect month number");
        list($year, $month, $day) = self::mds_to_gregorian($Dyear, $Dmonth, $Dday);
        $i = mktime($hour, $minute, $second, $month, $day, $year);
        return $i;
    }

    ///Find num of Day Begining Of Month ( 0 for Sat & 6 for Sun)
    public static function mstart($month, $day, $year)
    {
        list($Dyear, $Dmonth, $Dday) = self::gregorian_to_mds($year, $month, $day);
        list($year, $month, $day) = self::mds_to_gregorian($Dyear, $Dmonth, "1");
        $timestamp = mktime(0, 0, 0, $month, $day, $year);
        return date("w", $timestamp);
    }

    //Find Number Of Days In This Month
    public static function lastday($month, $day, $year)
    {
        $Dday2 = "";
        $jdate2 = "";
        $lastdayen = date("d", mktime(0, 0, 0, $month + 1, 0, $year));
        list($Dyear, $Dmonth, $Dday) = self::gregorian_to_mds($year, $month, $day);
        $lastdatep = $Dday;
        $Dday = $Dday2;
        while ($Dday2 != "1") {
            if ($day < $lastdayen) {
                $day++;
                list($Dyear, $Dmonth, $Dday2) = self::gregorian_to_mds($year, $month, $day);
                if ($jdate2 == "1")
                    break;
                if ($jdate2 != "1")
                    $lastdatep++;
            } else {
                $day = 0;
                $month++;
                if ($month == 13) {
                    $month = "1";
                    $year++;
                }
            }
        }
        return $lastdatep - 1;
    }

    //Find days in this year untile now
    public static function days_of_year($Dmonth, $Dday, $Dyear)
    {
        $year = "";
        $month = "";
        $year = "";
        $result = "";
        if ($Dmonth == "01")
            return $Dday;
        for ($i = 1; $i < $Dmonth || $i == 12; $i++) {
            list($year, $month, $day) = self::mds_to_gregorian($Dyear, $i, "1");
            @$result += self::lastday($month, $day, $year);
        }
        return $result + $Dday;
    }

    //translate number of month to name of month
    public static function monthname($month)
    {

        if ($month == "01")
            return "فروردين";

        if ($month == "02")
            return "اردیبهشت";

        if ($month == "03")
            return "خرداد";

        if ($month == "04")
            return "تیر";

        if ($month == "05")
            return "مرداد";

        if ($month == "06")
            return "شهریور";

        if ($month == "07")
            return "مهر";

        if ($month == "08")
            return "آبان";

        if ($month == "09")
            return "آذر";

        if ($month == "10")
            return "دی";

        if ($month == "11")
            return "بهمن";

        if ($month == "12")
            return "اسفند";
    }

    //converts the numbers into the persian's number
    public static function Convertnumber2farsi($srting)
    {
        $stringtemp = "";
        $len = strlen($srting);
        for ($sub = 0; $sub < $len; $sub++) {
            if (substr($srting, $sub, 1) == "0")
                $stringtemp .= "۰";
            elseif (substr($srting, $sub, 1) == "1")
                $stringtemp .= "۱";
            elseif (substr($srting, $sub, 1) == "2")
                $stringtemp .= "۲";
            elseif (substr($srting, $sub, 1) == "3")
                $stringtemp .= "۳";
            elseif (substr($srting, $sub, 1) == "4")
                $stringtemp .= "۴";
            elseif (substr($srting, $sub, 1) == "5")
                $stringtemp .= "۵";
            elseif (substr($srting, $sub, 1) == "6")
                $stringtemp .= "۶";
            elseif (substr($srting, $sub, 1) == "7")
                $stringtemp .= "۷";
            elseif (substr($srting, $sub, 1) == "8")
                $stringtemp .= "۸";
            elseif (substr($srting, $sub, 1) == "9")
                $stringtemp .= "۹";
            else
                $stringtemp .= substr($srting, $sub, 1);
        }
        return $stringtemp;
    } ///end conver to number in persian
    public static function convert2english($string)
    {
        $newNumbers = range(0, 9);
        // 1. Persian HTML decimal
        $persianDecimal = array('&#1776;', '&#1777;', '&#1778;', '&#1779;', '&#1780;', '&#1781;', '&#1782;', '&#1783;', '&#1784;', '&#1785;');
        // 2. Arabic HTML decimal
        $arabicDecimal = array('&#1632;', '&#1633;', '&#1634;', '&#1635;', '&#1636;', '&#1637;', '&#1638;', '&#1639;', '&#1640;', '&#1641;');
        // 3. Arabic Numeric
        $arabic = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
        // 4. Persian Numeric
        $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');

        $string = str_replace($persianDecimal, $newNumbers, $string);
        $string = str_replace($arabicDecimal, $newNumbers, $string);
        $string = str_replace($arabic, $newNumbers, $string);
        return str_replace($persian, $newNumbers, $string);
    }
    public static function is_kabise($year)
    {
        if ($year % 4 == 0 && $year % 100 != 0)
            return true;
        return false;
    }

    public static function div($a, $b)
    {
        return (int) ($a / $b);
    }

    public static function gregorian_to_mds($g_y, $g_m, $g_d)
    {
        $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        $m_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);

        $gy = $g_y - 1600;
        $gm = $g_m - 1;
        $gd = $g_d - 1;

        $g_day_no = 365 * $gy + self::div($gy + 3, 4) - self::div($gy + 99, 100) + self::div($gy + 399, 400);

        for ($i = 0; $i < $gm; ++$i)
            $g_day_no += $g_days_in_month[$i];
        if ($gm > 1 && (($gy % 4 == 0 && $gy % 100 != 0) || ($gy % 400 == 0)))
            /* leap and after Feb */
            $g_day_no++;
        $g_day_no += $gd;

        $m_day_no = $g_day_no - 79;

        $j_np = self::div($m_day_no, 12053); /* 12053 = 365*33 + 32/4 */
        $m_day_no = $m_day_no % 12053;

        $jy = 979 + 33 * $j_np + 4 * self::div($m_day_no, 1461); /* 1461 = 365*4 + 4/4 */

        $m_day_no %= 1461;

        if ($m_day_no >= 366) {
            $jy += self::div($m_day_no - 1, 365);
            $m_day_no = ($m_day_no - 1) % 365;
        }

        for ($i = 0; $i < 11 && $m_day_no >= $m_days_in_month[$i]; ++$i)
            $m_day_no -= $m_days_in_month[$i];
        $jm = $i + 1;
        $jd = $m_day_no + 1;

        return array($jy, $jm, $jd);
    }

    public static function mds_to_gregorian($m_y, $j_m, $m_d)
    {
        $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
        $m_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);



        $jy = $m_y - 979;
        $jm = $j_m - 1;
        $jd = $m_d - 1;

        $m_day_no = 365 * $jy + self::div($jy, 33) * 8 + self::div($jy % 33 + 3, 4);
        for ($i = 0; $i < $jm; ++$i)
            $m_day_no += $m_days_in_month[$i];

        $m_day_no += $jd;

        $g_day_no = $m_day_no + 79;

        $gy = 1600 + 400 * self::div($g_day_no, 146097); /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */
        $g_day_no = $g_day_no % 146097;

        $leap = true;
        if ($g_day_no >= 36525) /* 36525 = 365*100 + 100/4 */ {
            $g_day_no--;
            $gy += 100 * self::div($g_day_no, 36524); /* 36524 = 365*100 + 100/4 - 100/100 */
            $g_day_no = $g_day_no % 36524;

            if ($g_day_no >= 365)
                $g_day_no++;
            else
                $leap = false;
        }

        $gy += 4 * self::div($g_day_no, 1461); /* 1461 = 365*4 + 4/4 */
        $g_day_no %= 1461;

        if ($g_day_no >= 366) {
            $leap = false;

            $g_day_no--;
            $gy += self::div($g_day_no, 365);
            $g_day_no = $g_day_no % 365;
        }

        for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++)
            $g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);
        $gm = $i + 1;
        $gd = $g_day_no + 1;

        return array($gy, $gm, $gd);
    }

    public static function getBetweenDates($startDate, $endDate)
    {
        $rangArray = [];

        $startDate = strtotime($startDate);
        $endDate = strtotime($endDate);

        for (
            $currentDate = $startDate;
            $currentDate < $endDate;
            $currentDate += (86400)
        ) {
            $rangArray[] = self::mds_date('Y/m/d', $currentDate);
        }

        return $rangArray;
    }
    public static function  number_switch($variable)
    {
        $persian_numbers = [
            '1' => "یک",
            '2' => "دو",
            '3' => "سه",
            '4' => "چهار",
            '5' => "پنج",
            '6' => "شش",
            '7' => "هفت",
            '8' => "هشت",
            '9' => "نه",
            '10' => "ده",
            '11' => "یازده",
            '12' => "دوازده",
            '13' => "سیزده",
            '14' => "چهارده",
            '15' => "پانزده",
            '16' => "شانزده",
            '17' => "هفده",
            '18' => "هجده",
            '19' => "نوزده",
            '20' => "بیست",
            '30' => "سی",
            '40' => "چهل",
            '50' => "پنجاه",
            '60' => "شصت",
            '70' => "هفتاد",
            '80' => "هشتاد",
            '90' => "نود",
            '100' => "صد"
        ];
        if (array_key_exists($variable, $persian_numbers)) {
            return $persian_numbers[$variable];
        }
        return $variable;
    }
}
