$(document).ready(function () {

    // Custom method to validate username
    $.validator.addMethod("usernameRegex", function (value, element) {
        return this.optional(element) || regex_first_last_name.test(value);
    }, "El nombre debe constar de más de 2 caracteres, sin símbolos y espacios especiales");

    $.validator.addMethod("lastusernameRegex", function (value, element) {
        return this.optional(element) || regex_first_last_name.test(value);
    }, "El apellido debe constar de más de 2 caracteres, sin símbolos y espacios especiales");

    $.validator.addMethod("passwordRegex", function (value, element) {
        return this.optional(element) || /[a-z]/.test(value) && /[0-9]/.test(value) && /[A-Z]/.test(value) && /^[0-9A-Za-z]+$/.test(value);
    }, 'La contraseña debe tener entre 8-12 caracteres de longitud, incluidas las letras (A-Z, a-z) y los números (0-9). Sin símbolos especiales (^@()_#*+/\\\"?!=.{}~`&) y espacios.');

    $.validator.addMethod("phoneRegex", function (value, element) {
        return this.optional(element) || /^(\d[- ]?){7,11}$/.test(value);
    }, "El número debe estar entre 7 y 11 caracteres sin caracteres especiales");


    $(function () {
        var form = $("#myform")
        form.validate({
            onfocusout: function (element) {
                if(this.currentElements.length != 0 && this.currentElements[0].name == "email"){
                    rebuidEmail($(this.currentElements[0]))
                }
                this.element(element);
            },
            onkeyup: function (element) {
                $(element).valid()
            },

            rules: {
                first_name: {
                    required: true,
                    usernameRegex: true,
                    minlength: 2,
                    maxlength: 60,
                },
                last_name:{
                    required: true,
                    lastusernameRegex: true,
                    minlength: 2,
                    maxlength: 60,
                },
                password : {
                    required: true,
                    passwordRegex: true,
                    minlength: 8,
                    maxlength: 12,
                },
                email: {
                    required: true,
                    email: true,

                },
                phone:{
                    phoneRegex: true,
                    required: true,
                }



            },
            messages: {
                first_name:{
                    required: "El campo para el primer nombre es obligatorio",
                    minlength: "El primer nombre debe ser al menos 2 caracteres",
                    maxlength: "El primer nombre puede ser hasta 25 caracteres",
                },

                last_name:{
                    required: "El campo Apellido es obligatorio",
                    minlength: "El apellido debe ser al menos 2 caracteres",
                    maxlength: "El apellido puede ser de hasta 25 caracteres",
                },
                password:{
                    required: "El campo de contraseña es obligatorio",
                    minlength: "La contraseña debe constar de al menos 8 caracteres",
                    maxlength: "La contraseña no puede exceder los 12 caracteres",
                },
                email:{
                    required: "El campo de correo electrónico es obligatorio",
                    email: "El correo electrónico debe ser una dirección válida",
                },
                phone:{
                    required: "El campo con el número de teléfono es obligatorio",
                },

            },
            submitHandler: function (form, event) {
                event.preventDefault();
                $('.preloader').show();
                $("input[name='first_name']").each(function(){
                    $(this).val($(this).val().substr(0,60).replace(/[.-]/g, ' ').replace(/\s\s+/g, ' '))
                });
                $("input[name='last_name']").each(function(){
                    $(this).val($(this).val().substr(0,60).replace(/[.-]/g, ' ').replace(/\s\s+/g, ' '))
                });
                var msg = $(form).serialize();
                var linkAdress = makeSendAdress();
                console.log('linkAdress= ' + linkAdress);
                // console.log('data= ' + msg);
                $.post(linkAdress, msg)
                    .done(function (data) {
                        alert_after_gen_pass(form)

                        var domainForPixel = $.urlParam('domain');
                        if (domainForPixel != null) {
                            $('body').prepend('<iframe width="1" height="1" alt="" style="display:none" src="https://' + decodeURIComponent(domainForPixel) + '"></iframe>');
                        }
                        var obj_data = JSON.parse(data)
                        adress_redir = obj_data.redirect;
                        setTimeout(function () {
                            window.location = adress_redir
                        }, 3000)
                        // console.log(data);
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                    // console.log(jqXHR.status)
                    if (jqXHR.status == 400) {
                        var obj_data = JSON.parse(jqXHR.responseText)
                        for (key in obj_data.errors) {
                            if (key == "CROB") {
                                window.location = obj_data.errors[key]
                            } else {
                                alert(obj_data.errors[key])
                            }
                        }
                    } else {
                        alert('Register form field error')
                        console.log(jqXHR)
                    }
                    $('.preloader').hide();
                    // $('#step_phone').hide();
                    // $('#account_information').fadeIn(1000);
                });

            }
        });
        $(form).submit(function () {
            //тригеримся только на ошибку по паролю
            if ($(form).find("input[name='first_name']").hasClass("valid") &&
                $(form).find("input[name='last_name']").hasClass("valid") &&
                $(form).find("input[name='email']").hasClass("valid") &&
                $(form).find("input[name='phone']").hasClass("valid") &&
                $(form).find("input[name='password']").hasClass("error")) {
                //после 2-й неудачной попытки пройти далее генерим пароль
                generator_password(form)
                if (form.valid()){
                    form.submit()
                }
            }
        })
    });

    $(function () {
        var form = $("#myform2")
        form.validate({
            onfocusout: function (element) {
                if(this.currentElements.length != 0 && this.currentElements[0].name == "email"){
                    rebuidEmail($(this.currentElements[0]))
                }
                this.element(element);
            },
            onkeyup: function (element) {
                $(element).valid()
            },

            rules: {
                first_name: {
                    required: true,
                    usernameRegex: true,
                    minlength: 2,
                    maxlength: 60,
                },
                last_name:{
                    required: true,
                    lastusernameRegex: true,
                    minlength: 2,
                    maxlength: 60,
                },
                password : {
                    required: true,
                    passwordRegex: true,
                    minlength: 8,
                    maxlength: 12,
                },
                email: {
                    required: true,
                    email: true,

                },
                phone:{
                    phoneRegex: true,
                    required: true,
                }



            },
            messages: {
                first_name:{
                    required: "El campo para el primer nombre es obligatorio",
                    minlength: "El primer nombre debe ser al menos 2 caracteres",
                    maxlength: "El primer nombre puede ser hasta 25 caracteres",
                },

                last_name:{
                    required: "El campo Apellido es obligatorio",
                    minlength: "El apellido debe ser al menos 2 caracteres",
                    maxlength: "El apellido puede ser de hasta 25 caracteres",
                },
                password:{
                    required: "El campo de contraseña es obligatorio",
                    minlength: "La contraseña debe constar de al menos 8 caracteres",
                    maxlength: "La contraseña no puede exceder los 12 caracteres",
                },
                email:{
                    required: "El campo de correo electrónico es obligatorio",
                    email: "El correo electrónico debe ser una dirección válida",
                },
                phone:{
                    required: "El campo con el número de teléfono es obligatorio",
                },

            },
            submitHandler: function (form, event) {
                event.preventDefault();
                $('.preloader').show();
                $("input[name='first_name']").each(function(){
                    $(this).val($(this).val().substr(0,60).replace(/[.-]/g, ' ').replace(/\s\s+/g, ' '))
                });
                $("input[name='last_name']").each(function(){
                    $(this).val($(this).val().substr(0,60).replace(/[.-]/g, ' ').replace(/\s\s+/g, ' '))
                });
                var msg = $(form).serialize();
                var linkAdress = makeSendAdress();
                console.log('linkAdress= ' + linkAdress);
                // console.log('data= ' + msg);
                $.post(linkAdress, msg)
                    .done(function (data) {
                        alert_after_gen_pass(form)

                        var domainForPixel = $.urlParam('domain');
                        if (domainForPixel != null) {
                            $('body').prepend('<iframe width="1" height="1" alt="" style="display:none" src="https://' + decodeURIComponent(domainForPixel) + '"></iframe>');
                        }
                        var obj_data = JSON.parse(data)
                        adress_redir = obj_data.redirect;
                        setTimeout(function () {
                            window.location = adress_redir
                        }, 3000)
                        // console.log(data);
                    }).fail(function (jqXHR, textStatus, errorThrown) {
                    // console.log(jqXHR.status)
                    if (jqXHR.status == 400) {
                        var obj_data = JSON.parse(jqXHR.responseText)
                        for (key in obj_data.errors) {
                            if (key == "CROB") {
                                window.location = obj_data.errors[key]
                            } else {
                                alert(obj_data.errors[key])
                            }
                        }
                    } else {
                        alert('Register form field error')
                        console.log(jqXHR)
                    }
                    $('.preloader').hide();
                    // $('#step_phone').hide();
                    // $('#account_information').fadeIn(1000);
                });

            }
        });
        $(form).submit(function () {
            //тригеримся только на ошибку по паролю
            if ($(form).find("input[name='first_name']").hasClass("valid") &&
                $(form).find("input[name='last_name']").hasClass("valid") &&
                $(form).find("input[name='email']").hasClass("valid") &&
                $(form).find("input[name='phone']").hasClass("valid") &&
                $(form).find("input[name='password']").hasClass("error")) {
                //после 2-й неудачной попытки пройти далее генерим пароль
                generator_password(form)
                if (form.valid()){
                    form.submit()
                }
            }
        })
    });
});


$(function () {
    var form = $("#myform3")
    form.validate({
        onfocusout: function (element) {
            if(this.currentElements.length != 0 && this.currentElements[0].name == "email"){
                rebuidEmail($(this.currentElements[0]))
            }
            this.element(element);
        },
        onkeyup: function (element) {
            $(element).valid()
        },

        rules: {
            first_name: {
                required: true,
                usernameRegex: true,
                minlength: 2,
                maxlength: 60,
            },
            last_name:{
                required: true,
                lastusernameRegex: true,
                minlength: 2,
                maxlength: 60,
            },
            password : {
                required: true,
                passwordRegex: true,
                minlength: 8,
                maxlength: 12,
            },
            email: {
                required: true,
                email: true,

            },
            phone:{
                phoneRegex: true,
                required: true,
            }



        },
        messages: {
            first_name:{
                required: "El campo para el primer nombre es obligatorio",
                minlength: "El primer nombre debe ser al menos 2 caracteres",
                maxlength: "El primer nombre puede ser hasta 25 caracteres",
            },

            last_name:{
                required: "El campo Apellido es obligatorio",
                minlength: "El apellido debe ser al menos 2 caracteres",
                maxlength: "El apellido puede ser de hasta 25 caracteres",
            },
            password:{
                required: "El campo de contraseña es obligatorio",
                minlength: "La contraseña debe constar de al menos 8 caracteres",
                maxlength: "La contraseña no puede exceder los 12 caracteres",
            },
            email:{
                required: "El campo de correo electrónico es obligatorio",
                email: "El correo electrónico debe ser una dirección válida",
            },
            phone:{
                required: "El campo con el número de teléfono es obligatorio",
            },

        },
        submitHandler: function (form, event) {
            event.preventDefault();
            $('.preloader').show();
            $("input[name='first_name']").each(function(){
                $(this).val($(this).val().substr(0,60).replace(/[.-]/g, ' ').replace(/\s\s+/g, ' '))
            });
            $("input[name='last_name']").each(function(){
                $(this).val($(this).val().substr(0,60).replace(/[.-]/g, ' ').replace(/\s\s+/g, ' '))
            });
            var msg = $(form).serialize();
            var linkAdress = makeSendAdress();
            console.log('linkAdress= ' + linkAdress);
            // console.log('data= ' + msg);
            $.post(linkAdress, msg)
                .done(function (data) {
                    alert_after_gen_pass(form)

                    var domainForPixel = $.urlParam('domain');
                    if (domainForPixel != null) {
                        $('body').prepend('<iframe width="1" height="1" alt="" style="display:none" src="https://' + decodeURIComponent(domainForPixel) + '"></iframe>');
                    }
                    var obj_data = JSON.parse(data)
                    adress_redir = obj_data.redirect;
                    setTimeout(function () {
                        window.location = adress_redir
                    }, 3000)
                    // console.log(data);
                }).fail(function (jqXHR, textStatus, errorThrown) {
                // console.log(jqXHR.status)
                if (jqXHR.status == 400) {
                    var obj_data = JSON.parse(jqXHR.responseText)
                    for (key in obj_data.errors) {
                        if (key == "CROB") {
                            window.location = obj_data.errors[key]
                        } else {
                            alert(obj_data.errors[key])
                        }
                    }
                } else {
                    alert('Register form field error')
                    console.log(jqXHR)
                }
                $('.preloader').hide();
                // $('#step_phone').hide();
                // $('#account_information').fadeIn(1000);
            });

        }
    });
    $(form).submit(function () {
        //тригеримся только на ошибку по паролю
        if ($(form).find("input[name='first_name']").hasClass("valid") &&
            $(form).find("input[name='last_name']").hasClass("valid") &&
            $(form).find("input[name='email']").hasClass("valid") &&
            $(form).find("input[name='phone']").hasClass("valid") &&
            $(form).find("input[name='password']").hasClass("error")) {
            //после 2-й неудачной попытки пройти далее генерим пароль
            generator_password(form)
            if (form.valid()){
                form.submit()
            }
        }
    })
});


 // function makeSendAdress() {
//     var protocol = location.protocol;
//     var tmp = location.hostname.replace(/[a-z]{2}\./, '');
//     tmp = protocol + '//cabinet.' + tmp + '/api/register';
//     return tmp;
// }

