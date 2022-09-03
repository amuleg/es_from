<?php

namespace App\Classes;

use App\Classes\Send;

class Actions
{
    public static function getLocation()
    {
        header("Content-type:text/plain");
        $location = new \App\Classes\GetLocation();
        $print = $location->get_all();
        echo json_encode($print, JSON_PRETTY_PRINT);
    }

    public static function linkToMetrikaStats()
    {
        $settings = self::get_settings();
        if (isset($settings['yandex']) && $settings['yandex'] != '') {
            $url = 'https://metrika.yandex.ru/dashboard?group=dekaminute&period=today&id=' . $settings['yandex'];
            header("Location:{$url}");
        } else {
            header("Content-type:text/plain");
            echo json_encode([
                'message' => 'Metrika parameter is empty'
            ], JSON_PRETTY_PRINT);
        }
    }

    public static function linkToCloakIt()
    {
        $settings = self::get_settings();
        if (isset($settings['cloakit']) && $settings['cloakit'] != '') {
            $url = 'https://panel.cloakit.space/campaign/' . $settings['cloakit'];
            header("Location:{$url}");
        } else {
            header("Content-type:text/plain");
            echo json_encode([
                'message' => 'Cloakit parameter is empty'
            ], JSON_PRETTY_PRINT);
        }
    }

    public static function sendForm()
    {
        $send = new Send();
        $settings = self::get_settings();
        $action = 'neogara';

        if (isset ($settings['partners']) && !isset($settings['partner'])) {
            $settings['partner'] = $settings['partners'];
        }
        
        if (isset ($settings['partner']) && is_array($settings['partner'])) {
            foreach ($settings['partner'] as $partner => $value) {
                $all[] = $partner;
                if ($value == 1) {
                    $action = $partner;
                break;
                }
            }
            if (empty($action)) {
                $action = $all[0];
            }
        }
        
        if (isset ($settings['partners']) && is_array($settings['partners'])) {
            foreach ($settings['partners'] as $partner => $value) {
                $all[] = $partner;
                if ($value == 1) {
                    $action = $partner;
                break;
                }
            }
            if (empty($action)) {
                $action = $all[0];
            }
        }

        if (isset($_GET['partner'])) {
            $gets = $_GET['partner'];
            if ($gets == 'neogara' or $gets == 'neogara_js') {
                $action = $gets;
            }
        }
        
        if (!empty($action)) {
            $send->$action();
        }
    }

    public static function get_settings()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', 'settings.json']);
        $raw = file_get_contents($path);
        $json = self::utm_settings( json_decode($raw, 1) );
        return $json;
    }
    

    public static function utm_settings($json)
    {
        if ( !empty($_GET) ) {
            $_GET['facebook'] = (isset($_GET['pxl'])) ? $_GET['pxl'] : null;
            $_GET['yandex'] = (isset($_GET['ynd'])) ? $_GET['ynd'] : null;
            $json = array_diff(array_merge($json, $_GET), array(null));
        }
        return $json;
    }

    public static function get_intl_tel_input_file()
    {
        $filename = end(explode("/", explode('?', $_SERVER['REQUEST_URI'])[0] ));
        $filepath = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'Templates', 'intl_tel_input_files', $filename]);
        $ext = end(explode(".", $filename));
        
        switch ($ext) {
            case 'css':
                header("Content-type: text/css");
                break;
            case 'js':
                header("Content-type: text/javascript");
                break;
            case 'png':
                header("Content-type: image/png");
                break;
            default:
                header("Content-type: text/plain");
        }
        
        echo file_get_contents($filepath);
    }

    public static function check_rkn_ban()
    {
        header("Content-type: text/plain");
        $registry = @file_get_contents('https://reestr.rublacklist.net/api/v2/domains/json/');
        if (empty($registry)) { die('Не удалось получить массив доменов'); }
        $registry_array = json_decode($registry);
        if ( !is_array( $registry_array ) ) { die( 'Массив доменов пришёл не корректный' ); }
        echo ( !in_array( $_SERVER['HTTP_HOST'], $registry_array) ) ? 'Домен чист' : 'Домен не чист';
    }

    public static function get_settings_json()
    {
        header("Content-Type: application/json");
        $settings = self::get_settings();
        echo json_encode( $settings, JSON_PRETTY_PRINT );
    }

    public static function check_token()
    {
        if (isset($_POST['checkToken'])) {
            echo $_POST['checkToken'];
        }
    }
}