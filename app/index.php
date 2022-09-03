<?php
require_once('autoload.php');

use App\Classes\General;
use App\Classes\Router;
use App\Classes\Templates;

check_subdomain_non_ssl();

$site = new General();
starter_timecheck();

$templates = new Templates($site);
$router = new Router();

check_banned_ip();
check_black_white_url();
check_is_domain_not_ip();

// если путь совпадает с тем, который есть в роутере, подключаем его
// иначе ищем файл в папке public
if ($router->covergence()) {
    $router->connect();
}

// Назначаем переменные, которые выводятся на страницах сайта
$site->set('metrika', $templates->get('metrika'));
$site->set('metrika_thanks', $templates->get('metrika_thanks'));
$site->set('metrika_targetclick', $templates->get('metrika_targetclick'));
$site->set('pixel', $templates->get('pixel'));
$site->set('pixel_img', $templates->get('pixel_img'));
$site->set('pixel_img_pageview', $templates->get('pixel_img_pageview'));
$site->set('prokl_link', $site->get_relink());
$site->set('metrika_code', $site->get_metrika_code());
$site->set('metrika_preland', $templates->get('metrika_from_preland'));
$site->set('utm_form', $site->get_utm_form_link());
$site->set('country_english', $site->get_country_english());
$site->set('country_code', $site->get_country_code());

// Если пользователь заполнял форму и его редиректнуло на главную, заполнить поля
if (isset($_SESSION['form_fields'])) {
    $site->inputs_fill = true;
}

$site->run();