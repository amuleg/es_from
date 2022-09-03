<?php

namespace App\Classes;

class GetLocation
{
    public $api;

    public function __construct()
    {
        if( !$this->check_session() ) {
            $this->import_to_session();
        }
    }

    public function get_all()
    {
        return $this->print_session_location();
    }

    public function import_to_session()
    {
        $userIp = get_user_ip();
        if ( $userIp == '127.0.0.1' ) {
            return false;
        }

        $api = $this->reformat_2ip(
            $this->get_by_api_2ip()
        );

        if (empty($api['country']) or empty($api['city'])) {
            $api = $this->get_by_api();
        }
        
        if ($api && !empty($api) && !empty($api['country'])) {
            $json = json_encode($api);
            $_SESSION['locations'][$userIp] = base64_encode($json);
        }
    }

    public function check_session()
    {
        $userIp = get_user_ip();
        return isset($_SESSION['locations'][$userIp]);
    }

    public function print_session_location()
    {
        $userIp = get_user_ip();
        
        if ($userIp == false) {
            return false;
        }

        $base = $_SESSION['locations'][$userIp];
        $json = base64_decode($base);
        return json_decode($json, 1);
    }

    public function reformat_2ip($array)
    {
        $result = [
            'ip' => $array['ip'],
            'city' => $array['city'],
            'region' => $array['region'],
            'country' => $array['country_code'],
            'loc' => $array['latitude'].','.$array['longitude'],
        ];
        return $result;
    }

    public function get_by_api_2ip()
    {
        $userIp = get_user_ip();

        if ($userIp == false) {
            return false;
        }

        $token = '95e0eb50c21c1536';
        $token = (dotenv('2IP_TOKEN')) ? dotenv('2IP_TOKEN') : $token;
        
        $url = "https://api.2ip.ua/geo.json?ip={$userIp}&key={$token}";

        $raw = @file_get_contents($url);
        if (!empty($raw)) {
            $json = json_decode($raw, 1);
            if (is_array($json) && isset($json['country_code']) && !empty($json['country_code'])) {
                return $json;
            }
        }
        return false;
    }

    public function get_by_api()
    {
        $userIp = get_user_ip();
        $settings = get_settings();

        if ($userIp == false) {
            return false;
        }

        $defaultToken = 'bd2672960789d6';
        $token = (isset($settings['intlToken'])) ? $settings['intlToken'] : $defaultToken;
        $apiUrl = "http://ipinfo.io/{$userIp}?token={$token}";

        $raw = @file_get_contents($apiUrl);
        if (!empty($raw)) {
            $json = json_decode($raw, 1);
            if (is_array($json) && isset($json['country']) && !empty($json['country'])) {
                return $json;
            }
        }
        return false;
    }

    public function get_country_name()
    {
        $result = $this->print_session_location();
        if ($result == false) {
            return [
                'EN' => '',
                'RU' => '',
                'code' => ''
            ];
        }
        $names = $this->import_country_names();
        if (!empty($result) && isset($result['country'])) {
            $code = $result['country'];
            foreach ($names as $name) {
                if ($name['alpha2'] == $code) {
                    return [
                        'EN' => $name['english'],
                        'RU' => $name['name'],
                        'code' => $code
                    ];
                }
            }
        }
    }

    public function import_country_names()
    {
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'country_names.json']);
        if (!file_exists($path)) {
            return [];
        }
        $raw = file_get_contents($path);
        $array = json_decode($raw, 1);
        return $array;
    }
}