<?php
/**
 * DMP API Integration Class
 */
class DmpAPI
{
    const REMOTE_URL = 'https://api.smart-adm.site/statistics/send/';

    const DEFAULT_SUCCESS_MESSAGE      = 'Successfully Saved';
    const DEFAULT_ERROR_MESSAGE        = 'Unknown Error';
    const DEFAULT_REMOTE_ERROR_MESSAGE = 'Unknown Remote Server Error';

    const CONFIG_FILE_PATH = __DIR__.'/../config.json';

    const INPUT_LOG_FILE_PATH  = __DIR__.'/../logs/input.json';
    const OUTPUT_LOG_FILE_PATH = __DIR__.'/../logs/output.json';

    private $_credentials = null;

    private $_userData = array();

    private $_isDebugMode = false;

    public function __construct($isDebugMode = false)
    {
        $isDebugMode = (bool) $isDebugMode;
        $this->_initDebug($isDebugMode);

        $this->_initLogs();

        $this->_setCredentials();
        $this->_setUserData();
    }

    public function send()
    {
        try {
            $requestOptions  = $this->_getRequestOptions();

            $curl = curl_init();
            curl_setopt_array($curl, $requestOptions);

            $response       = (string) curl_exec($curl);
            $curlError      = (string) curl_error($curl);
            $httpStatusCode = (int)    curl_getinfo($curl, CURLINFO_HTTP_CODE);

            curl_close($curl);

            if (!empty($curlError)) {
                throw new Exception($curlError);
            }

            if ($httpStatusCode != 200) {
                $httpStatusCode = (string) $httpStatusCode;

                $errorMessage = 'Remote Server Error'.
                                '. HTTP Response Code #'.$httpStatusCode;

                if (!empty($response)) {
                    $errorMessage = $errorMessage.' Response Message: '.
                                    $response;
                }

                throw new Exception($errorMessage);
            }

            if (empty($response)) {
                throw new Exception(static::DEFAULT_REMOTE_ERROR_MESSAGE);
            }

            $response = json_decode($response, true);

            if (!$this->_isResponseSuccessful($response)) {
                $responseMessage = $this->_getMessageFromResponse($response);
                $this->_responseError($responseMessage);
            }

            $this->_responseSuccess($response);
        } catch (Exception $exp) {
            $this->_responseError($exp->getMessage());
        }
    }

    private function _isResponseSuccessful($response = [])
    {
        $response = (array) $response;
        $status   = false;

        if (
            array_key_exists('content', $response) &&
            !empty($response['content']) &&
            is_array($response['content'])
        ) {
            $response = $response['content'];
        }

        if (array_key_exists('status', $response)) {
            $status = (string) $response['status'];
            $status = mb_convert_case($status, MB_CASE_LOWER);
            $status = preg_replace('#\s+#Umis', '', $status);
        }

        if ($status == 'ok') {
            $status = true;
        }

        return (bool) $status;
    }

    private function _getMessageFromResponse($response = [])
    {
        $response = (array) $response;
        $message  = '';

        if (
            array_key_exists('content', $response) &&
            !empty($response['content']) &&
            is_array($response['content'])
        ) {
            $response = $response['content'];
        }

        if (array_key_exists('messages', $response) && !empty($response)) {
            $response['message'] = implode('. ', $response['messages']);
            $response['message'] = str_replace(
                '. .',
                '.',
                $response['message']
            );

            $response['message'] = str_replace('..', '', $response['message']);

            $response['message'] = preg_replace(
                '#(^\.)|(\.$)#Umis',
                '',
                $response['message']
            );
        }

        if (array_key_exists('message', $response)) {
            $message = (string) $response['message'];

            $message = strip_tags($message);
            $message = htmlspecialchars($message);
            $message = addslashes($message);

            $message = preg_replace('#\s+#Umis', ' ', $message);
            $message = preg_replace('#(^\s)|(\s$)#Umis', '', $message);
        }

        if (empty($message)) {
            $message = static::DEFAULT_REMOTE_ERROR_MESSAGE;
        }

        return (string) $message;
    }

    private function _initDebug($isDebugMode = false)
    {
        $isDebugMode = (bool) $isDebugMode;

        if ($isDebugMode) {
            ini_set('error_reporting', E_ALL);
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);

            return false;
        }

        ini_set('error_reporting', E_ERROR);
        ini_set('display_errors', 0);
        ini_set('display_startup_errors', 0);

        $this->_isDebugMode = $isDebugMode;
    }

    private function _initLogs()
    {
        $this->_clearLogs();

        $userData = json_encode($this->_userData);
        $this->_save2log(static::INPUT_LOG_FILE_PATH, $userData);
    }

    private function _save2log($logName = '', $logData = '')
    {
        $logName = (string) $logName;
        $logData = (string) $logData;

        if (empty($logName)) {
            return false;
        }

        if (file_exists($logName) && is_file($logName)) {
            unlink($logName);
        }

        if (empty($logData)) {
            return false;
        }

        file_put_contents($logName, $logData);

        return true;
    }

    private function _clearLogs()
    {
        if ($this->_isDebugMode) {
            return false;
        }

        if (
            file_exists(static::INPUT_LOG_FILE_PATH) &&
            is_file(static::INPUT_LOG_FILE_PATH)
        ) {
            unlink(static::INPUT_LOG_FILE_PATH);
        }

        if (
            file_exists(static::OUTPUT_LOG_FILE_PATH) &&
            is_file(static::OUTPUT_LOG_FILE_PATH)
        ) {
            unlink(static::OUTPUT_LOG_FILE_PATH);
        }

        return true;
    }

    private function _getRequestOptions()
    {
        $requestValues  = $this->_getPreparedRequestValues();

        return [
            CURLOPT_URL            => static::REMOTE_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => $requestValues,
            CURLOPT_HTTPHEADER     => $this->_getHTTPHeaders()
        ];
    }

    private function _getPreparedRequestValues()
    {
        $values = [
            'name'         => $this->_getName(),
            'first_name'   => $this->_getFirstName(),
            'second_name'  => $this->_getSecondName(),
            'middle_name'  => $this->_getMiddleName(),
            'email'        => $this->_getEmail(),
            'phone'        => $this->_getPhone(),
            'inn_code'     => $this->_getINNCode(),
            'amount'       => $this->_getAmount(),
            'utm_source'   => $this->_getUtm('source'),
            'utm_medium'   => $this->_getUtm('medium'),
            'utm_campaign' => $this->_getUtm('campaign'),
            'utm_term'     => $this->_getUtm('term'),
            'utm_content'  => $this->_getUtm('content'),
            'utm_promo'    => $this->_getUtm('promo'),
            'ip'           => $this->_getIP(),
            'site_path'    => $this->_getSitePath(),
            'access-token' => $this->_credentials['token'],
            'domain'       => $this->_credentials['domain']
        ];

        $values = $this->_removeEmptyValues($values);

        return http_build_query($values);
    }

    private function _removeEmptyValues($values = [])
    {
        $values = (array) $values;

        foreach ($values as $key => $value) {
            if (empty($value)) {
                unset($values[$key]);
            }
        }

        return $values;
    }

    private function _getName()
    {
        $name = $this->_getUserDataParam('name', false);

        if (!empty($name)) {
            return $name;
        }

        $name = (string) $this->_getFirstName();

        $name = $name.' '.$this->_getSecondName();
        $name = $name.' '.$this->_getMiddleName();

        $name = preg_replace('/\s+/su', '', $name);
        $name = preg_replace('/(^\s)|(\s$)/su', '', $name);

        if (empty($name)) {
            $message = 'Value \'name\' Is Missing Or Has Bad Format';
            $this->_responseError($message);
        }

        return $name;
    }

    private function _getFirstName()
    {
        return $this->_getUserDataParam('firstname', false);
    }

    private function _getSecondName()
    {
        return $this->_getUserDataParam('lastname', false);
    }

    private function _getMiddleName()
    {
        return $this->_getUserDataParam('middle_name', false);
    }

    private function _getEmail()
    {
        $email = (string) $this->_getUserDataParam('email', false);

        $email = preg_replace('#\s+#Umis', '', $email);

        if (empty($email)) {
            return $email;
        }

        if (!preg_match('#^(.*?)@(.*?)\.(.*?)$#Umis', $email)) {
            $this->_responseError('Email Value Has Bad Format');
        }

        return $email;
    }

    private function _getPhone()
    {
        $phone = (string) $this->_getUserDataParam('phone_number', true);

        $phone = preg_replace('#([^0-9]+)#Umis', '', $phone);

        if (empty($phone) || strlen($phone) < 5) {
            $this->_responseError('Phone Value Has Bad Format');
        }

        return $phone;
    }

    private function _getINNCode()
    {
        $innCode = (string) $this->_getUserDataParam('inn_code', false);

        $innCode = preg_replace('#([^0-9]+)#Umis', '', $innCode);

        if (empty($innCode) || strlen($innCode) < 5) {
            return null;
        }

        return $innCode;
    }

    private function _getAmount()
    {
        $amount = (string) $this->_getUserDataParam('amount', false);

        $amount = (int) preg_replace('#([^0-9]+)#Umis', '', $amount);

        if ($amount < 1) {
            return null;
        }

        return $amount;
    }

    private function _getUtm($utmParamName = '')
    {
        $utmParamName = (string) $utmParamName;

        if (empty($utmParamName)) {
            return null;
        }

        $utmParamName = 'utm_'.$utmParamName;

        $utmParamValue = $this->_getUserDataParam($utmParamName, false);

        if (empty($utmParamValue)) {
            $utmParamValue = $this->_getUtmParamFromHTTPReferer($utmParamName);
        }

        if (empty($utmParamValue)) {
            return null;
        }

        return $utmParamValue;
    }

    private function _getUtmParamFromHTTPReferer($utmParamName = '')
    {
        $utmParamName = (string) $utmParamName;

        if (empty($utmParamName)) {
            return null;
        }

        if (!array_key_exists('HTTP_REFERER', $_SERVER)) {
            return null;
        }

        $httpQueryString = (string) $_SERVER['HTTP_REFERER'];

        $httpQueryString = preg_replace(
            '#^http(s|)\:\/\/(.*?)$#Umis',
            '$2',
            $httpQueryString
        );

        $httpQueryString = explode('/', $httpQueryString);

        if (empty($httpQueryString)) {
            return null;
        }

        $httpQueryString = end($httpQueryString);
        $httpQueryString = str_replace('?', '&', $httpQueryString);
        $httpQueryString = preg_replace('#\s+#Umis', '', $httpQueryString);

        $httpQueryParams = explode('&', $httpQueryString);
        unset($httpQueryParams[0]);

        foreach ($httpQueryParams as $httpQueryParam) {
            if (preg_match(
                '#^'.$utmParamName.'=(.*?)$#Umis',
                $httpQueryParam
            )) {
                $httpQueryParam = preg_replace(
                    '#^'.$utmParamName.'=(.*?)$#Umis',
                    '$1',
                    $httpQueryParam
                );
                $httpQueryParam = urldecode($httpQueryParam);
                $httpQueryParam = preg_replace(
                    '#\s+#Umis',
                    '',
                    $httpQueryParam
                );

                return (string) $httpQueryParam;
            }
        }

        return null;
    }

    private function _getIP()
    {
        if (array_key_exists('HTTP_CF_CONNECTING_IP', $_SERVER)) {
            return $_SERVER['HTTP_CF_CONNECTING_IP'];
        }

        if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
            return $_SERVER['HTTP_CLIENT_IP'];
        }

        if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        if (array_key_exists('HTTP_X_FORWARDED', $_SERVER)) {
            return $_SERVER['HTTP_X_FORWARDED'];
        }

        if (array_key_exists('HTTP_FORWARDED_FOR', $_SERVER)) {
            return $_SERVER['HTTP_FORWARDED_FOR'];
        }

        if (array_key_exists('HTTP_FORWARDED', $_SERVER)) {
            return $_SERVER['HTTP_FORWARDED'];
        }

        if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
            return $_SERVER['REMOTE_ADDR'];
        }

        return null;
    }

    private function _getSitePath()
    {
        $sitePath = (string) $this->_getUserDataParam('site_path', false);

        $sitePath = preg_replace('#\s+#Umis', '', $sitePath);

        if (empty($sitePath)) {
            $sitePath = $this->_getSitePathFromHTTPReferer();
        }

        if (empty($sitePath)) {
            return '/';
        }

        return $sitePath;
    }

    private function _getSitePathFromHTTPReferer()
    {
        if (!array_key_exists('HTTP_REFERER', $_SERVER)) {
            return null;
        }

        $sitePath = (string) $_SERVER['HTTP_REFERER'];

        $sitePath = preg_replace(
            '#^http(s|)\:\/\/(.*?)$#Umis',
            '$2',
            $sitePath
        );

        $sitePath = explode('/', $sitePath);

        if (empty($sitePath)) {
            return null;
        }

        unset($sitePath[0]);

        $sitePath = '/'.implode('/', $sitePath);

        $sitePath = explode('#', $sitePath)[0];
        $sitePath = explode('&', $sitePath)[0];
        $sitePath = explode('?', $sitePath)[0];

        if (empty($sitePath)) {
            return null;
        }

        return (string) $sitePath;
    }

    private function _getUserDataParam($paramName = '', $isReqired = false)
    {
        $paramName = (string) $paramName;
        $isReqired = (boolean) $isReqired;

        if (empty($paramName)) {
            return null;
        }

        if ($this->_isUserDataParamExists($paramName)) {
            return $this->_userData[$paramName];
        }

        if ($isReqired) {
            $message = sprintf(
                'Value \'%s\' Is Missing Or Has Bad Format',
                $paramName
            );

            $this->_responseError($message);
        }

        return null;
    }

    private function _isUserDataParamExists($paramName = '')
    {
        $paramName = (string) $paramName;

        if (empty($paramName)) {
            return false;
        }

        if (!array_key_exists($paramName, $this->_userData)) {
            return false;
        }

        return !empty($this->_userData[$paramName]);
    }

    private function _setUserData()
    {
        $postData = $this->_escapeInput($_POST);
        $getData  = $this->_escapeInput($_GET);

        $this->_userData = !empty($postData) ? $postData : $getData;

        if (empty($this->_userData)) {
            $this->_responseError('Input Data Is Empty!');
        }
    }

    private function _escapeInput($inputData = [])
    {
        $inputData = (array) $inputData;

        foreach ($inputData as $key => $inputValue) {
            $inputValue = strip_tags($inputValue);
            $inputValue = htmlspecialchars($inputValue);
            $inputValue = addslashes($inputValue);

            $inputValue = preg_replace('#\s+#Umis', ' ', $inputValue);
            $inputValue = preg_replace('#(^\s)|(\s$)#Umis', '', $inputValue);

            $inputData[$key] = $inputValue;
        }

        return $inputData;
    }

    private function _setCredentials()
    {
        if (!$this->_isConfigFileExists()) {
            $this->_responseError('Config File Missing');
        }

        $configJSON = (string) file_get_contents(static::CONFIG_FILE_PATH);
        $configData = (array) json_decode($configJSON, true);

        if (!$this->_isConfigHasValidFormat($configData)) {
            $this->_responseError('Config File Has Bad Format');
        }

        $this->_credentials = $configData;

        $host = 'http://'.$configData['domain'];

        if ($this->_isHTTPSEnabled()) {
            $host = 'https://'.$configData['domain'];
        }

        $this->_credentials['host'] = $host;
    }

    private function _isHTTPSEnabled()
    {
        if (
            array_key_exists('HTTPS', $_SERVER) &&
            !empty($_SERVER['HTTPS']) &&
            $_SERVER['HTTPS'] !== 'off'
        ) {
            return true;
        }

        if (
            array_key_exists('SERVER_PORT', $_SERVER) &&
            intval($_SERVER['SERVER_PORT']) == 443
        ) {
            return true;
        }

        return false;
    }

    private function _isConfigFileExists()
    {
        return file_exists(static::CONFIG_FILE_PATH) &&
               is_file(static::CONFIG_FILE_PATH);
    }

    private function _isConfigHasValidFormat($configData = [])
    {
        $configData = (array) $configData;

        if (empty($configData)) {
            return false;
        }

        if (
            !array_key_exists('domain', $configData) ||
            !array_key_exists('token', $configData)
        ) {
            return false;
        }

        if (empty($configData['domain']) || empty($configData['token'])) {
            return false;
        }

        return true;
    }

    private function _getHTTPHeaders()
    {
        return [
            "Content-Type: application/x-www-form-urlencoded",
            "Access-Token: ".$this->_credentials['token'],
            "Referer: ".$this->_credentials['host'],
            "cache-control: no-cache"
        ];
    }

    private function _responseError($message = '')
    {
        $message = (string) $message;

        if (empty($message)) {
            $message = static::DEFAULT_ERROR_MESSAGE;
        }

        $this->_response(false, $message);
    }

    private function _responseSuccess($response = [])
    {
        $this->_response(true, static::DEFAULT_SUCCESS_MESSAGE, $response);
    }

    private function _response($status = false, $message = '', $response = [])
    {
        $status  = (bool) $status;
        $message = (string) $message;

        if (!$status && empty($message)) {
            $message = static::DEFAULT_ERROR_MESSAGE;
        }

        if (empty($message)) {
            $message = static::DEFAULT_SUCCESS_MESSAGE;
        }
        
        $responseContent = array();
        if (!empty($response['content'])) {
            $responseContent = $response['content'];
        }
        $dataJSON = [
            'status' => $status,
            'data'   => $message,
            'response' => $responseContent
        ];

        $dataJSON = json_encode($dataJSON);

        if ($this->_isDebugMode) {
            $this->_save2log(static::OUTPUT_LOG_FILE_PATH, $dataJSON);
        }

        header('Content-Type: application/json');
        echo $dataJSON;

        exit(0);
    }
}
?>
