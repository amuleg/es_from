<?php

namespace App\Classes;

use App\Classes\Neogara;

class General
{
    public $settings;
    public $variables;
    public $location;
    public $inputs_fill;

    public function __construct()
    {
        $this->import_env();
        $this->get_settings();
        $this->check_needed_files();
        $this->check_last_symb();
        $this->utm_settings();
        $this->print_errors();
    }

    private function import_env()
    {
        $envData = [];
        $envFiles = [
            implode(DIRECTORY_SEPARATOR, [__DIR__, '..', 'root', '.env']),
            implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', '.env']),
            implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', explode(":", $_SERVER['HTTP_HOST'])[0], '.env'])
        ];
        
        foreach ( $envFiles as $envFile ) {
            if ( file_exists( $envFile) ) {
                $raw = str_replace( "\r\n", "\n", file_get_contents( $envFile ) );
                $envData = array_merge( $envData, explode("\n", $raw) );
            }
        }
        
        if ( !empty($envData) ) {
            foreach ( $envData as $string ) {
                [ $key, $value ] = explode("=", $string );
                $_ENV[trim($key)] = trim($value);
            }
        }
    }

    public function check_last_symb()
    {
        $ref = $this->get_ref();
        $array = str_split($ref);
        $last = end($array);
        if ($last == '?') {
            $ref = trim(trim($ref, '?\/'), '?\/');
            header("Location:{$ref}");
        }
    }

    public function get_file()
    {
        $file = explode("/", 
            explode("?", $_SERVER['REQUEST_URI'])[0]
        );
        return end($file);
    }

    public function set($key, $value = null)
    {
        if (!empty($key) || !empty($value)) {
            $this->variables[$key] = $value;
        }
    }

    public function inputs_fill_action($view = '')
    {
        $inputs = [];
        if (!empty($_SESSION['form_fields'])) {
            foreach ($_SESSION['form_fields'] as $key => $value) {
                if ($key[0] != '_') {
                    $inputs[$key] = $value;
                }
            }
        }
        $inputs = array_diff($inputs, ['']);

        preg_match_all('/<input.*?name=[\'|"](.*?)[\'|"].*?>/', $view, $forms);
        if (!empty($forms)) {
            foreach($forms[0] as $k => $input) {
                $input_key = $forms[1][$k];
                if (isset($inputs[$input_key])) {
                    $add = "value=\"{$inputs[$input_key]}\"";
                    $input_b = str_replace('>', ' '.$add.'>', $input);
                    $view = str_replace($input, $input_b, $view);
                }
            }
        }
        unset($_SESSION['form_fields']);
        return $view;
    }

    public function run()
    {
        $view = $this->render();
        if (!$view) {
            return false;
        }
        
        if ($this->check_form($view)) {
            create_lead_initialization();
        }
        
        $this->get_location();

        if ($this->inputs_fill) {
            $view = $this->inputs_fill_action($view);
        }
        
        if ($this->get_partner() == 'neogara') {
            $neogara = new Neogara();
            $view = $neogara->click_reg($view);
        } else {
            $view = $this->get_ref_field($view);
        }

        if ($this->get_partner() == 'neogara_js') {
            $view = $this->add_neo_js($view);
        }

        $view = $this->check_utm($view);
        $view = $this->check_errors($view);

        $this->check_redirect_session();

        $view = $this->add_utm_to_links($view);
        $view = $this->add_after_submit_script($view);
        $view = $this->change_url_get_ipinfo($view);
        $view = $this->replace_braces_to_vars($view);

        $view = $this->add_intl_tel_input( $view );
        echo $view;
    }

    public function change_url_get_ipinfo($view)
    {
        $url = '//' . $_SERVER['HTTP_HOST'] . '/api/getLocation.me';
        $view = str_replace('$.get("https://ipinfo.io", function() {}, "jsonp").', '$.get("'.$url.'", function() {}, "json").', $view);
        $view = str_replace('$.get(\'https://ipinfo.io?\', function() {}, "jsonp")', '$.get(\''.$url.'\', function() {}, "json")', $view);
        return $view;
    }

    public function check_form($view)
    {
        $form = explode("<form", $view);
        return isset($form[1]);
    }

    public function add_after_submit_script($view)
    {
        $check = explode("<form", $view);
        if (!isset($check[1])) {
            return $view;
        }

        $content = '<style> body.unavailable { pointer-events:none; opacity:0.5; } </style>';
        $view = str_replace("</head", $content."\n"."</head", $view);
        return $view;
    }

    public function add_utm_to_links($view)
    {
        $getstr = '';
        if (!empty($_GET)) {
            $getstr = http_build_query($_GET);
        }
        $getstr = (!empty($getstr)) ? "?{$getstr}" : false;
        $view = str_replace('<a href="/"', '<a href="/'.$getstr.'"', $view);
        $view = str_replace('<a href=\'/\'', '<a href=\'/'.$getstr.'\'', $view);

        return $view;
    }

    public function get_ref_field($view)
    {
        $ref = $this->get_ref();
        $input_str = "\n\t<input type=\"hidden\" name=\"_ref\" value=\"{$ref}\">";
        preg_match_all("(<form[^<>]+>)", $view, $out);
        
        if (isset($out[0])) {
            $out[0] = array_unique($out[0]);
            foreach ($out[0] as $form) {
                $view = str_replace($form, "{$form}\n{$input_str}", $view);
            }
        }
        return $view;
    }

    public function check_utm($view)
    {
        $a = (!empty($_GET)) ? '?'.http_build_query($_GET) : false;
        
        if (empty($a)) {
            return $view;
        }
        
        $find = preg_match_all('/action=["|\']([\s\S]+?)["|\']/', $view, $forms);
        
        if ($find) {
            foreach ($forms[0] as $id => $form) {
                $to = $forms[1][$id].$a;
                $from = $forms[1][$id];
                $check = explode($a, $from);
                if (!isset($check[1])) {
                    $rep = str_replace($from, $to, $form);
                    $view = str_replace($form, $rep, $view);
                }
            }
        }
        return $view;
    }

    public function check_errors($view)
    {
        if (!$this->have_forms($view)) {
            return $view;
        }

        $q = '';
        if (isset($_SESSION['error'])) {
            $q = '<script>document.addEventListener("DOMContentLoaded", function(){';
            foreach ($_SESSION['error'] as $error) {
                $q .= "alert('{$error}');";
            }
            $q .= '});</script>';
        }
        unset($_SESSION['error']);
        $view = str_replace('</body', $q."\n</body", $view);
        return $view;
    }

    public function have_forms($view)
    {
        $form = explode("<form", $view);
        if (count($form) > 1) {
            return true;
        }
        return false;
    }

    public function isCloakit()
    {
        $settings = $this->settings;
        return (isset($settings['cloakit']) && !empty($settings['cloakit']));
    }

    public function render()
    {
        $vars = $this->variables;
        $requestAr = explode("?", $_SERVER['REQUEST_URI']);
        $rootFolder = get_root_folder();
        
        $fileName = trim($requestAr[0], '\/ ');

        if (!empty($rootFolder)) {
            $fileName = removeFolder($fileName, $rootFolder);
        }
        
        $fileName = (empty($fileName)) ? 'index.php' : $fileName;

        if ($this->isCloakit() && $fileName == 'index.php') {
            $cloak = new \App\Classes\Cloakit($this->settings);
            $fileName = $cloak->connect();
        }

        $fileNamePath = $this->get_file_path($fileName);

        if ($fileNamePath) {
            extract($vars);
            ob_start();
            require_once($fileNamePath);
            $content = ob_get_contents();
            ob_end_clean();
            return $content;
        } else {
            return $this->error(404);
        }

        return false;
    }

    public function get_settings()
    {
        $settingsPath = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', 'settings.json']);
        if (!file_exists($settingsPath)) {
            $this->error[] = 'file "settings.json" is missing';
            return false;
        }

        $settingsRaw = file_get_contents($settingsPath);

        if (empty($settingsRaw)) {
            $this->error[] = 'file "settings.json" is empty';
            return false;
        }

        $settingsJson = json_decode($settingsRaw, 1);

        if (!is_array($settingsJson)) {
            $this->error[] = 'file "settings.json" is corrupted';
            return false;
        }

        $this->settings = $settingsJson;
        return true;
    }

    public function error($code)
    {
        switch ($code) {
            case '404':
                header("HTTP/1.0 404 Not Found");
            break;
        }
    }

    public function get_file_path($path)
    {
        $dir = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', 'public', $path]);
        $white = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', 'white', $path]);
        if (file_exists($dir)) {
            $files_check = ['index.php', 'index.html', 'index.htm'];
            if ( is_dir($dir) ) {
                foreach ($files_check as $file) {
                    $file = $dir . '/' . $file;
                    if (file_exists($file)) {
                        return $file;
                    }
                };
            }

            return $dir;
        } elseif (file_exists($white)) {
            return $white;
        }
        return false;
    }

    public function utm_settings()
    {
        $get = $_GET;
        if (empty($get)) {
            return false;
        }

        if (isset($get['thx'])) {
            $get['return'] = $get['thx'];
        }

        if (isset($get['pxl'])) {
            $get['facebook'] = $get['pxl'];
        }

        if (isset($get['ynd'])) {
            $get['yandex'] = $get['ynd'];
        }

        foreach ($get as $key => $value) {
            $this->settings[$key] = $value;
        }
    }

    public function get_partner()
    {
        if (isset($_GET['partner'])) {
            $gets = $_GET['partner'];
            if ($gets == 'global' or $gets == 'neogara' or $gets == 'neogara_js') {
                return $gets;
            }
        }
        if (isset($this->settings['partner'])) {
            $partners = $this->settings['partner'];
        } elseif (isset($this->settings['partners'])) {
            $partners = $this->settings['partners'];
        }
        if (!empty($partners)) {
            foreach ($partners as $partner => $value) {
                $all[] = $partner;
                if ($value == 1) {
                    return $partner;
                }
            }
            return $all[0];
        }
        else return 'neogara';
    }

    public function add_neo_js($view)
    {
        $needed = ['group', 'offer', 'pid', 'return'];
        foreach ($this->settings as $key => $value) {
            if (in_array($key, $needed)) {
                $array[$key] = $value;
            }
        }
        
        $query = array_diff($array, ['']);
        $http_query = (!empty($query)) ? '?' . http_build_query($query) : null;
        $link = "https://admin.neogara.com/script/neo_form_js.js{$http_query}";
        $script = "<script src=\"{$link}\"></script>";

        return str_replace('</head>', "{$script}\n</head>", $view);
    }

    public function check_htaccess()
    {
        $settings = $this->settings;
        $path = implode(DIRECTORY_SEPARATOR, [__DIR__, '..', '..', '.htaccess']);
        
        if (!file_exists($path)) {
            $this->error[] = 'file ".htaccess" is missing';
        }
    }

    public function check_public_dir()
    {
        $public = implode( DIRECTORY_SEPARATOR, [__DIR__, '..', '..', 'public']);
        if ( !file_exists($public) ) {
            $this->error[] = '"public" directory is missing';
            return false;
        }
        return true;
    }

    public function get_ref()
    {
        $schema = ( isset($_SERVER['REQUEST_SCHEME']) ) ? ( ($_SERVER['REQUEST_SCHEME'] == 'http') ? 'http' : 'https' ) : 'http';
        return "{$schema}://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    }

    public function get_relink()
    {
        $settings = $this->settings;
        $utm = $_GET;
        $utm = array_diff(
            $this->last_yandex($utm),
        ['']);

        $str = '';

        if (isset($settings['relink'])) {
            $array = $this->parse_settings_relink($settings['relink']);
            if (!empty($utm)) {
                $array['data'] = array_merge(
                        (is_array($array['data'])) ? $array['data'] : [], 
                    $utm);
            }
            if(!empty($array['data'])) {
                $str = '?'.http_build_query($array['data']);
            }
            
            return $array['source'].$str;
        }
        return '#';
    }

    public function parse_settings_relink($string)
    {
        $data = explode("?", $string);
        if (!isset($data[1])) {
            return ['source' => $data[0], 'data' => []];
        }
        parse_str($data[1], $output);
        return ['source' => $data[0], 'data' => $output];
    }

    public function last_yandex($utm)
    {
        $settings = $this->settings;
        if (isset($utm['ynd'])) {
            $ynd = $utm['ynd'];
            unset($utm['ynd']);
        }
        if (isset($utm['yandex'])) {
            $ynd = ($ynd) ? $ynd : $utm['yandex'];
            unset($utm['yandex']);
        }
        $utm['yand'] = (isset($ynd) && !empty($ynd) && $ynd != '') ? $ynd : $settings['yandex'];
        if (empty($utm['yand']) || $utm['yand'] == '') unset($utm['yand']);
        return $utm;
    }

    public function get_metrika_code()
    {
        $settings = $this->settings;
        return $settings['yandex'];
    }

    public function get_utm_form_link()
    {
        $utm = explode("?", $_SERVER["REQUEST_URI"]);
        if (!empty($utm[1])) {
            return "?{$utm[1]}";
        }
        return false;
    }

    public function get_location()
    {
        $app = new GetLocation();
        return $app->get_all();
    }

    public function get_country_english()
    {
        $app = new GetLocation();
        $name = $app->get_country_name();
        return $name["EN"];
    }

    public function get_country_code()
    {
        $app = new GetLocation();
        $name = $app->get_country_name();
        return $name["code"];
    }

    public function check_redirect_session()
    {
        if (isset($_SESSION['redirect'])) {
            $s = $this->get_autologin_settings();
            $link = $_SESSION['redirect'];
            $_SESSION['link_after_thanks'] = $link;
            unset($_SESSION['redirect']);
            if ($s == 0) {
                header("Location:{$link}");
            }
            header( 'refresh: ' . $s . '; url=' . $link );
        }
    }

    public function get_autologin_settings()
    {
        $settings = $this->settings;
        if (isset($settings['autologin'])) {
            $s = intval($settings['autologin']);
            return $s;
        }
        return 3;
    }

    public function replace_braces_to_vars($view)
    {
        $matches = [];
        $re = '/{{(.+)}}/m';
        preg_match_all($re, $view, $matches, PREG_SET_ORDER, 0);
        if (!empty($matches)) {
            foreach ($matches as $match) {
                
                $var = trim($match[1], '\'"\/ ');
                if (isset ($this->variables[$var])) {
                    $view = str_replace($match[0], $this->variables[$var], $view);
                } else {
                    $view = str_replace($match[0], '', $view);
                }
                
            }
        }
        return $view;
    }

    protected function add_intl_tel_input( $view )
    {
        if ( $this->isset_phone_fields( $view ) ) {
            $view = $this->styles_intl_tel_input($view);
            $view = $this->scripts_intl_tel_input($view);
        }
        return $view;
    }

    protected function isset_phone_fields( $raw )
    {
        $re = '/type=[\'"]tel[\'"]/s';
        preg_match_all($re, $raw, $matches, PREG_SET_ORDER, 0);
        return (!empty($matches)); 
    }
    
    protected function styles_intl_tel_input( $raw )
    {
        $css = '<link rel="stylesheet" href="/api/intl-tel-input/styles.min.css">';
        $js = implode("\n", [
            '<script src="/api/intl-tel-input/script.min.js"></script>',
            '<script src="/api/intl-tel-input/mainTop.js"></script>'
        ]);
        $raw = str_replace('</head>', "\n{$css}\n{$js}\n</head>", $raw);
        return $raw;
    }

    protected function scripts_intl_tel_input( $raw )
    {
        $country = $this->get_country_code();
        $raw = $this->add_form_data_ids($raw);
        $re = '/<form.+form>/sU';
        preg_match_all($re, $raw, $forms, PREG_SET_ORDER, 0);
        if (count($forms) > 0) {
            foreach($forms as $form) {
                if ($this->isset_phone_fields($form[0])) {
                    $localFromReg = '/data-local-form=["\'](.+)["\']/sU';
                    preg_match_all($localFromReg, $form[0], $matches, PREG_SET_ORDER, 1);
                    if ($matches[0][1]) {
                        $localFormId = $matches[0][1];
                        $raw = str_replace($form[0], $form[0] . "<script>intlTelInputToForm('{$localFormId}', '{$country}');</script>", $raw);
                    }
                }
            }
        }
        
        $script = "<script>beforeCloseBody();</script>";
        $raw = str_replace('</body', $script . "\n</body", $raw);
        return $raw;
    }

    protected function add_form_data_ids($raw)
    {
        $array = explode("<form", $raw);
        if (isset($array[1])) {
            $strs = [];
            foreach ($array as $strings) {
                $randomId = 'form-'.rand(10000,99999);
                $strs[] = $strings;
                $strs[] = "<form data-local-form=\"{$randomId}\"";
            }
            array_pop($strs);
            $raw = implode("", $strs);
        }
        return $raw;
    }

    protected function print_errors()
    {
        if ( !empty($this->error)) {
            header("Content-type: text/plain; charset=utf-8");
            $message = [$_SERVER['HTTP_HOST']];
            $message[] =  '=== FOUND ERRORS!!! ===';
            $message[] = '';
            $message = array_merge($message, $this->error);
            $message[] = '';
            $message[] = 'You can find the lost files in the "app/root" directory and copy them to the root of the site.';
            $message[] = 'Also the "lead_logs.json" file must have write permissions.';
            echo implode("\n", $message);
            die;
        }
    }

    protected function check_needed_files()
    {
        $this->check_htaccess();
        $this->check_public_dir();
        $this->check_lead_logs();
    }

    protected function check_lead_logs()
    {
        $file = implode( DIRECTORY_SEPARATOR, [__DIR__, '..', '..', 'lead_logs.json'] );
        if ( !file_exists($file) ) {
            $this->error[] = 'file "lead_logs.json" is missing';
        } elseif ( !is_writable($file) ) {
            $this->error[] = 'file "lead_logs.json" is not writable';
        }
    }
}