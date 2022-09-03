<?php
return [
    '' => ['POST', 'Actions@check_token'],
    'settings.json' => ['GET', 'Actions@get_settings_json'],

    'metrika/stats.php' => ['GET', 'Actions@LinkToMetrikaStats'],
    'cloakit/stats.php' => ['GET', 'Actions@LinkToCloakIt'],

    'api/getLocation.me' => ['GET', 'Actions@GetLocation'],
    'api/getLocation' => ['GET', 'Actions@GetLocation'],

    'send.php' => ['POST', 'Actions@SendForm'],

    'api/intl-tel-input/styles.min.css' => ['GET', 'Actions@get_intl_tel_input_file'],
    'api/intl-tel-input/script.min.js'  => ['GET', 'Actions@get_intl_tel_input_file'],
    'api/intl-tel-input/flags.png'      => ['GET', 'Actions@get_intl_tel_input_file'],
    'api/intl-tel-input/flags@2x.png'   => ['GET', 'Actions@get_intl_tel_input_file'],
    'api/intl-tel-input/utils.js'       => ['GET', 'Actions@get_intl_tel_input_file'],
    'api/intl-tel-input/mainTop.js'    => ['GET', 'Actions@get_intl_tel_input_file'],

    'api/rknBan' => ['GET', 'Actions@check_rkn_ban'],
    'robots.txt' => ['GET', 'Actions@check_robots']
];