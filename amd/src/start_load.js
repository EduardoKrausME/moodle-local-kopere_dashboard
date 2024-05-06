define([
    "jquery",
    "local_kopere_dashboard/maskedinput", // carrega para aplicar os
    "local_kopere_dashboard/validate",
    "local_kopere_dashboard/iosCheckbox",
], function($, maskedinput, validate, iosCheckbox) {
    return {
        init : function() {

            // iosCheckbox
            $(".ios-checkbox").iosCheckbox();

            // mackInputs()
            $("input.mask_phone").mask("(99) 9999-9999");
            $("input.mask_celphone").mask("(99) 9 9999-9999");
            $("input.mask_cep").mask("99999-999");
            $("input.mask_cpf,input.val_cpf").mask("999.999.999-99");
            $("input.mask_cnpj").mask("99.999.999/9999-99");
            $("input.mask_datahora").mask("99/99/9999 99:99");
            $("input.mask_data").mask("99/99/9999");
            $("input.mask_relatorioData").mask("9999-99");
            $("input.mask_int").keyup(function() {
                var text = $(this).val();
                $(this).val(text.replace(/[^\d]/, ""));
            });
            $("input.mask_float").keyup(function() {
                var text = $(this).val();
                $(this).val(text.replace(/[^\d,]/, ""));
            });
            $("input.mask_valor").keyup(function() {
                var text = $(this).val();
                text = text.replace(/[^\d]/, "");
                text = parseInt(text).toString();
                if (text == "NaN")
                    text = "";
                text = text.substr(0, text.length - 2) + "," + text.substr(text.length - 2, text.length);
                $(this).val(text);
            });

            function loadValidateAll() {
                $("form.validate").validate({
                    invalidHandler : function(e, validator) {
                        var errors = validator.numberOfInvalids();
                        if (errors) {
                            var message = 'você tem ' + errors + ' items obrigatórios.';
                            if (errors == 1) {
                                message = 'você tem um item obrigatório.';
                            }
                            $("div.displayErroForm span").html(message);
                            $("div.displayErroForm").show();
                        } else {
                            $("div.displayErroForm").hide();
                        }
                    }
                });

                $.validator.addMethod("phoneVal", function(value, element) {
                    if ($(element).hasClass('required')) {
                        return value.match(/^\([1-9]{2}\)\ [0-9]{4}\-[0-9]{4}$/);
                    }
                    return true;

                }, "Telefone em formáto inválido!");
                $.validator.classRuleSettings.val_phone = {phoneVal : true};

                $.validator.addMethod("celphoneVal", function(value, element) {
                    if ($(element).hasClass('required')) {
                        return value.match(/^\([1-9]{2}\)\ 9\ [0-9]{4}\-[0-9]{4}$/);
                    }
                    return true;

                }, "Celular em formáto inválido!");
                $.validator.classRuleSettings.val_celphone = {celphoneVal : true};

                $.validator.addMethod("cepVal", function(value, element) {
                    if ($(element).hasClass('required')) {
                        return value.match(/^[0-9]{5}\-[0-9]{3}$/);
                    }
                    return true;
                }, "CEP em formáto inválido! Somente aceito formato 99999-999");
                $.validator.classRuleSettings.val_cep = {cepVal : true};

                $.validator.addMethod("cpfVal", function(value, element) {
                    if ($(element).hasClass('required')) {
                        return value.match(/^[0-9]{3}\.[0-9]{3}\.[0-9]{3}\-[0-9]{2}$/);
                    }
                    return true;
                }, "CPF em formáto inválido!");
                $.validator.classRuleSettings.val_cpf = {cpfVal : true};

                $.validator.addMethod("emailVal", function(value, element) {
                    if ($(element).hasClass('required')) {
                        return value.match(/^.*@.*\..*$/);
                    }
                    return true;
                }, "E-mail em formáto inválido!");
                $.validator.classRuleSettings.val_email = {emailVal : true};

                $.validator.addMethod("passwordVal", function(value, element) {
                    if ($(element).hasClass('required')) {
                        if (value.length < 6) {
                            return false;
                        }
                        if (value == "123456") {
                            return false;
                        }
                    }
                    return true;
                }, "Senha no mínimo 6 caracteres e não pode ser 123456!");
                $.validator.classRuleSettings.val_password = {passwordVal : true};

                $.validator.addMethod("nomeVal", function(value, element) {
                    if ($(element).hasClass('required')) {
                        value = value.replace(/^\s+|\s+$/g, "");
                        if (value.indexOf(' ') > 0) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                    return true;
                }, "Insira seu nome completo!");
                $.validator.classRuleSettings.val_nome = {nomeVal : true};

                $.validator.addMethod("cnpjVal", function(value, element) {
                    if ($(element).hasClass('required')) {
                        return value.match(/^[0-9]{2}\.[0-9]{3}\.[0-9]{3}\/[0-9]{4}\-[0-9]{2}$/);
                    }
                    return true;
                }, "CNPJ em formáto inválido!");
                $.validator.classRuleSettings.val_cnpj = {cnpjVal : true};

                // $.validator.addMethod("caracteres", function(value, element, params) {
                //     if ($(element).hasClass('required')) {
                //         if ($(element).attr('caracteres') == $(element).val().length) {
                //             return true;
                //         } else {
                //             return false;
                //         }
                //     }
                //     return true;
                // }, $.validator.format("Você deve adicionar {0} caracteres!"));
                //$.validator.classRuleSettings.val_caracteres = { caracteresVal:true };

                /*
                 * phone
                 * ^\([1-9][0-9]\)\ [0-9][0-9][0-9][0-9]\-[0-9][0-9][0-9][0-9]$
                 *
                 * cep
                 * ^[0-9][0-9][0-9][0-9][0-9]\-[0-9][0-9][0-9]$
                 *
                 * cpf
                 * ^[0-9][0-9][0-9]\.[0-9][0-9][0-9]\.[0-9][0-9][0-9]\-[0-9][0-9]$
                 *
                 * cnpj
                 * ^[0-9][0-9]\.[0-9][0-9][0-9]\.[0-9][0-9][0-9]\/[0-9][0-9][0-9][0-9]\-[0-9][0-9]$
                 *
                 * datahora
                 * ^[0-9][0-9]\/[0-9][0-9]\/[0-9][0-9][0-9][0-9]\ [0-9][0-9]\:[0-9][0-9]$
                 *
                 * data
                 * ^[0-9][0-9]\/[0-9][0-9]\/[0-9][0-9][0-9][0-9]$
                 */
            };
            loadValidateAll();

        }
    };
});
