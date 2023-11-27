<?php


if (!defined('ABSPATH')) {
    header('location: /', true, 302);
}

use App\Database\PDODatabaseConnection;
use App\Database\PDOQueryBuilder;

function DatabaseQuery()
{
    $pdoConnection = new PDODatabaseConnection(DbConfig);
    $queryBuilder = new PDOQueryBuilder($pdoConnection->connect());
    return ["connection" => $pdoConnection, "query" => $queryBuilder];
}

/**
 * 
 * get_user_meta 
 *      @param string $user_id optional
 *      @param string $metaKey optional
 *      @param string $metaValue optional
 *      @param string $id optional
 *      when just send user_id return array of id , meta_key and meta_value
 *      when send user_id , metaKey return A id , meta_value in array
 *      when send metaKey return id , user_id and meta_value in array
 *      when send nothing return id , user_id , meta_key and meta_value in array
 *      when send meta_key , meta_value return id and user_id in array
 *      when send value id return user_id , meta_key and meta in A array
 * 
 */
function get_user_meta($user_id = 0, $metaKey = "", $metaValue = "", $id = 0)
{
    $database = DatabaseQuery();
    $data = ["meta_value" => NULL];
    if ($id == 0) {
        if ($metaValue == "") {
            if ($user_id != 0) {
                if ($metaKey == "") {
                    $usermeta = $database['query']->table('ps_usermeta')->where("user_id", $user_id)->get();
                    if ($usermeta) {
                        foreach ($usermeta as $row) {
                            $data[] = ["id" => $row['id'], "meta_key" => $row['meta_key'], "meta_value" => $row['meta_value']];
                        }
                        unset($data['meta_value']);
                    }
                } else {
                    $usermeta = $database['query']->table('ps_usermeta')->where("user_id", $user_id)->where("meta_key", $metaKey)->get();
                    if ($usermeta) {
                        foreach ($usermeta as $row) {
                            $data = ["id" => $row['id'], "meta_value" => $row['meta_value']];
                        }
                    }
                }
            } else {
                if ($metaKey != "") {
                    $usermeta = $database['query']->table('ps_usermeta')->where("meta_key", $metaKey)->get();
                    if ($usermeta) {
                        foreach ($usermeta as $row) {
                            $data[] = ["id" => $row['id'], "user_id" => $row['user_id'], "meta_value" => $row['meta_value']];
                        }
                        unset($data['meta_value']);
                    }
                } else {
                    $usermeta = $database['query']->table('ps_usermeta')->get();
                    if ($usermeta) {
                        foreach ($usermeta as $row) {
                            $data[] = ["id" => $row['id'], "user_id" => $row['user_id'], "meta_key" => $row['meta_key'], "meta_value" => $row['meta_value']];
                        }
                        unset($data['meta_value']);
                    }
                }
            }
        } else {
            if ($metaKey != "") {
                $usermeta = $database['query']->table('ps_usermeta')->where("meta_key", $metaKey)->where("meta_value", $metaValue)->get();
                if ($usermeta) {
                    foreach ($usermeta as $row) {
                        $data[] = ["id" => $row['id'], "user_id" => $row['user_id']];
                    }
                    unset($data['meta_value']);
                }
            }
        }
    } else {
        $usermeta = $database['query']->table('ps_usermeta')->where("id", $id)->get();
        if ($usermeta) {
            foreach ($usermeta as $row) {
                $data = ["user_id" => $row['user_id'], "meta_key" => $row['meta_key'], "meta_value" => $row['meta_value']];
            }
        }
    }
    return $data;
}

/** #update Record from database
 *  
 *  @param string $id requiered
 *  id to update for detect data
 *  @param string $metaValue requiered
 *  metaValue for update variable
 *  @param string $switcher requiered
 *  switcher for select table just 'user' and 'option'
 *  
 *  returned result array true or false
 * 
 */

function update_meta($id, $metaValue, $switcher)
{
    $database = DatabaseQuery();
    $data = [];
    switch ($switcher) {
        case 'user':
            $data = $database['query']->table('ps_usermeta')->where("id", $id)->update(['meta_value' => $metaValue]);
            break;
        case 'site':
            $data = $database['query']->table('ps_options')->where("id", $id)->update(['meta_value' => $metaValue]);
            break;
    }
    return $data;
}

/** #set new Record to database
 * 
 * set_meta 
 *    @param string $reserve_id requiered
 *    @param string $metaKey requiered
 *    @param string $metaValue requiered
 *    @param string $switcher requiered
 *    set new meta for reserve
 * 	  set switcher for switch bitween tables
 *    in return show array with parms result and insert_id
 * 
 */
function set_meta($_id, $metaKey, $metaValue, $switcher)
{
    $database = DatabaseQuery();
    $data = [];
    switch ($switcher) {
        case 'user':
            $data = $database['query']->table('ps_usermeta')->create(["user_id" => $_id, "meta_key" => $metaKey, "meta_value" => $metaValue]);
            break;
        case 'site':
            $data = $database['query']->table('ps_options')->create(["meta_key" => $metaKey, "meta_value" => $metaValue]);
            break;
    }
    return $data;
}


/**
 * get_site_option
 *    @param string $meta_key optional
 *    when called return array of id , meta_key and meta_value
 *    when send meta_key return A meta_key and meta_value
 * 
 */

function get_site_option($meta_key = "")
{
    $database = DatabaseQuery();
    $data = ["meta_key" => $meta_key, "meta_value" => NULL];
    if ($meta_key == "") {
        $options = $database['query']->table('ps_options')->get();
        if ($options) {
            foreach ($options as $row) {
                $data[] = ["id" => $row['id'], "meta_key" => $row['meta_key'], "meta_value" => $row['meta_value']];
            }
            unset($data['meta_key']);
            unset($data['meta_value']);
        }
    } else {
        $option = $database['query']->table('ps_options')->where("meta_key", $meta_key)->get();
        if ($option) {
            foreach ($option as $row) {
                $data = ["id" => $row['id'], "meta_key" => $row['meta_key'], "meta_value" => $row['meta_value']];
            }
        }
    }
    return $data;
}

/** #Set or Update usermeta 
 * @param string $user_id requiered
 * @param string $metaKey requiered
 * @param string $metaValue requiered
 * 
 */

function SetOrUpdateMeta($_id, $metaKey, $metaValue, $switcher)
{
    $data = false;
    switch ($switcher) {
        case 'user':
            $usermeta = get_user_meta($_id, $metaKey);
            if ($usermeta['meta_value']) {
                $data = update_meta($usermeta['id'], $metaValue, "usermeta");
            } else {
                $data = set_meta($_id, $metaKey, $metaValue, "usermeta");
            }
            break;
        case "site":
            $sitemeta = get_site_option($metaKey);
            if ($sitemeta['meta_value']) {
                $data = update_meta($sitemeta['id'], $metaValue, "site");
            } else {
                $data = set_meta(null, $metaKey, $metaValue, "site");
            }
            break;
    }
    return $data;
}


/**
 * encrypt and decrypt function 
 * @param string $string requiered
 * @param string $action optional
 * 
 */

function encrypt_decrypt($string, $action = 'encrypt')
{
    $encrypt_method = ENCRYPT_METHOD;
    $secret_key = SECRET_KEY;
    $secret_iv = SECRET_IV;
    $key = hash('sha256', $secret_key);
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else if ($action == 'decrypt') {
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    }
    return $output;
}


/**
 * RealIpAddr for get ip address
 */

function RealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}

/**
 * CheckToken
 * check user token
 */

function CheckToken($token)
{
    $checker['status'] = false;
    $checker['message'] = "لطفا دوباره وارد شوید";
    $decrypt_cookie = encrypt_decrypt($token, "decrypt");
    $decrypt_cookie_array = explode("_!$%$!_", $decrypt_cookie);
    if (isset($decrypt_cookie_array[0]) && isset($decrypt_cookie_array[1]) && isset($decrypt_cookie_array[2]) && $decrypt_cookie_array[2] >= time()) {
        $database = DatabaseQuery();
        $result = $database['query']->table('gv_users')->where("id", $decrypt_cookie_array[0])->where("phone", $decrypt_cookie_array[1])->get();
        $isexist = isset($result[0]) ? $result[0] : $result;
        if (isset($isexist['id']) && $isexist['user_status'] == 1) {
            $checker['status'] = true;
            $checker["_id"] = $isexist['id'];
            $checker["_role"] = $isexist['user_role'];
            $checker["_phone"] = $isexist['phone'];
            $checker["_time"] = mds_date("Y/m/d H:i:s", $decrypt_cookie_array[2]);
            $checker['message'] = "اطلاعات شما صحیح می باشد";
        } else {
            $checker['message'] = "حساب کاربری شما مسدود می‌باشد";
        }
    }
    return $checker;
}

function CheckUrl()
{
    try {
        global $router;
        $data = false;
        $CurrentUri = explode("/", $router->getCurrentUri());
        $opened_url = json_decode(get_site_option("opened_url")["meta_value"], true);
        if ($opened_url) {
            foreach ($opened_url as $value) {
                if (strpos("/" . $CurrentUri[1], $value) !== false) {
                    $data = true;
                }
            }
        }
        return $data;
    } catch (\Throwable $th) {
        $response = ['success' => false, 'err' => 'error', 'message' => "خطای سیستم", 'code' => 503];
        header('Content-Type: application/json', true, $response['code']);
        exit(json_encode($response));
    }
}

function CheckAccess($role, $access, $user_id)
{
    $data = false;
    $user_capp = get_site_option($role . "_capp")['meta_value'];
    if ($user_capp != null) {
        $user_capp = json_decode($user_capp, true);
        $data = (isset($user_capp[$access]) && $user_capp[$access] == 1) ? true : false;
        return $data;
    }
    return $data;
}

function ChangeAddAccess($access, $status, $role)
{
    $_capp = get_site_option($role . "_capp")['meta_value'];
    if ($_capp != null) {
        $_capp = json_decode($_capp, true);
        $_capp[$access] = $status;
        $data = SetOrUpdateMeta("0", $role . "_capp", json_encode($_capp), "site");
    } else {
        $_capp = [];
        $_capp[$access] = $status;
        $data = SetOrUpdateMeta("0", $role . "_capp", json_encode($_capp), "site");
    }
    return $data;
}

// function file_save_fun($file_name = null, $fileb64 = null)
// {
// 	$back = false;
// 	if ($fileb64 != null && $file_name != null) {
// 		$fileb64 = str_replace('data:image/png;base64,', '', $fileb64);
// 		$fileb64 = str_replace('data:image/jpg;base64,', '', $fileb64);
// 		$fileb64 = str_replace('data:image/jpeg;base64,', '', $fileb64);
// 		$fileb64 = str_replace('data:image/webp;base64,', '', $fileb64);
// 		$fileb64 = str_replace('data:application/pdf;base64,', '', $fileb64);
// 		$fileb64 = str_replace(' ', '+', $fileb64);
// 		$data_image = base64_decode($fileb64);
// 		file_put_contents(UPLOAD_PATH . "/" . $file_name, $data_image);
// 		$back = true;
// 	}
// 	return $back;
// }

function number_switch($variable)
{
    switch ($variable) {
        case '1':
            $persion_number = "یک";
            break;
        case '2':
            $persion_number = "دو";
            break;
        case '3':
            $persion_number = "سه";
            break;
        case '4':
            $persion_number = "چهار";
            break;
        case '5':
            $persion_number = "پنج";
            break;
        case '6':
            $persion_number = "شش";
            break;
        case '7':
            $persion_number = "هفت";
            break;
        case '8':
            $persion_number = "هشت";
            break;
        case '9':
            $persion_number = "نه";
            break;
        case '10':
            $persion_number = "ده";
            break;
        default:
            $persion_number = $variable;
            break;
    }
    return $persion_number;
}

// persion time
function mds_date($format, $when = "now", $persianNumber = 0)
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
        list($Dyear, $Dmonth, $Dday) = gregorian_to_mds($year, $month, $day);
        $when = mktime(date("H") + $TZhours, date("i") + $TZminute, date("s"), date("m"), date("d"), date("Y"));
    } else {
        $when += $TZhours * 3600 + $TZminute * 60;
        $date = date("Y-m-d", $when);
        list($year, $month, $day) = preg_split('/-/', $date);

        list($Dyear, $Dmonth, $Dday) = gregorian_to_mds($year, $month, $day);
    }

    $need = $when;
    $year = date("Y", $need);
    $month = date("m", $need);
    $day = date("d", $need);
    $i = 0;
    $subtype = "";
    $subtypetemp = "";
    list($Dyear, $Dmonth, $Dday) = gregorian_to_mds($year, $month, $day);
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
                    $result .= Convertnumber2farsi($result1);
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
                $result .= monthname($Dmonth);
                break;
            case "g":
                $result1 = date("g", $need);
                if ($persianNumber == 1)
                    $result .= Convertnumber2farsi($result1);
                else
                    $result .= $result1;
                break;
            case "G":
                $result1 = date("G", $need);
                if ($persianNumber == 1)
                    $result .= Convertnumber2farsi($result1);
                else
                    $result .= $result1;
                break;
            case "h":
                $result1 = date("h", $need);
                if ($persianNumber == 1)
                    $result .= Convertnumber2farsi($result1);
                else
                    $result .= $result1;
                break;
            case "H":
                $result1 = date("H", $need);
                if ($persianNumber == 1)
                    $result .= Convertnumber2farsi($result1);
                else
                    $result .= $result1;
                break;
            case "i":
                $result1 = date("i", $need);
                if ($persianNumber == 1)
                    $result .= Convertnumber2farsi($result1);
                else
                    $result .= $result1;
                break;
            case "j":
                $result1 = $Dday;
                if ($persianNumber == 1)
                    $result .= Convertnumber2farsi($result1);
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
                    $result .= Convertnumber2farsi($result1);
                else
                    $result .= $result1;
                break;
            case "n":
                $result1 = $Dmonth;
                if ($persianNumber == 1)
                    $result .= Convertnumber2farsi($result1);
                else
                    $result .= $result1;
                break;
            case "s":
                $result1 = date("s", $need);
                if ($persianNumber == 1)
                    $result .= Convertnumber2farsi($result1);
                else
                    $result .= $result1;
                break;
            case "S":
                $result .= "&#1575;&#1605;";
                break;
            case "t":
                $result .= lastday($month, $day, $year);
                break;
            case "w":
                $result1 = date("w", $need);
                if ($persianNumber == 1)
                    $result .= Convertnumber2farsi($result1);
                else
                    $result .= $result1;
                break;
            case "y":
                $result1 = substr($Dyear, 2, 4);
                if ($persianNumber == 1)
                    $result .= Convertnumber2farsi($result1);
                else
                    $result .= $result1;
                break;
            case "Y":
                $result1 = $Dyear;
                if ($persianNumber == 1)
                    $result .= Convertnumber2farsi($result1);
                else
                    $result .= $result1;
                break;
            case "U":
                $result .= time();
                break;
            case "Z":
                $result .= days_of_year($Dmonth, $Dday, $Dyear);
                break;
            case "L":
                list($tmp_year, $tmp_month, $tmp_day) = mds_to_gregorian(1384, 12, 1);
                echo $tmp_day;
                /*if(lastday($tmp_month,$tmp_day,$tmp_year)=="31")*/
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
function make_time($hour = "", $minute = "", $second = "", $Dmonth = "", $Dday = "", $Dyear = "")
{
    if (!$hour && !$minute && !$second && !$Dmonth && !$Dmonth && !$Dday && !$Dyear)
        return time();
    if ($Dmonth > 11)
        die("Incorrect month number");
    list($year, $month, $day) = mds_to_gregorian($Dyear, $Dmonth, $Dday);
    $i = mktime($hour, $minute, $second, $month, $day, $year);
    return $i;
}

///Find num of Day Begining Of Month ( 0 for Sat & 6 for Sun)
function mstart($month, $day, $year)
{
    list($Dyear, $Dmonth, $Dday) = gregorian_to_mds($year, $month, $day);
    list($year, $month, $day) = mds_to_gregorian($Dyear, $Dmonth, "1");
    $timestamp = mktime(0, 0, 0, $month, $day, $year);
    return date("w", $timestamp);
}

//Find Number Of Days In This Month
function lastday($month, $day, $year)
{
    $Dday2 = "";
    $jdate2 = "";
    $lastdayen = date("d", mktime(0, 0, 0, $month + 1, 0, $year));
    list($Dyear, $Dmonth, $Dday) = gregorian_to_mds($year, $month, $day);
    $lastdatep = $Dday;
    $Dday = $Dday2;
    while ($Dday2 != "1") {
        if ($day < $lastdayen) {
            $day++;
            list($Dyear, $Dmonth, $Dday2) = gregorian_to_mds($year, $month, $day);
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
function days_of_year($Dmonth, $Dday, $Dyear)
{
    $year = "";
    $month = "";
    $year = "";
    $result = "";
    if ($Dmonth == "01")
        return $Dday;
    for ($i = 1; $i < $Dmonth || $i == 12; $i++) {
        list($year, $month, $day) = mds_to_gregorian($Dyear, $i, "1");
        @$result += lastday($month, $day, $year);
    }
    return $result + $Dday;
}

//translate number of month to name of month
function monthname($month)
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
function Convertnumber2farsi($srting)
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
function convert2english($string)
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
function is_kabise($year)
{
    if ($year % 4 == 0 && $year % 100 != 0)
        return true;
    return false;
}

function div($a, $b)
{
    return (int) ($a / $b);
}

function gregorian_to_mds($g_y, $g_m, $g_d)
{
    $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    $m_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);

    $gy = $g_y - 1600;
    $gm = $g_m - 1;
    $gd = $g_d - 1;

    $g_day_no = 365 * $gy + div($gy + 3, 4) - div($gy + 99, 100) + div($gy + 399, 400);

    for ($i = 0; $i < $gm; ++$i)
        $g_day_no += $g_days_in_month[$i];
    if ($gm > 1 && (($gy % 4 == 0 && $gy % 100 != 0) || ($gy % 400 == 0)))
        /* leap and after Feb */
        $g_day_no++;
    $g_day_no += $gd;

    $m_day_no = $g_day_no - 79;

    $j_np = div($m_day_no, 12053); /* 12053 = 365*33 + 32/4 */
    $m_day_no = $m_day_no % 12053;

    $jy = 979 + 33 * $j_np + 4 * div($m_day_no, 1461); /* 1461 = 365*4 + 4/4 */

    $m_day_no %= 1461;

    if ($m_day_no >= 366) {
        $jy += div($m_day_no - 1, 365);
        $m_day_no = ($m_day_no - 1) % 365;
    }

    for ($i = 0; $i < 11 && $m_day_no >= $m_days_in_month[$i]; ++$i)
        $m_day_no -= $m_days_in_month[$i];
    $jm = $i + 1;
    $jd = $m_day_no + 1;

    return array($jy, $jm, $jd);
}

function mds_to_gregorian($m_y, $j_m, $m_d)
{
    $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);
    $m_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);



    $jy = $m_y - 979;
    $jm = $j_m - 1;
    $jd = $m_d - 1;

    $m_day_no = 365 * $jy + div($jy, 33) * 8 + div($jy % 33 + 3, 4);
    for ($i = 0; $i < $jm; ++$i)
        $m_day_no += $m_days_in_month[$i];

    $m_day_no += $jd;

    $g_day_no = $m_day_no + 79;

    $gy = 1600 + 400 * div($g_day_no, 146097); /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */
    $g_day_no = $g_day_no % 146097;

    $leap = true;
    if ($g_day_no >= 36525) /* 36525 = 365*100 + 100/4 */ {
        $g_day_no--;
        $gy += 100 * div($g_day_no, 36524); /* 36524 = 365*100 + 100/4 - 100/100 */
        $g_day_no = $g_day_no % 36524;

        if ($g_day_no >= 365)
            $g_day_no++;
        else
            $leap = false;
    }

    $gy += 4 * div($g_day_no, 1461); /* 1461 = 365*4 + 4/4 */
    $g_day_no %= 1461;

    if ($g_day_no >= 366) {
        $leap = false;

        $g_day_no--;
        $gy += div($g_day_no, 365);
        $g_day_no = $g_day_no % 365;
    }

    for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++)
        $g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap);
    $gm = $i + 1;
    $gd = $g_day_no + 1;

    return array($gy, $gm, $gd);
}

function getBetweenDates($startDate, $endDate)
{
    $rangArray = [];

    $startDate = strtotime($startDate);
    $endDate = strtotime($endDate);

    for (
        $currentDate = $startDate;
        $currentDate < $endDate;
        $currentDate += (86400)
    ) {
        $rangArray[] = mds_date('Y/m/d', $currentDate);
    }

    return $rangArray;
}

function cmp($a, $b)
{
    return strcmp($a['set_time'], $b['set_time']);
}


function AddZeroToDate($date)
{
    $date = explode("/", $date);
    foreach ($date as $datek => $dateval) {
        if ($datek != 0 && strlen($dateval) == 1) {
            $date[$datek] = "0" . $dateval;
        }
    }
    $date = implode("/", $date);
    return $date;
}


function getHTMLByClass($class, $html, $bring_tag = false)
{
    $dom = new DOMDocument;
    libxml_use_internal_errors(true);
    $dom->validateOnParse = true;
    $dom->loadHTML($html);
    $class_arr = array();
    $xpath = new DOMXPath($dom);
    $results = $xpath->query("//*[contains(@class, '$class')]");
    if ($results->length > 0) {
        foreach ($results as $tag) {
            if ($bring_tag === true)
                array_push($class_arr, $tag);
            else
                array_push($class_arr, $dom->saveHTML($tag));
        }
    }
    return $class_arr;
}

function getHTMLByTag($tagName, $attrname, $html, $bring_tag = false)
{
    $dom = new DOMDocument;
    libxml_use_internal_errors(true);
    $dom->validateOnParse = true;
    $dom->loadHTML($html);
    $class_arr = array();
    $xpath = new DOMXPath($dom);
    $results = $xpath->query("//$tagName [@name='$attrname']");
    if ($results->length > 0) {
        foreach ($results as $tag) {
            if ($bring_tag === true)
                array_push($class_arr, $tag);
            else
                array_push($class_arr, $dom->saveHTML($tag));
        }
    }
    return $class_arr;
}
