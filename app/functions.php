<?php

session_start();

function get_user_ip()
{
    $keys = ['HTTP_CF_CONNECTING_IP', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];

    foreach ($keys as $key) {
        if (isset ($_SERVER[$key])) {
            return check_localhost( $_SERVER[$key] );
        }
    }
}

function check_localhost($ip) {
    return ($ip == '127.0.0.1') ? false : $ip;
}


function get_settings()
{
    $settingsPath = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'settings.json']);
    if (!file_exists($settingsPath)) {
        die('file "settings.json" is missing');
    }

    $settingsRaw = file_get_contents($settingsPath);

    if (empty($settingsRaw)) {
        die('file "settings.json" is empty');
    }

    $settingsJson = json_decode($settingsRaw, 1);

    if (!is_array($settingsJson)) {
        die('file "settings.json" is corrupted');
    }
    $settingsJson = utm_settings($settingsJson);
    if (!empty($settingsJson)) {
        $settingsJson = array_filter($settingsJson, function($element) {
            return !empty($element);
        }); 
    }

    return $settingsJson;
}

function utm_settings($array)
{
    $get = $_GET;
    if (empty($get)) {
        return $array;
    }

    if (isset($get['pxl'])) {
        $get['facebook'] = $get['pxl'];
    }

    if (isset($get['ynd'])) {
        $get['yandex'] = $get['ynd'];
        unset($get['ynd']);
    }

    foreach ($get as $key => $value) {
        $array[$key] = $value;
    }
    return $array;
}

function get_root_folder()
{
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $delPath = explode("/app/index.php", $scriptName);
    return trim($delPath[0],'/');
}

function removeFolder($filename, $folder)
{
    $rand = md5(rand());
    $filename = $rand.$filename;
    $folder = $rand.$folder;
    $result = str_replace($folder, '', $filename);
    return trim($result);
}

function requestUnderscoresDelete()
{
    $result = [];
    foreach ($_REQUEST as $key => $value) {
        if ($key[0] != '_') {
            $result[$key] = $value;
        }
    }
    if (isset ($result['PHPSESSID'])) {
        unset($result['PHPSESSID']);
    }
    return $result;
}

function getSubs($array)
{
    if (is_array($array) && !empty($array)) {
        $result = [];
        $array = array_filter($array, function($element) {
            return !empty($element);
        });
        if (!empty($array)) {
            foreach ($array as $key => $value) {
                $check = explode('sub', $key);
                if (empty($check[0]) && isset($check[1])) {
                    $result[$key] = $value;
                }
            }
        }
        return $result;
    }
}

function get_lead_firstname()
{
    if (isset($_SESSION['lead_data']['firstname'])) {
        return $_SESSION['lead_data']['firstname'];
    }
    return false;
}

function get_link_after_thanks()
{
    if (isset($_SESSION['link_after_thanks'])) {
        return $_SESSION['link_after_thanks'];
    }
    return '/';
}

// блокировка по ip

function check_banned_ip()
{
    $ip = get_user_ip();
    $bannedBase = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'banned_ip.txt']);
    if ( (!file_exists($bannedBase)) ) {
        die('file "banned_ip.txt" is missing');
    }

    $ips = compare_banned_ips();

    if (in_array($ip, $ips)) {
        $settings = file_get_contents(
            implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'settings.json'])
        );

        $array = json_decode($settings, 1);
        $ban_link = ($array['ban_redirect']) ? $array['ban_redirect'] : 'https://google.com';
        header("Location:{$ban_link}");
    }
}

function get_base_banned_ip()
{
    $base = implode(DIRECTORY_SEPARATOR, [__DIR__, 'banned_ip.txt']);
    $raw = file_get_contents( $base );
    $raw = str_replace("\r\n", "\n", $raw);
    $array = explode("\n", $raw);
    return $array;
}

function compare_banned_ips()
{
    $result = true;

    $base = implode(DIRECTORY_SEPARATOR, [__DIR__, 'banned_ip.txt']);
    $bannedBase = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'banned_ip.txt']);
    
    $source = file_get_contents($base);
    $source = str_replace("\r\n","\n", $source);
    $sourceAr = explode("\n", $source);

    $base = file_get_contents($bannedBase);
    $base = str_replace("\r\n","\n", $base);
    $baseAr = explode("\n", $base);

    foreach ($sourceAr as $sar) {
        if (!in_array($sar, $baseAr)) {
            $result = false;
            $baseAr[] = $sar;
        }
    }

    return $baseAr;
}

// если сайт субдомен и нет ssl сертификата, редиректим на основной домен
function check_subdomain_non_ssl()
{
    if (dotenv('SUBDOMAIN_REDIRECT') != 1) {
        return false;
    }

    $host = array_reverse(explode(".", $_SERVER['HTTP_HOST']));

    $excepts = [
        ($host[1] == 'com'),
        ($host[1] == 'co')
    ];

    if (in_array(true, $excepts)) {
        $host[0] = "{$host[1]}.{$host[0]}";
        unset($host[1]);
        $host = array_values($host);
    }

    $schema = ($_SERVER['REQUEST_SCHEME'] == 'http');
    
    if ($schema && isset($host[2])) {
        $domain = "https://{$host[1]}.{$host[0]}{$_SERVER['REQUEST_URI']}";
        header("Location:{$domain}");
    }
}

function dotenv($param = null)
{    
    return ( isset($_ENV[$param]) ) ? $_ENV[$param] : false;
}

function issetUserdata($key)
{
    return isset($_SESSION['userdata'][$key]);
}

function getUserdata($key)
{
    $data = $_SESSION['userdata'][$key];
    unset($_SESSION['userdata'][$key]);
    return $data;
}

function check_black_white_url()
{
    $settings = get_settings();
    if (($_ENV['DEBUG'] != 1) && (isset($settings['cloakit'])) && (!empty($settings['cloakit']))) {
        $uri =  explode(
            '/', explode("?", $_SERVER['REQUEST_URI'])[0]
        );
        if (end($uri) == 'b.php' || end($uri) == 'w.php') {
            header("HTTP/1.0 404 Not Found");
            die;
        }
    }
}

function check_is_domain_not_ip()
{
    if ($_ENV['DOMAIN_NOT_IP']) {
        $host_dm = $_SERVER['HTTP_HOST'];
        $host_nm = $_SERVER['SERVER_NAME'];
        $host_ip = $_SERVER['SERVER_ADDR'];
    
        if ( ($host_dm == $host_nm) or ($host_dm == $host_ip) ) {
            header("HTTP/1.0 404 Not Found");
            die('Not Found');
        }
    }
}

function create_lead_initialization()
{
    if ( isset($_SESSION['lead_initialized']) ) { return false; }
    $_SESSION['lead_initialized'] = true;
}

function lead_can_be_sent()
{
    if ( intval($_ENV['DEBUG']) == true ) { return true; }
    if ( !isset($_SESSION['lead_initialized']) ) { return false; }
    if ( $_SESSION['is_sent'] == true ) { return false; }
    return true;
}

function lead_is_sent()
{
    $_SESSION['is_sent'] = true;
}

function starter_timecheck()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        return false;
    }
    if ($_ENV['TIMECHECK'] == 1) {
        if (!check_user_query()) {
            check_query_timestamp();
        }
    }
}

function check_query_timestamp()
{
    if (!isset($_REQUEST['t'])) {
        redirect_to_google();
    }
    $now = time();
    $timeDiff = $now - intval($_REQUEST['t']);
    if ($timeDiff >= 60) {
        redirect_to_google();
    }
    give_user_access();
}

function redirect_to_google()
{
    $redirect = 'https://google.com';
    header("Location: {$redirect}");
    die();
}

function give_user_access()
{
    setcookie('user_timestamp', time());
}

function check_user_query()
{
    return (isset($_COOKIE['user_timestamp']));
}
