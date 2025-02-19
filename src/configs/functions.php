<?php


if (!defined('ABSPATH')) {
    header('location: /', true, 302);
}

use App\Helpers\Config;
use App\Database\PDODatabaseConnection;
use App\Database\PDOQueryBuilder;
use App\Helpers\Number2Word;

function json_encode_unicode(array|object $array): string
{
    return json_encode($array, JSON_UNESCAPED_UNICODE);
}

function json_decode_unicode(string $string, ?bool $associative = null): array|object|null
{
    return json_decode(str_replace("&#34;", '"', $string), $associative);
}

function DatabaseQuery()
{
    $pdoConnection = new PDODatabaseConnection(DbConfig);
    $queryBuilder = new PDOQueryBuilder($pdoConnection->connect());
    return ["connection" => $pdoConnection, "query" => $queryBuilder];
}
function DatabaseQuerySqlite()
{
    $SqliteConfig = Config::get("database", "sqlite");
    $pdoConnection = new PDODatabaseConnection($SqliteConfig, 'sqlite');
    $queryBuilder = new PDOQueryBuilder($pdoConnection->connect());
    return ["connection" => $pdoConnection, "query" => $queryBuilder];
}

function encryptdata(string $plaintext, string $secret_key = SECRET_KEY, string $cipher = ENCRYPT_METHOD, string $hash_hmac = 'sha256'): bool|string
{
    $supported_ciphers = openssl_get_cipher_methods();
    if (!in_array($cipher, $supported_ciphers)) {
        return false;
    }

    $ivlen = openssl_cipher_iv_length($cipher);
    if ($ivlen <= 0) {
        return false;
    }

    $key = @openssl_digest($secret_key, $hash_hmac, true);
    $iv = openssl_random_pseudo_bytes($ivlen);

    if ($iv === false) {
        return false;
    }

    $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    $hmac = hash_hmac($hash_hmac, $iv . $ciphertext_raw, $key, true);

    return base64_encode($iv . $hmac . $ciphertext_raw);
}

// --- Decrypt --- //
function decryptdata(string $ciphertext, string $secret_key = SECRET_KEY, string $cipher = ENCRYPT_METHOD, string $hash_hmac = 'sha256'): bool|string
{
    $key = @openssl_digest($secret_key, $hash_hmac, TRUE);
    $ivlen = openssl_cipher_iv_length($cipher);
    $c = base64_decode($ciphertext);
    $iv = substr($c, 0, $ivlen);
    $hmac = substr($c, $ivlen, $sha2len = 32);
    $ciphertext_raw = substr($c, $ivlen + $sha2len);
    $calcmac = hash_hmac($hash_hmac, $iv . $ciphertext_raw, $key, true); // Include IV in HMAC calculation
    if (hash_equals($hmac, $calcmac)) {
        return openssl_decrypt($ciphertext_raw, $cipher, $key, OPENSSL_RAW_DATA, $iv);
    }
    return false; // Return false if HMAC verification fails
}

function mb_strrev($str)
{
    preg_match_all('/./us', $str, $ar);
    return join('', array_reverse($ar[0]));
}

function getRandomHashHmac()
{
    $hash_hmacs = [
        'sha256',
        'sha512/256',
        'sha3-256',
        'ripemd256',
        'snefru',
        'snefru256',
        'gost',
        'gost-crypto',
        'haval256,3',
        'haval256,4',
        'haval256,5',
    ];
    return $hash_hmacs[array_rand($hash_hmacs)];
}
function getRandomCipher()
{
    $ciphers = [
        'aes-128-cbc',
        'aes-128-cfb',
        'aes-128-cfb1',
        'aes-128-cfb8',
        'aes-128-ctr',
        'aes-128-ofb',
        'aes-128-wrap-pad',
        'aes-192-cbc',
        'aes-192-cfb',
        'aes-192-cfb1',
        'aes-192-cfb8',
        'aes-192-ctr',
        'aes-192-ofb',
        'aes-192-wrap-pad',
        'aes-256-cbc',
        'aes-256-cfb',
        'aes-256-cfb1',
        'aes-256-cfb8',
        'aes-256-ctr',
        'aes-256-ofb',
        'aes-256-wrap-pad',
        'aria-128-cbc',
        'aria-128-cfb',
        'aria-128-cfb1',
        'aria-128-cfb8',
        'aria-128-ctr',
        'aria-128-ofb',
        'aria-192-cbc',
        'aria-192-cfb',
        'aria-192-cfb1',
        'aria-192-cfb8',
        'aria-192-ctr',
        'aria-192-ofb',
        'aria-256-cbc',
        'aria-256-cfb',
        'aria-256-cfb1',
        'aria-256-cfb8',
        'aria-256-ctr',
        'aria-256-ofb',
        'camellia-128-cbc',
        'camellia-128-cfb',
        'camellia-128-cfb1',
        'camellia-128-cfb8',
        'camellia-128-ctr',
        'camellia-128-ofb',
        'camellia-192-cbc',
        'camellia-192-cfb',
        'camellia-192-cfb1',
        'camellia-192-cfb8',
        'camellia-192-ctr',
        'camellia-192-ofb',
        'camellia-256-cbc',
        'camellia-256-cfb',
        'camellia-256-cfb1',
        'camellia-256-cfb8',
        'camellia-256-ctr',
        'camellia-256-ofb',
        'chacha20',
        'des-ede-cbc',
        'des-ede-cfb',
        'des-ede-ofb',
        'des-ede3-cbc',
        'des-ede3-cfb',
        'des-ede3-cfb1',
        'des-ede3-cfb8',
        'des-ede3-ofb',
        'sm4-cbc',
        'sm4-cfb',
        'sm4-ctr',
        'sm4-ofb',
    ];
    return $ciphers[array_rand($ciphers)];
}

function random_color(): string
{
    $color = "";
    for ($i = 0; $i < 3; $i++) {
        $color .= str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
    }
    return $color;
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

function number_fa_format(int $number): string
{
    $persion_number = new Number2Word;
    return $persion_number->numberToWords($number);
}

function gettypeMap(mixed $value): string
{
    $typeofnumber = gettype($value);

    // Map string types to the desired format
    $typeMapping = [
        'integer' => 'int',
        'double' => 'float',
        'boolean' => 'bool',
        'string' => 'string',
        'array' => 'array',
        'object' => 'object',
        'resource' => 'resource',
        'NULL' => 'null',
    ];
    $desiredType = $typeMapping[$typeofnumber] ?? $typeofnumber;
    return $desiredType;
}


function sortArrayOfObjects(array $array, string $property): array|object
{
    usort($array, function ($a, $b) use ($property) {
        return strcmp($a[$property], $b[$property]);
    });

    return $array;
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

function get_function($method, $class = null)
{

    if (!empty($class)) $func = new ReflectionMethod($class, $method);
    else $func = new ReflectionFunction($method);

    $filename = $func->getFileName();
    $start_line = $func->getStartLine() - 1; // it's actually - 1, otherwise you wont get the function() block
    $end_line = $func->getEndLine();
    $length = $end_line - $start_line;

    $source = file($filename);
    $body = implode("", array_slice($source, $start_line, $length));

    return $body;
}
function getVariableNameFromAssignment($assignment)
{
    $variableNames = [];

    // Use a regular expression to match variable names
    if (preg_match_all('/\$[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*(?![^\[]*\])/', $assignment, $matches)) {
        // Merge the matched variable names into the result array
        $variableNames = array_merge($variableNames, $matches[0]);
    }

    // Remove duplicates by converting the array to a set and back to an array
    $variableNames = array_values(array_unique($variableNames));

    return $variableNames;
}
function api_error_handler($errno, $errstr, $errfile, $errline)
{
    return api_error($errstr, $errno, 500, $errline, $errfile);
}
function api_exception_handler($exception)
{
    return api_error($exception->getMessage(), $exception->getCode(), 500, $exception->getLine(), $exception->getFile());
}

function api_error($error, $errno, $code, $line = 0, $file = '')
{
    header('Content-Type: application/json', true, $code);
    $error_loger = json_encode_unicode([
        'success' => false,
        'errno'   => $errno,
        'error'   => $error,
        'line'   => $line,
        'file'   => $file,
    ]);
    die($error_loger);
}

if (ISTEST === false) {
    set_error_handler('api_error_handler');
    set_exception_handler('api_exception_handler');
}

function CurlRequestGet(string $url, array $params)
{
    $queryinurl = http_build_query($params);
    $url = $url . "?" . $queryinurl;
    return file_get_contents($url);
}

function randomStringGenerate(int $Counter = 10, $mode = "hard"): string
{
    if ($mode == "hard") {
        $alphabet = ':|;!@#$%*(){}[]<>.,-+/&abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    } else {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    }
    $pass = array();
    $alphaLength = strlen($alphabet) - 1;
    for ($i = 0; $i < $Counter; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    if (preg_match('/(:;|\*%\*)|(?:\b(for|foreach|while|if|elseif|else|switch)\b)/', implode($pass)) || function_exists(implode($pass))) {
        return randomStringGenerate($Counter);
    } else {
        return implode($pass);
    }
}

/**
 * Check the syntax of some PHP code.
 *
 * @param string $code PHP code to check.
 * @return array|null|string If null, then the check was successful; otherwise, an array [message, line] of errors is returned.
 */
function php_syntax_error($code)
{
    $code = "<?php\n" . $code;
    $braces = 0;
    $inString = 0;

    foreach (token_get_all($code) as $token) {
        if (is_array($token)) {
            switch ($token[0]) {
                case T_CURLY_OPEN:
                case T_DOLLAR_OPEN_CURLY_BRACES:
                case T_START_HEREDOC:
                    ++$inString;
                    break;
                case T_END_HEREDOC:
                    --$inString;
                    break;
            }
        } else if ($inString & 1) {
            switch ($token) {
                case '`':
                case '\'':
                case '"':
                    --$inString;
                    break;
            }
        } else {
            switch ($token) {
                case '`':
                case '\'':
                case '"':
                    ++$inString;
                    break;
                case '{':
                    ++$braces;
                    break;
                case '}':
                    if ($inString) {
                        --$inString;
                    } else {
                        --$braces;
                        if ($braces < 0) {
                            break 2;
                        }
                    }
                    break;
            }
        }
    }

    ob_start();
    $code = substr($code, strlen("<?php\n"));
    $braces || $code = "if(0){{$code}\n}";

    try {
        eval($code);
        ob_end_clean();
        return $code; // No syntax errors
    } catch (ParseError $e) {
        ob_end_clean();
        $errorMessage = $e->getMessage();
        $errorLine = $e->getLine();
        return [$errorMessage, $errorLine];
    }
}

function base64ToFile($base64)
{
    list($type, $base64Data) = explode(';', $base64);
    list(, $extension) = explode('/', $type);
    list(, $base64Data)      = explode(',', $base64Data);
    $fileName = time() . '.' . $extension;
    $base64Data = base64_decode($base64Data);
    file_put_contents(UPLOADLIST_PATH . $fileName, $base64Data);
    return $fileName;
}

function JsonCurlExecute(string $url, array|object $data)
{
    header('Content-Type: application/json');
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => API_URL . $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            'AUTHKEY: ' . API_KEY,
            'AUTHIV: ' . API_IV,
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    return json_decode($response, true);
}
