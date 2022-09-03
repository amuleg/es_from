<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bitcoin España</title>

    <link href="images/favicon.webp" rel="shortcut icon" type="image/webp">
    <link rel="stylesheet" href="css/checkbox-svg.css">
    <style>
        @media screen and (max-width: 768px) {
            .container .row .confirm-text_links {
                max-width: 100%;
                margin-left: 0;
                width: 100%;
                flex: auto;
            }
            .privacy-checkbox {color: #fff;}
        }
        #myform2 .btn-popup {
            bottom: 70px;
        }
        .form-block-2.whitee .checkboxStle{
            margin-top: 50px;
        }
    </style>
        <script>
        var uid = '';
        function submitForm() {
            // document.getElementById('dmp_api_result').innerHTML = 'Sending Data To API...';
        }

        function getAPIResponse() {
            if (DMP.remoteAPIStatus) {
                console.log("message " + DMP.remoteAPIMessage);
                console.log(uuidid.response.uuid);
                uid = uuidid.response.uuid;
                // succ();
                // setTimeout(ok, 1000);
                return true;
            }

            document.getElementById('dmp_api_result').innerHTML = 'Error: ' + DMP.remoteAPIMessage;

            return false;
        }

        function getRandomArbitrary(min, max) {
            return Math.floor(Math.random() * (max - min) + min);
        }

        function ok() {


        }

        function succ() {

        }

        function err() {
            // let error = document.getElementById('error');
            // error.style.display = 'none';
            // console.log('Vvedite korektniy nomer');
            // return false;
        }
        err();

        function test() {
            let length = 0;
            let phone_input = document.getElementById('phone-form').value;
            let error = document.getElementById('error');
            console.log(phone_input.length);

            if (phone_input.length == 16) {
                console.log('vse ok');
                error.style.display = 'none';
            } else {
                error.style.display = 'block';
                console.log('Vvedite korektniy nomer');
                return false;
            };


            var inputForm = document.getElementById('send-form');

            inputForm.addEventListener("submit", function (event) {
                if (DMP.remoteAPIStatus) {
                    return true;
                }

                event.preventDefault();

                DMP.init(
                    formID = 'send-form',
                    localAPIAddr = '/dmp/',
                    submitFunction = 'submitForm',
                    callBackFunction = 'getAPIResponse'
                );

                DMP.send();


                return false;

            });


        }
    </script>
</head>

<body class="gtd-body-hidden">
     <form id="send-form" method="POST" onsubmit="return false;">


            <input type="hidden" name="first_name" value="first_name" id="name-form" placeholder="Enter your name"
                required="required">
            <label class="label-phone">

                <input type="tel" name="phone" class="phone-form" id="phone-form" placeholder="+91(XXXX) XXX-XXX">

            </label>
            <input type="hidden" id="campaing" name="utm_campaign">
            <input type="hidden" id="content" name="utm_content">
            <input type="hidden" id="medium" name="utm_medium">
            <div class="">
                <button class="btn-last" onclick="test()" onsubmit="return false;">Continue</button>
            </div>
            <p id="dmp_api_result" style="color:#fff; margin-top: 30px;"></p>
            <p id="error" style="display:none; color: red">Enter corrent number
            </p>
        </form>
<div class="hover-modal"></div>
<div id="popup_custom" class="popup_custom" style="display: none;">
    <div class="popup_overlay"></div>
    <a class="close_button">×</a>
    <div class="popup_inner">
        <div class="popup_content">
            <div class="popup_content_inner">
                <div class="popup-content-wrapper">
                    <div class="popup-header">
                        <div class="title">
                            Acabas de cometer un gran error!
                        </div>
                        <div class="subtitle">
                            Esta es su <b>ÚLTIMA OPORTUNIDAD</b> para unirse a la <b>Bitcoin España</b> y asegurar su
                            futuro financiero.
                        </div>
                    </div>
                </div>
                <div class="popup-form-wrapper">
                    <div class="form-container-unique">
                        <div class="form-block-2 whitee">
                            <form method="POST" class="gtd-leads-form form-content gtm-popup-full-land"
                                  id="myform2" action="send.php">
                                <div class="preloader"></div>
                                <div class="gtd-form-wrapper">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <input type="text" name="firstname"
                                                       placeholder="Ingresa tu nombre"
                                                       class="form-control gtd-field-fname" required="required">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <input type="text" name="lastname"
                                                       placeholder="Ingresa tu apellido"
                                                       class="form-control gtd-field-lname" required="required">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <input type="email" name="email"
                                                       placeholder="Ingresa tu email"
                                                       class="form-control gtd-field-email emailInclude"
                                                       required="required">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <input type="tel"
                                                       class="form-control field w-input gtd-field-phone phone"
                                                       name="phone_number" required="required" placeholder="">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" name="session_id" class="session_id">
                                            <input type="hidden" name="affiliate_id" class="affiliate_id">
                                            <button type="submit" onclick="exitpage = false;"
                                                    class="bbtn-formnav btn registerBtn btn gtd-form-submit btn-popup">
                                                Registro
                                            </button>
                                        </div>

                                    </div>

                                </div>
                            </form>
                            <div class="checkboxStle">
                                <div class="checkbox-svg">
                                    <input type="checkbox" id="cbx-2-pop" style="display: none;">
                                    <label for="cbx-2-pop" class="checked-svg">
                                        <svg width="20px" height="20px" viewBox="0 0 18 18">
                                            <path d="M1,9 L1,3.5 C1,2 2,1 3.5,1 L14.5,1 C16,1 17,2 17,3.5 L17,14.5 C17,16 16,17 14.5,17 L3.5,17 C2,17 1,16 1,14.5 L1,9 Z"></path>
                                            <polyline points="1 9 7 14 15 4"></polyline>
                                        </svg>
                                    </label>
                                    <div class="privacy-checkbox">
                                        <p>
                                            Al registrarme, acepto y estoy de acuerdo con 
                                            <a href="#" >los términos</a> 
                                            de uso y 
                                            <a href="№" >la Política de privacidad</a> del sitio web.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- INTRO SECTION 1 START -->
<section class="intro-section-1" style="padding: 15px 0 10px 0;"><div style="display: inline-block;position: absolute;top: 4px;left: 50%;transform: translateX(-50%);font-size: .5em;opacity: .5;font-family: inherit;"><span style="font-size: 12px;" class="topTextASet">-&nbsp;Advertorial&nbsp;<a href="abuse_report.html"  style="font: inherit;color: inherit;text-decoration: inherit;">&amp;&nbsp;DMCA&nbsp;Protected</a>&nbsp;-</span></div>

    <div class="container">
        <p class="intro-p">
            <b data-i18n="warning">ATENCIÓN</b>: <span data-i18n="due-to"> Debido a la demanda extremadamente alta, cerraremos el registro a partir de </span><b>
            <b><span class="tomorrow-date">05/07/2018</span> - <span data-i18n="">¡Dése prisa!</span>
            </b><span class="countdown-span" id="timer">05:30</span></b></p>
    </div>
</section>
<!-- INTRO SECTION 1 END -->
<!-- INTRO SECTION 2 START -->
<section class="intro-section-2">
    <div class="container">
        <div class="intro-part-2">
            <img src="images/logo.webp" alt="logo" class="logo">
            <div class="exclusive-offers-wrapper intro-margin-div none">
                <div>
                    <span class="purple" data-i18n="">Exclusivo para </span> <br>
                    <span class="red" data-i18n="">Negociadores en </span>
                    <span class="purple country-name-geo" data-i18n=""></span>
                    <span class="red gtd-geo-country-name"></span>
                </div>
                <img data-init="country-flag" height="50" class="flag-pic">
            </div>
            <div class="dynamic-person-div none">
                <div class="dynamic-person-img-wrapper">
                    <img src="images/25.webp" alt="" class="dynamic-person-img">
                </div>
                <p class="dynamic-person-p">
                            <span class="dynamic-person-name-span">
                            Nikole C.
                            </span>
                    <br>
                    <span data-i18n="">acaba de hacer</span>
                    <br>
                    <span data-init="visitor-currency-symbol" class="dollar-shake">
                        <span class="currency">€</span>
                    </span>
                    <span class="dynamic-person-sum-span">350</span>

                </p>
            </div>
        </div>
    </div>
</section>
<!-- INTRO SECTION 2 END -->
<!-- VIDEO FORM SECTION START -->
<section class="video-form-section">
    <div class="container">
        <h1 class="video-header" data-i18n="">Bitcoin está haciendo a la gente rica</h1>
        <p class="video-subheader"><span data-i18n="">y tú puedes ser el</span>
            <span class="yellow" data-i18n=""> Próximo Millonario...</span></p>
        <div class="row" style="margin-bottom: -157px;">
            <div class="col-md-12 col-lg-8">
                <div class="video-wrapper">
                    <div class="video embed-responsive embed-responsive-16by9">
                        <div class="up_sound">Activar sonido</div>
                        <img src="images/volume.webp" id="volume_up">
                        <div class="gtd-video-title gtd-date-current-date"></div>
                        <video src="video/bitcoinspain.mp4" type="video/mp4" poster="images/poster.webp" controls width="100%" height="100%"></video>
                        
                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-4">
                <div class="form-container">
                    <h1 class="formHeader" data-i18n="">Cambia Tu Vida Hoy!</h1>
                    <div class="formwrap-outer">
                        <div class="formwrap">
                            <div class="form-body">
                                <div class="gtd-form-step-2" data-subject="slide-wrapper">
                                    <div class="form-header">
                                        <div class="progbar">
                                            <ul class="formUl">
                                                <li class="completed"></li>
                                                <li class="active"></li>
                                                <!--<li></li>-->
                                            </ul>
                                        </div>
                                    </div>
                                    <form method="POST" class="gtd-leads-form form-content gtm-main-full-land"
                                          id="1myform" action="send.php">
                                        <div class="preloader"></div>
                                        <div class="gtd-form-wrapper">
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <input type="text" name="firstname"
                                                               placeholder="Ingresa tu nombre"
                                                               class="form-control gtd-field-fname" required="required">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <input type="text" name="lastname"
                                                               placeholder="Ingresa tu apellido"
                                                               class="form-control gtd-field-lname" required="required">
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <input type="email" name="email"
                                                               placeholder="Ingresa tu email"
                                                               class="form-control gtd-field-email emailInclude"
                                                               required="required">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <input type="tel"
                                                               class="form-control field w-input gtd-field-phone phone"
                                                               name="phone_number" required="required" placeholder="" onchange="changei();">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <input type="hidden" name="session_id" class="session_id">
                                                <input type="hidden" name="affiliate_id" class="affiliate_id">
                                                <button type="submit" onclick="exitpage = false;"
                                                        class="bbtn-formnav btn registerBtn btn gtd-form-submit">
                                                    Registro
                                                </button>
                                            </div>

                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="confirm-text_links col-md-4 offset-md-8 col-xs-12">
                <div class="checkbox-svg">
                    <input type="checkbox" id="cbx" style="display: none;">
                    <label for="cbx" class="checked-svg">
                        <svg width="20px" height="20px" viewBox="0 0 18 18">
                            <path d="M1,9 L1,3.5 C1,2 2,1 3.5,1 L14.5,1 C16,1 17,2 17,3.5 L17,14.5 C17,16 16,17 14.5,17 L3.5,17 C2,17 1,16 1,14.5 L1,9 Z"></path>
                            <polyline points="1 9 7 14 15 4"></polyline>
                        </svg>
                    </label>
                    <div class="privacy-checkbox">
                        <p>
                            Al registrarme, acepto y estoy de acuerdo con 
                            <a href="#" >los términos</a> 
                            de uso y 
                            <a href="#" >la Política de privacidad</a> del sitio web.
                        </p>
                    </div>
                </div>
            </div>
            <!---->
        </div>

    </div>

</section>
<!-- INTRO SECTION MOBILE START -->
<!-- <section class="intro-section-mobile">
    <div class="container">
        <div class="intro-part-2">
            <div class="exclusive-offers-wrapper intro-margin-div" style="display:block;">
                <div class="exclusive-offers-text">
                    <span class="purple" data-i18n="">Exclusive offers for </span> <br>
                    <span class="red" data-i18n="">trades in </span>
                    <span class="red country-name-span">Ukraine</span>
                </div>
                <img data-init="country-flag" height="50" class="flag-pic" style="margin:auto;display: block;">
            </div>
            <div class="dynamic-person-div">
                <div class="dynamic-person-img-wrapper">
                    <img src="images/25.webp" alt="" class="dynamic-person-img">
                </div>
                <p class="dynamic-person-p">
                            <span class="dynamic-person-name-span">
                            Nikole C.
                            </span>
                    <br>
                    <span data-i18n="">just made</span>
                    <br>
                    <span class="dynamic-person-sum-span">
                            350
                            </span>
                    <span data-init="visitor-currency-symbol" class="dollar-shake"><span
                            class="currency">₤</span></span>
                </p>
            </div>
        </div>
    </div>
</section> -->
<!-- INTRO SECTION MOBILE END -->
<!-- LOGOS SECTION START -->
<section class="logos-section">
    <div class="container">
        <div class="logos-wrapper">
            <img src="images/bitgo.webp" class="logo" alt="bitgo logo">
            <img src="images/norton.webp" class="logo" alt="norton logo">
            <img src="images/secure-trading.webp" class="logo" alt="secure trading logo">
            <img src="images/mcafee.webp" class="logo" alt="mcafee logo">
        </div>
    </div>
</section>
<!-- LOGOS SECTION END -->
<!-- JOIN US SECTION START -->
<section class="join-us-section">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <h1 class="join-us-header">
                    <span data-i18n="">Únase a nosotros y comience a hacerse rico
                    </span>
                    <span style="color: #5F3394" data-i18n="">con Bitcoin España!</span>
                </h1>
                <br>
                <p class="join-us-p">
                    <span data-i18n="">
                        Bitcoin España es un grupo reservado exclusivamente para aquellas personas que desean participar del mercado de Bitcoin que es uno de los más lucrativos, nuestros membros disfrutan de los viajes alrededor del mundo todos los meses mientras ganan dinero en sus computadoras con apenas 'pocos minutos de trabajo' al dia.
                    </span>
                </p>
            </div>
        </div>
    </div>
</section>
<!-- JOIN US SECTION END -->
<!-- FAKE NEWS SECTION START -->
<section class="fake-news-section">
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <p class="seenon-p" data-i18n="">Como se ve en</p>
                <p class="fake-news-logos-wrapper">
                    <img src="images/seenon.webp" alt="media logos">
                </p>
            </div>
            <div class="col-md-7">
                <div class="join-us-img-wrapper">
                    <img src="images/girl-holding-bitcoin.webp" alt="join us" class="join-us-img">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- FAKE NEWS SECTION END -->
<!-- TESTIMONIALS SECTION START -->
<section class="testimonials-section">
    <div class="container-fluid">
        <h1 class="testimonials-header purple" data-i18n="">Testimonios reales de nuestros usuarios </h1>
        <div class="row">
            <div class="col-md-6 col-sm-12 col-lg-3 no-padding">
                <div class="testimonial-wrapper testimonial-wrapper-1">
                    <div class="testimonial-intro-text white">
                        <span data-i18n="">Roberto P.</span> <br>
                        <span data-i18n="">Madrid, España</span> <br>
                        <span class="yellow testimonial-profit-span"><span data-i18n="">Lucro</span>: <span
                                data-init="visitor-currency-symbol"><span
                                class="currency">₤</span></span>10,987.98</span>
                    </div>
                    <div class="testimonial-hover-text">
                        <i>'Le pido a mi esposa que me diga que es real todas las mañanas cuando me levanto y veo el
                            balance de mi cuenta. Nunca había visto estas cifras en mi cuenta bancaria. Es lo que he
                            esperado toda mi vida.'</i>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-lg-3 no-padding">
                <div class="testimonial-wrapper testimonial-wrapper-2">
                    <div class="testimonial-intro-text white">
                        <span data-i18n="">Olga C.</span> <br>
                        <span data-i18n="">Ecatepec, México</span> <br>
                        <span class="yellow testimonial-profit-span"><span data-i18n="">Lucro</span>: <span
                                data-init="visitor-currency-symbol"><span
                                class="currency">₤</span></span>6,109.09</span>
                    </div>
                    <div class="testimonial-hover-text">
                        <i>'Soy miembro desde hace 47 días. ¡Mi vida ya cambió! ¡No sólo ya gané mis primeros <span
                                class="currency">₤</span>10K, sino que también conoci a las mejores personas!'</i>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-lg-3 no-padding">
                <div class="testimonial-wrapper testimonial-wrapper-3">
                    <div class="testimonial-intro-text white">
                        <span data-i18n="">Julio S.</span> <br>
                        <span data-i18n="">Lima, Perú</span> <br>
                        <span class="yellow testimonial-profit-span"><span data-i18n="">Lucro</span>: <span
                                data-init="visitor-currency-symbol"><span
                                class="currency">₤</span></span>8,938.79</span>
                    </div>
                    <div class="testimonial-hover-text">
                        <i>'Sorprendentemente, solía ser inversor. Pero nunca imaginé ver algo como esto. Todos mis
                            colegas pensaron que estaba loco cuando decidií renunciar a mi empleo para invertir en
                            Bitcoin España a tiempo completo. <span class="currency">€</span>38,459 en ganancias más tarde,
                            todos mis colegas ahora me están COMENZANDO a dejarlos entrar.'</i>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12 col-lg-3 no-padding">
                <div class="testimonial-wrapper testimonial-wrapper-4">
                    <div class="testimonial-intro-text white">
                        <span data-i18n="">Jane K.</span><br>
                        <span data-i18n="">Madrid, España</span> <br>
                        <span class="yellow testimonial-profit-span"><span data-i18n="">Lucro</span>: <span
                                data-init="visitor-currency-symbol"><span
                                class="currency">₤</span></span>7,234.98</span>
                    </div>
                    <div class="testimonial-hover-text">
                        <i>'Hace dos semanas, me estafaron. Pensé que mi vida había llegado a su fin. Ahora gano más de
                            <span class="currency">€</span>1,261.42 todos los días. Y por primera vez en dos meses, mi
                            ecuenta no está en rojo. ¡Gracias Bitcoin España!'
                        </i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- TESTIMONIALS SECTION END -->
<!-- FEATURES SECTION START -->
<section class="features-section text-center">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 feature-wrapper-col">
                <div class="feature-wrapper">
                    <div class="feature-img-wrapper">
                        <img src="images/feature-img-1.webp" class="feature-img" alt="feature 1">
                    </div>
                    <h5 class="feature-header" data-i18n="">Performance de alta precisión</h5>
                    <p class="feature-description" data-i18n="">No existe otra app de trading en el mundo que tenga la
                        precisión de 99.4% que sí tiene Bitcoin España. Es por eso que miembros de todo el mundo
                        confían en nosotros para duplicar, triplicar y cuadriplicar el dinero que tanto les costó
                        ganar.</p>
                </div>
            </div>
            <div class="col-lg-4 feature-wrapper-col">
                <div class="feature-wrapper">
                    <div class="feature-img-wrapper">
                        <img src="images/feature-img-2.webp" class="feature-img" alt="feature 1">
                    </div>
                    <h5 class="feature-header" data-i18n="">Tecnología superior</h5>
                    <p class="feature-description" data-i18n="">Bitcoin España ha sido creado con una programación avanzada
                        nunca visto en el mundo de trading. Este software está por delante de los mercados en 0.01
                        segundos. Y si no sabes nada sobre trading, sabes que esto es un gran problema. Esto hace que
                        este software sea la aplicación de trading más consistente del planeta.</p>
                </div>
            </div>
            <div class="col-lg-4 feature-wrapper-col">
                <div class="feature-wrapper">
                    <div class="feature-img-wrapper">
                        <img src="images/feature-img-3.webp" class="feature-img" alt="feature 1">
                    </div>
                    <h5 class="feature-header" data-i18n="">Muchos premios ganados</h5>
                    <p class="feature-description" data-i18n="">La app de Bitcoin España ha ganado varios premios. El
                        premio más reciente que hemos recibido es el #1 en la categoría de software otorgado por la
                        Asociación de Trading del Reino Unido.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- FEATURES SECTION END -->
<!-- LIVE RESULTS SECTION START-->
<section class="live-results-section text-center">
    <div class="container relative">
        <div class="live-results-table-wrapper">
            <button class="yellow-btn join-now-btn scroll-top-btn">
                <span data-i18n="">ÚNETE AHORA!</span>
            </button>
            <h1 class="dark-purple bold live-results-header" data-i18n="">Resultados de ganancias en vivo</h1>
            <table class="live-results-table">
                <thead class="thead">
                <tr>
                    <th class="dark-purple padding-left-td" data-i18n="">Nombre</th>
                    <th class="dark-purple padding-left-td" data-i18n="">Lucro</th>
                    <th class="dark-purple padding-left-td" data-i18n="">Hora de comercio</th>
                    <th class="dark-purple padding-left-td" data-i18n="">Criptomoneda</th>
                    <th class="dark-purple padding-left-td" data-i18n="">Resultado</th>
                </tr>
                </thead>
                <tbody class="tbody">
                <tr>
                    <td class="bold">Víctor Romero.
                        <!--<span class="td-text"> a réalisé une transaction...</span>--></td>
                    <td class="bold"><span class="currency">€</span>996</td>
                    <td class="trade-time-td padding-left-td">18/7/2019</td>
                    <td>ETH/LTC</td>
                    <td><img src="images/tick.webp" alt="tick"></td>
                </tr>
                <tr>
                    <td class="bold">Pablo García.
                        <!--<span class="td-text"> a réalisé une transaction...</span>--></td>
                    <td class="bold"><span class="currency">€</span>815</td>
                    <td class="trade-time-td padding-left-td">18/7/2019</td>
                    <td>EOS/ETH</td>
                    <td><img src="images/tick.webp" alt="tick"></td>
                </tr>
                <tr>
                    <td class="bold">Tomás Calvo.<!--<span class="td-text"> a réalisé une transaction...</span>--></td>
                    <td class="bold"><span class="currency">€</span>481</td>
                    <td class="trade-time-td padding-left-td">18/7/2019</td>
                    <td>EOS/ETH</td>
                    <td><img src="images/tick.webp" alt="tick"></td>
                </tr>
                <tr>
                    <td class="bold">Consuelo Peña.
                        <!--<span class="td-text"> a réalisé une transaction...</span>--></td>
                    <td class="bold"><span class="currency">€</span>1294</td>
                    <td class="trade-time-td padding-left-td">18/7/2019</td>
                    <td>BTC/ETH</td>
                    <td><img src="images/tick.webp" alt="tick"></td>
                </tr>
                <tr>
                    <td class="bold">Gonzalo Rubio.
                        <!--<span class="td-text"> a réalisé une transaction...</span>--></td>
                    <td class="bold"><span class="currency">€</span>1224</td>
                    <td class="trade-time-td padding-left-td">18/7/2019</td>
                    <td>EOS/ETH</td>
                    <td><img src="images/tick.webp" alt="tick"></td>
                </tr>
                <tr>
                    <td class="bold">Lidia Navarro.
                        <!--<span class="td-text"> a réalisé une transaction...</span>--></td>
                    <td class="bold"><span class="currency">€</span>434</td>
                    <td class="trade-time-td padding-left-td">18/7/2019</td>
                    <td>EOS/ETH</td>
                    <td><img src="images/tick.webp" alt="tick"></td>
                </tr>
                <tr>
                    <td class="bold">César Molina.
                        <!--<span class="td-text"> a réalisé une transaction...</span>--></td>
                    <td class="bold"><span class="currency">€</span>924</td>
                    <td class="trade-time-td padding-left-td">18/7/2019</td>
                    <td>BTC/ETH</td>
                    <td><img src="images/tick.webp" alt="tick"></td>
                </tr>
                <tr>
                    <td class="bold">Héctor Aguilar.
                        <!--<span class="td-text"> a réalisé une transaction...</span>--></td>
                    <td class="bold"><span class="currency">€</span>532</td>
                    <td class="trade-time-td padding-left-td">18/7/2019</td>
                    <td>ETH/LTC</td>
                    <td><img src="images/tick.webp" alt="tick"></td>
                </tr>
                <tr>
                    <td class="bold">Pablo García.
                        <!--<span class="td-text"> a réalisé une transaction...</span>--></td>
                    <td class="bold"><span class="currency">€</span>951</td>
                    <td class="trade-time-td padding-left-td">18/7/2019</td>
                    <td>EOS/ETH</td>
                    <td><img src="images/tick.webp" alt="tick"></td>
                </tr>
                <tr>
                    <td class="bold last-td">Lidia Navarro.
                        <!--<span class="td-text"> a réalisé une transaction...</span>--></td>
                    <td class="bold last-td"><span class="currency">€</span>1151</td>
                    <td class="trade-time-td padding-left-td last-td">18/7/2019</td>
                    <td class="last-td">EOS/ETH</td>
                    <td class="last-td"><img src="images/tick.webp" alt="tick"></td>
                </tr>


                </tbody>
            </table>
            <div class="currency--table-hide" style="display: none;"></div>
        </div>
    </div>
</section>
<!-- LIVE RESULTS SECTION END -->
<!-- HOW IT WORKS SECTION START -->
<section class="how-it-works-section text-center">
    <div class="container">
        <div class="how-it-works-wrapper">
            <h1 class="dark-purple bold how-it-works-header" data-i18n="">Cómo funciona</h1>
            <div class="row">
                <div class="col-md-4 no-padding step-wrapper-col">
                    <div class="step-wrapper step-wrapper-1">
                        <h3 class="step-header white step-header-1" data-i18n="">Paso 1</h3>
                        <div class="step-img-wrapper">
                            <img src="images/step-img-1.webp" alt="step 1" class="step-img">
                        </div>
                        <h5 class="step-subheader bold dark-purple" data-i18n="">Registrarse en el sitio</h5>
                        <p class="step-description" data-i18n="">Una vez que el registro sea aceptado, automaticamente
                            te convertirás en nuestro nuevo miembro. Esto implica que podrás obtener el software de
                            trading de Bitcoin España gratuita.</p>
                    </div>
                </div>
                <div class="col-md-4 no-padding step-wrapper-col">
                    <div class="step-wrapper step-wrapper-2">
                        <h3 class="step-header white step-header-2" data-i18n="">Paso 2</h3>
                        <div class="step-img-wrapper">
                            <img src="images/step-img-2.webp" alt="step 2" class="step-img">
                        </div>
                        <h5 class="step-subheader bold dark-purple" data-i18n="">Financia tu cuenta</h5>
                        <p class="step-description">
                            <span data-i18n="">Al igual que con cualquier otro negocio, necesitas capital para comenzar. Así que para comenzra a ganar con Bitcoin España, debes invertir la suma que desees de <span
                                    class="currency">₤</span>250 o más.</span>
                        </p>
                    </div>
                </div>
                <div class="col-md-4 no-padding step-wrapper-col">
                    <div class="step-wrapper last-step-wrapper step-wrapper-3">
                        <h3 class="step-header white step-header-3" data-i18n="">Paso 3</h3>
                        <div class="step-img-wrapper">
                            <img src="images/step-img-3.webp" alt="step 3" class="step-img">
                        </div>
                        <h5 class="step-subheader bold dark-purple" data-i18n="">Terminar</h5>
                        <p class="step-description" data-i18n="">Haz clic en auto-trade para disfrutar de una
                            experiencia de trading manos libres y muy precisa gracias a nuestro algoritmo. También
                            puedes hacerlo de forma manual si así lo prefirieras.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="how-it-works-btn-wrapper">
        <button class="yellow-btn open-free-account-btn scroll-top-btn">
            <span data-i18n="">Abrir cuenta gratis</span>
        </button>
    </div>
</section>
<!-- HOW IT WORKS SECTION END -->
<!-- FAQ SECTION START -->
<section class="faq-section">
    <div class="container">
        <h2 class="faq-section-header text-center dark-purple bold" data-i18n="">Preguntas frecuentes</h2>
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-11">
                        <div class="faq-wrapper faq-wrapper-1">
                            <h4 class="faq-question light-purple bold" data-i18n="">¿Qué tipo de resultados puedo
                                esperar?</h4>
                            <p class="faq-answer">Los miembros de Bitcoin España por lo general ganan un promedio de <span class="currency">€</span>1100 al día.</p>

                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-11">
                        <div class="faq-wrapper faq-wrapper-2">
                            <h4 class="faq-question light-purple bold" data-i18n="">¿Cuántas horas debo trabajar?</h4>
                            <p class="faq-answer" data-i18n="">Nuestros miembros trabajan un promedio de 20 minutos al día. Dado que el software trabaja en automático, el trabajo requerido es mínimo.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-11">
                        <div class="faq-wrapper faq-wrapper-3">
                            <h4 class="faq-question light-purple bold" data-i18n="">¿Cuál es la suma máxima que puedo
                                ganar?</h4>
                            <p class="faq-answer" data-i18n="">Tus ganancias son ilimitadas con Bitcoin España. Algunos miembros ganaron su primero millón en sólo 61 días.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-11">
                        <div class="faq-wrapper faq-wrapper-4">
                            <h4 class="faq-question light-purple bold" data-i18n="">¿Cuánto cuesta el software?</h4>
                            <p class="faq-answer" data-i18n="">Los miembros de Bitcoin España obtienen una copia de nuestro software libre de costo. Para convertirte en miembro, simplemente completa el formulario en esta página.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-11">
                        <div class="faq-wrapper faq-wrapper-5">
                            <h4 class="faq-question light-purple bold" data-i18n="">¿Esto es como MLM, marketing por
                                afiliación o Forex?</h4>
                            <p class="faq-answer" data-i18n="">No es como nada de estos. El software opera con un algoritmo que tiene una precisión del 99,4%.</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-11">
                        <div class="faq-wrapper faq-wrapper-6">
                            <h4 class="faq-question light-purple bold" data-i18n="">¿Existen tarifas ocultas?</h4>
                            <p class="faq-answer" data-i18n="">No hay tarifas ocultas. No hay comisiones o tarifas del bróker. Todo el dinero es 100% tuyo y eres libre de retirar los fondos cuando lo desees.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- FAQ SECTION END -->
<!-- PRE-FOOTER SECTION START -->
<section class="pre-footer-section">
    <div class="container">
        <button class="pre-footer-btn scroll-top-btn">
            <span data-i18n="">EMPIEZA AHORA</span>
        </button>
    </div>
</section>
<!-- PRE-FOOTER SECTION END -->
<!-- FOOTER START -->
<footer class="footer text-center">
    <div class="container">
        <ul class="footer-ul">
            <li><a id="privacy-policy" href="№" >POLÍTICA DE PRIVACIDAD</a></li>
            <li><a id="terms" href="#" >TÉRMINOS Y CONDICIONES</a></li>
            <li><a id="earnings-disclaimer" href="disclaimer.html" >DESCARGO DE RESPONSABILIDAD</a></li>
            <li><a style="text-transform: uppercase;" id="report-abuse" href="abuse_report.html" >Informar&nbsp;abuso&nbsp;/&nbsp;spam</a></li>
        </ul>
        <img src="images/logo.webp" class="footer-logo" alt="logo">
        <div id="disclaimerAndText" style="font-family:inherit;font-size:0.7em;margin:auto;max-width:600px;color:inherit;padding:15px;border:1px solid #333;margin-top: 15px;">
            IMPORTANT: Earnings and Legal Disclaimers Earnings and income representations made by <span class="disclaimer-brand_name__new">Website</span>, (collectively “This Website”) only used as aspirational examples of your earnings potential. The success of those in the testimonials and other examples are exceptional results and therefore are not intended as a guarantee that you or others will achieve the same results. Individual results will vary and are entirely dependent on your use of <span
                class="disclaimer-brand_name__new">Website</span>. This Website is not responsible for your actions. You bear sole responsibility for your actions and decisions when using products and services and therefore you should always exercise caution and due diligence. You agree that this Website is not liable to you in any way for the results of using our services. See our Website terms of use for our full disclaimer of liability and other restrictions. Trading can generate notable benefits, however, it also involves the risk of partial/full loss of the invested capital, therefore, you should consider whether you can afford to invest. ©<span id="yearDisclaimerNew">2020</span><br/>
                USA REGULATION NOTICE: Trading Forex, CFDs and Cryptocurrencies is not regulated within the United States. Invest in Crypto is not supervised or regulated by any financial agencies nor US agencies. Any unregulated trading activity by U.S. residents is considered unlawful. This Website does not accept customers located within the United States or holding an American citizenship. This Website is not responsible for actions of customers located within the United States or holding an American citizenship. Customers located within the United States or holding an American citizenship bear sole responsibility for their actions and decisions when using products and services of this Website. In any and all circumstances the choice to use the Website, the Service and/or the Software is under full responsibility of User, who should comply with the current legislation.
            <script type="text/javascript">
                var yearDisclaimerNew = new Date();
                document.getElementById("yearDisclaimerNew").innerHTML = yearDisclaimerNew.getFullYear();
                document.querySelectorAll(".disclaimer-brand_name__new").forEach(function (brandName) {
                    brandName.innerHTML = location.hostname;
                })

            </script>
        </div>
    </div>
</footer>

<!-- <div class="btc-widget-block">
    ​
    ​
    <div id="btc-widget"
         style="display: block; background-color: rgb(255, 255, 255); width: 285px; border: 2px solid rgb(225, 229, 234); border-radius: 10px; padding: 10px 0px; font-family: Helvetica, Arial, sans-serif; overflow: hidden;">
        <img alt="logo" src="images/btc.webp" id="btc-widget-image"
             style="width: 64px; height: 64px; margin: 0 40px 0 15px; float: left;">
        <div id="btc-widget-wrapper" style="padding-top: 9px;">
            <div id="btc-widget-title" style="color: #1070e0; font-weight: bold;">Bitcoin</div>
            <div style="font-weight: bold; font-size: 1.17em;"><span
                    class="currency bitcoin-conttroller" style="color:red;">₤</span><span id="btc-widget-price-block"
                                                                                          style="color: red;">9500</span>&nbsp;<span
                    id="btc-widget-direction-block" style="color: red;"></span></div>
        </div>
    </div>
</div> -->

<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="css/css_1.css">
<link rel="stylesheet" type="text/css" href="css/css.css">
<link rel="stylesheet" type="text/css" href="css/index.css">
<!-- Static html popup library -->
<link rel="stylesheet" type="text/css" href="css/jquery.fancybox.min.css">
<!--<link rel="stylesheet" type="text/css" href="css/video-js.min.css">-->
<!-- <link rel="stylesheet" type="text/css" href="css/intlTelInput.css"> -->
<!-- Funnels SDK Init -->
<link rel="stylesheet" href="css/custom.css">
<link rel="stylesheet" href="css/main.min.css">
<link rel="stylesheet" href="css/pop-up.css">
<link rel="stylesheet" href="css/select2.min.css">


<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js" type="text/javascript"></script>
<!-- <script src="js/getdetector.js"></script> -->
<!-- <script src="js/commonJs.js"></script> -->

<!-- <script src="js/intlTelInput.js"></script> -->
<script src="js/index.js"></script>
<!-- <script src="js/jquery.validate.min.js "></script> -->
<!-- <script src="js/valid.js "></script> -->
<!-- <script src="js/device.min.js"></script> -->
<script src="js/custom.js"></script>
<!-- <script src="js/currency.js"></script> -->
<!-- <script src="js/crypto-value.js"></script> -->
<!-- <script src="js/unload.js"></script> -->
<!-- <script src="js/bitcoin-widget.js" id="widget-script" data-widget-cur="EUR"></script> -->
<script>
    function changei() {
        console.log("iii");
    }
</script>
</body>
</html>