const $ = jQuery;

var uri_parc = window.location.href;
var exist_parc = false;
if (uri_parc.indexOf("show_parcelow") !== -1) {
    exist_parc = true;
}

$(document).ready(function ($) {
    //e.preventDefault();
    $("body").append('<div id="boxHTMLModalParcelow"></div>');

    if (exist_parc == true) {
        jQuery.ajax({
            url: script_ajax.ajax_url,
            type: "POST",
            dataType: "JSON",
            data: {
                action: "wcppa_carrega_ajax",
                acao: "INICIAFRONTPARCELOW",
                uripag: window.location.href,
            },
            success: function (data) {
                if (data.status == 1) {
                    $("#boxHTMLModalParcelow").html(data.texto);
                    setTimeout(() => {
                        var ped_cod_parcelow = $("#PARCELOW_COD_PED").val();
                        var acc = $("#PARCELOW_ACC").val();
                        var apihost = $("#PARCELOW_API_HOST").val();

                        var myModal = new bootstrap.Modal(
                            document.getElementById("mod_gatway_parcelow"),
                            {
                                keyboard: false,
                            }
                        );
                        myModal.show();
                        wcppa_getQuestions(
                            "#boxQuestions",
                            ped_cod_parcelow,
                            acc,
                            apihost
                        );
                    }, 1000);
                }
            },
            error: function (request, status, error) {
                console.log(request.responseText);
            },
        });
    }

    function wcppa_getQuestions(target, order_id, acc, apihost) {
        jQuery.ajax({
            url: script_ajax.ajax_url,
            type: "POST",
            dataType: "JSON",
            data: {
                action: "wcppa_carrega_ajax",
                acao: "SHOWQUETIONS",
                order_id: order_id,
                acc: acc,
                apihost: apihost,
            },
            error: function (request, status, error) {
                console.log(request.responseText);
                console.log(error);
            },
            success: function (data) {
                $(target).html("");
                //console.log('QUESTIONS = ');
                //$('#boxMsgOrder').html('&nbsp;');

                var totord = $("#WC_PARCELOW_ORDER_TOTAL_EMDOLAR").val();
                //console.log('total = ' + totord);
                if (parseFloat(totord) >= 3000) {
                    $("#wcppa_boxValidaTotal").css("display", "block");
                    $("#boxQuestions").css("display", "none");
                } else {
                    $("#wcppa_boxValidaTotal").css("display", "none");
                    $("#boxQuestions").css("display", "block");
                }

                $("#boxQuestions").html(data.texto);

                //carrega parcelas
                wcppa_getParcelas("card_parcelas", order_id, acc, apihost);
                //carrega parcelas em dolar
                wcppa_getParcelasDolar(order_id, acc, apihost);               
            },
            beforeSend: function () {
                $(target).html(
                    '<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                );
            },
        });
    }

    function wcppa_getParcelas(target, order_id, acc, apihost) {
        var total = $("#WC_PARCELOW_TOTAL").val();
        jQuery.ajax({
            url: script_ajax.ajax_url,
            type: "POST",
            dataType: "JSON",
            data: {
                action: "wcppa_carrega_ajax",
                acao: "WC_PARCELOW_TOTAL",
                order_id: order_id,
                acc: acc,
                total: total,
                apihost: apihost,
            },
            success: function (data) {
                //bxParcParcelow1
                //bxParcParcelow2

                localStorage.setItem("TOTAL_PED_GERAL", data.total_geral);

                var obj = $.parseJSON(data.json);
                var obj2 = $.parseJSON(data.json);
                var v_mes = 0;
                var v_total = 0;
                var temparcde1a6 = 0;
                var tParcela = 0;
                var html = '<ul class="list-group list-group-flush">';
                for (var i = 0; i <= 5; i++) {
                    if (typeof obj[i] !== "undefined") {
                        temparcde1a6++;
                        tParcela = obj[i].installment;
                        v_mes = obj[i].monthly;
                        v_total = obj[i].total;
                        v_mes = v_mes + "";
                        v_mes = parseInt(v_mes.replace(/[\D]+/g, ""));
                        v_mes = v_mes + "";
                        v_mes = v_mes.replace(/([0-9]{2})$/g, ",$1");

                        if (v_mes.length > 6) {
                            v_mes = v_mes.replace(
                                /([0-9]{3}),([0-9]{2}$)/g,
                                ".$1,$2"
                            );
                        }

                        v_total = v_total + "";
                        v_total = parseInt(v_total.replace(/[\D]+/g, ""));
                        v_total = v_total + "";
                        v_total = v_total.replace(/([0-9]{2})$/g, ",$1");

                        if (v_total.length > 6) {
                            v_total = v_total.replace(
                                /([0-9]{3}),([0-9]{2}$)/g,
                                ".$1,$2"
                            );
                        }
                        html +=
                            '<li class="list-group-item box_cbk_parcela" parcela="' +
                            obj[i].installment +
                            '" valor="' +
                            v_total +
                            '"><strong>' +
                            obj[i].installment +
                            "X de R$ " +
                            v_mes +
                            "</strong><br><span class='box_cbk_parcela_font2'>total de R$ " +
                            v_total +
                            "</span></li>";
                    }
                }
                html += "</ul>";
                if (temparcde1a6 > 0) {
                    $("#bxParcParcelow1").html(html);
                }

                v_mes = 0;
                v_total = 0;
                html = '<ul class="list-group list-group-flush">';
                var temparcde6a12 = 0;
                for (var i = 6; i <= 11; i++) {
                    if (typeof obj2[i] !== "undefined") {
                        temparcde6a12++;
                        v_mes = obj2[i].monthly;
                        v_total = obj2[i].total;
                        tParcela = obj2[i].installment;
                        v_mes = v_mes + "";
                        v_mes = parseInt(v_mes.replace(/[\D]+/g, ""));
                        v_mes = v_mes + "";
                        v_mes = v_mes.replace(/([0-9]{2})$/g, ",$1");

                        if (v_mes.length > 6) {
                            v_mes = v_mes.replace(
                                /([0-9]{3}),([0-9]{2}$)/g,
                                ".$1,$2"
                            );
                        }

                        v_total = v_total + "";
                        v_total = parseInt(v_total.replace(/[\D]+/g, ""));
                        v_total = v_total + "";
                        v_total = v_total.replace(/([0-9]{2})$/g, ",$1");

                        if (v_total.length > 6) {
                            v_total = v_total.replace(
                                /([0-9]{3}),([0-9]{2}$)/g,
                                ".$1,$2"
                            );
                        }

                        //$("#" + target).append($("<option></option>").val(obj.installment).html(obj.installment + "X de R$ "+ v_mes + ", TOTAL R$ " + v_total));
                        html +=
                            '<li class="list-group-item box_cbk_parcela" parcela="' +
                            obj2[i].installment +
                            '" valor="' +
                            v_total +
                            '"><strong>' +
                            obj2[i].installment +
                            "X de R$ " +
                            v_mes +
                            "</strong><br><span class='box_cbk_parcela_font2'>total de R$ " +
                            v_total +
                            "</span></li>";
                    }
                }
                html += "</ul>";
                if (temparcde6a12 > 0) {
                    $("#bxParcParcelow2").html(html);
                }
                $("#card_parcelas").val(parseInt(tParcela));

                wcppa_iniJS();

                $(".box_cbk_parcela").each(function (index) {
                    var p = $(this).attr("parcela");
                    if (parseInt(p) == parseInt(tParcela)) {
                        $(this).css("background", "#fbbc04");
                        $(this).css("color", "#000");
                        $(this).find("span").css("color", "#000");
                    }
                });

                /*
                //console.log(data);
                //console.log("DOLAR = " + data.dolar);
                //console.log("MOEDA = " + data.moeda);
                //console.log("PARCELAS = " + data.json);
                //console.log("PARCELAS = " + data.json);
                localStorage.setItem('TOTAL_PED_GERAL', data.total_geral);

                var json2 = $.parseJSON(data.json);
                

                $("#" + target).find('option').remove();
                $("#" + target).append($("<option value=''>SELECIONE</option>").val('').html("SELECIONE"));
                //$('select[name=' + target + ']').selectpicker('refresh');
                var v_mes = 0;
                var v_total = 0;
                $.each(json2, function (idx, obj) {
                    v_mes = obj.monthly;
                    //v_mes = parseFloat(v_mes);
                    //v_mes = v_mes.toFixed(2);

                    v_total = obj.total;
                    
                    //v_total = parseFloat(v_total);
                    //v_total = v_total.toFixed(2);

                    v_mes = v_mes + '';
                    v_mes = parseInt(v_mes.replace(/[\D]+/g,''));
                    v_mes = v_mes + '';
                    v_mes = v_mes.replace(/([0-9]{2})$/g, ",$1");
    
                    if (v_mes.length > 6) {
                        v_mes = v_mes.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");
                    }

                    v_total = v_total + '';
                    v_total = parseInt(v_total.replace(/[\D]+/g,''));
                    v_total = v_total + '';
                    v_total = v_total.replace(/([0-9]{2})$/g, ",$1");
    
                    if (v_total.length > 6) {
                        v_total = v_total.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");
                    }

                    $("#" + target).append($("<option></option>").val(obj.installment).html(obj.installment + "X de R$ "+ v_mes + ", TOTAL R$ " + v_total));
                });
                //$('select[name=' + target + ']').selectpicker('refresh');
                */
            },
            beforeSend: function () {
                $("#" + target)
                    .find("option")
                    .remove();
                $("#" + target).append(
                    $("<option></option>").val("").html("[Carregando...]")
                );
                //$('select[name=' + target + ']').selectpicker('refresh');
            },
        });
    }

    function wcppa_getParcelasDolar(order_id, acc, apihost) {
        var total = $("#WC_PARCELOW_TOTAL").val();
        jQuery.ajax({
            url: script_ajax.ajax_url,
            type: "POST",
            dataType: "JSON",
            data: {
                action: "wcppa_carrega_ajax",
                acao: "WC_PARCELOW_SIMULATE_PARCELAS_DOLAR",
                order_id: order_id,
                acc: acc,
                total: total,
                apihost: apihost,
            },
            success: function (data) {
                localStorage.setItem(
                    "LS_OBJ_JSON_PARCELAS_DOLAR",
                    JSON.stringify(data)
                );
            },
        });
    }

    function readJsonParcDolar(installment) {
        var data_json = localStorage.getItem("LS_OBJ_JSON_PARCELAS_DOLAR");
        //console.log(data_json);
        var json2 = $.parseJSON(data_json);
        //console.log(json2.data.creditcard.installments);
        var v_total = 0;
        var ret = 0;
        $.each(json2.data.creditcard.installments, function (idx, obj) {
            //console.log(installment + ' = ' + obj.installment);
            if (parseInt(installment) == parseInt(obj.installment)) {
                v_total = obj.total;
                v_total = v_total + "";
                v_total = parseInt(v_total.replace(/[\D]+/g, ""));
                v_total = v_total + "";
                v_total = v_total.replace(/([0-9]{2})$/g, ",$1");

                if (v_total.length > 6) {
                    v_total = v_total.replace(
                        /([0-9]{3}),([0-9]{2}$)/g,
                        ".$1,$2"
                    );
                }
                ret = obj.total;
            }
        });
        //console.log(ret);
        return ret;
    }

    function wcppa_iniJS() {
        jQuery(document).ready(function ($) {
            $("#btn_response_question").on("click", function () {
                wcppa_responseQuestion("#boxRespQuests");
            });

            $("#btn_show_form_card").on("click", function () {
                wcppa_showFormCard("#boxCartao");
            });

            $("#btn_show_pix").on("click", function () {
                wcppa_pagarComPix("#boxPix");
            });
            
             $(".otherpayment").on("click", function () {   
                 $('#boxCartao').css("display", "none");
                 $('#boxMeioPagto').css("display", "block");
            });
            
            $(document).on('click','.otherpaymentpix',function(){
                $('#boxPix').css("display", "none");
                $('#boxMeioPagto').css("display", "block");
                clearInterval(myInterval);
            });

            $("#btn_finaliza_com_cartao").on("click", function () {
                var target = "#boxMsgFinalizaCard";
                wcppa_showMsg(target);

                var card_name = $("#card_name").val();
                var card_numero = $("#card_numero").val();
                var card_cvv = $("#card_cvv").val();
                var card_data_valid = $("#card_numero").val();
                var card_parcelas = $("#card_parcelas").val();
                var card_cep = $("#card_cep").val();
                var card_street = $("#card_street").val();
                var card_street_number = $("#card_street_number").val();
                var card_street_bairro = $("#card_street_bairro").val();
                var card_street_city = $("#card_street_city").val();
                var card_street_state = $("#card_street_state").val();
                var li_termo = false;
                if ($("#li_termo").is(":checked")) {
                    li_termo = true;
                }
                if (card_name == "") {
                    $(target).html(
                        '<div class="alert alert-danger" role="alert">O campo <strong>NOME COMPLETO</strong> não pode ser vazio.</div>'
                    );
                    wcppa_hiddMsg(target, 3000);
                } else if (card_numero == "") {
                    $(target).html(
                        '<div class="alert alert-danger" role="alert">O campo <strong>NÚMERO DO CARTÃO</strong> não pode ser vazio.</div>'
                    );
                    wcppa_hiddMsg(target, 3000);
                } else if (card_cvv == "") {
                    $(target).html(
                        '<div class="alert alert-danger" role="alert">O campo <strong>CVV - CÓDIGO DE SEGURANÇA</strong> não pode ser vazio.</div>'
                    );
                    wcppa_hiddMsg(target, 3000);
                } else if (card_data_valid == "") {
                    $(target).html(
                        '<div class="alert alert-danger" role="alert">O campo <strong>DATA DE EXPIRAÇÃO</strong> não pode ser vazio.</div>'
                    );
                    wcppa_hiddMsg(target, 3000);
                } else if (card_parcelas == "") {
                    $(target).html(
                        '<div class="alert alert-danger" role="alert">Selecione uma <strong>PARCELA</strong>.</div>'
                    );
                    wcppa_hiddMsg(target, 3000);
                } else if (card_cep == "") {
                    $(target).html(
                        '<div class="alert alert-danger" role="alert">O campo <strong>CEP</strong> não pode ser vazio.</div>'
                    );
                    wcppa_hiddMsg(target, 3000);
                } else if (card_street == "") {
                    $(target).html(
                        '<div class="alert alert-danger" role="alert">O campo <strong>ENDEREÇO</strong> não pode ser vazio.</div>'
                    );
                    wcppa_hiddMsg(target, 3000);
                } else if (card_street_number == "") {
                    $(target).html(
                        '<div class="alert alert-danger" role="alert">O campo <strong>NÚMERO</strong> não pode ser vazio.</div>'
                    );
                    wcppa_hiddMsg(target, 3000);
                } else if (card_street_bairro == "") {
                    $(target).html(
                        '<div class="alert alert-danger" role="alert">O campo <strong>BAIRRO</strong> não pode ser vazio.</div>'
                    );
                    wcppa_hiddMsg(target, 3000);
                } else if (card_street_city == "") {
                    $(target).html(
                        '<div class="alert alert-danger" role="alert">O campo <strong>CIDADE</strong> não pode ser vazio.</div>'
                    );
                    wcppa_hiddMsg(target, 3000);
                } else if (card_street_state == "") {
                    $(target).html(
                        '<div class="alert alert-danger" role="alert">Selecione um <strong>ESTADO</strong>.</div>'
                    );
                    wcppa_hiddMsg(target, 3000);
                } else if (li_termo == "") {
                    $(target).html(
                        '<div class="alert alert-danger" role="alert">Confirme se você concorda com os <strong>TERMOS DE USO</strong> e <strong>POLÍTICA DE PRIVACIDADE</strong> da plataforma Parcelow.</div>'
                    );
                    wcppa_hiddMsg(target, 3000);
                } else {
                    wcppa_finalizaPedidoCartao("#boxMsgFinalizaCard");
                }
            });

            $("#wcppa_btn_concordo").on("click", function () {
                $("#wcppa_boxValidaTotal").css("display", "none");
                $("#boxQuestions").css("display", "block");
            });

            $("#wcppa_btn_concordo2").on("click", function () {
                $("#wcppa_boxValidaTotal2").css("display", "none");
                $("#boxCartao").css("display", "block");
            });

            $("#wcppa_btn_nconcordo").on("click", function () {
                $("#wcppa_boxValidaTotal").css("display", "none");
                $("#boxQuestions").css("display", "none");
                $("#mod_gatway_parcelow").modal("hide");
            });

            $("#wcppa_btn_nconcordo2").on("click", function () {
                $("#wcppa_boxValidaTotal2").css("display", "none");
                $("#boxCartao").css("display", "none");
                $("#mod_gatway_parcelow").modal("hide");
            });

            $(".box_cbk_parcela").on("click", function () {
                $(".box_cbk_parcela").each(function (index) {
                    $(this).css("background", "#fff");
                    $(this).css("color", "#000");
                    $(this).find("span").css("color", "#777");
                });

                var parcela = $(this).attr("parcela");
                var valor = $(this).attr("valor");
                var vtotal = readJsonParcDolar(parseInt(parcela));
                $("#card_parcelas").val(parseInt(parcela));
                //console.log('parcela = ' + parcela);
                $(this).css("background", "#fbbc04");
                $(this).css("color", "#000");
                $(this).find("span").css("color", "#000");

                if (vtotal >= 3000) {
                    $("#wcppa_boxValidaTotal2").css("display", "block");
                    $("#boxCartao").css("display", "none");
                } else {
                    $("#wcppa_boxValidaTotal2").css("display", "none");
                    $("#boxCartao").css("display", "block");
                }
            });

            $("#card_cep").blur(function () {
                if ($(this).val() != "") {
                    wcppa_getCep("#boxCepCard", $(this).val());
                }
            });

            $("#card_numero").blur(function () {
                var n = $(this).val();
                var ret = false;
                if (n != "") {
                    var target = "#boxMsgCard";
                    wcppa_showMsg(target);
                    ret = wcppa_validCardNumber(n);
                    if (ret == false) {
                        $(target).html(
                            '<span style="color: #dc3545;"><i class="fa-solid fa-circle-exclamation"></i> Inválido.</span>'
                        );
                        wcppa_hiddMsg(target, 3000);
                        $(this).val("");
                    }
                }
            });

            $("#card_data_valid").blur(function () {
                var da = $(this).val();

                if (da != "" && da.length == 7) {
                    var target = "#boxMsgDataExp";
                    wcppa_showMsg(target);

                    var ret = 0;
                    var data = new Date();
                    var dia = String(data.getDate()).padStart(2, "0");
                    var mes = String(data.getMonth() + 1).padStart(2, "0");
                    var ano = data.getFullYear();

                    var dav = da.split("/");
                    var mesexp = dav[0];
                    var anoexp = dav[1];
                    //console.log(parseInt(anoexp) +' >= '+ parseInt(ano));
                    //console.log(parseInt(anoexp) +' >= '+ parseInt(ano) +' && '+ parseInt(mesexp) +' >= '+ parseInt(mes));
                    if (
                        parseInt(anoexp) == parseInt(ano) &&
                        parseInt(mesexp) >= parseInt(mes)
                    ) {
                        ret++;
                        //console.log('X1');
                    } else if (
                        parseInt(anoexp) > parseInt(ano) &&
                        parseInt(mesexp) < parseInt(mes) &&
                        parseInt(mesexp) >= 1 &&
                        parseInt(mesexp) <= 12
                    ) {
                        ret++;
                        //console.log('X2');
                    } else if (
                        parseInt(anoexp) > parseInt(ano) &&
                        parseInt(mesexp) < parseInt(mes) &&
                        parseInt(mesexp) >= 1 &&
                        parseInt(mesexp) <= 12
                    ) {
                        ret++;
                        //console.log('X3');
                    } else if (
                        parseInt(anoexp) > parseInt(ano) &&
                        parseInt(mesexp) >= parseInt(mes) &&
                        parseInt(mesexp) >= 1 &&
                        parseInt(mesexp) <= 12
                    ) {
                        ret++;
                        //console.log('X3_1');
                    } else if (parseInt(anoexp) < ano) {
                        ret = 0;
                        //console.log('X4');
                    } else if (parseInt(mesexp) < 1) {
                        ret = 0;
                        //console.log('X5');
                    } else if (parseInt(mesexp) > 12) {
                        ret = 0;
                        //console.log('X6');
                    }

                    if (ret == 0) {
                        $(target).html(
                            '<span style="color: #dc3545;"><i class="fa-solid fa-circle-exclamation"></i> Inválido.</span>'
                        );
                        wcppa_hiddMsg(target, 3000);
                        $(this).val("");
                    }
                }
            });

            /*
            $( "#card_parcelas" ).change(function() {
                //console.log('PARCELA -- ' + $(this).val());
                var vtotal = readJsonParcDolar( $(this).val() );
                //console.log('TOTAL X = ' + vtotal);
                var se = $(this).text();
                var s2 = se.split('R$');
                s2 = $.trim(s2[1]);
                s2 = s2.replace(".","");
                s2 = s2.replace(",",".");
                console.log(s2);
                s2 = parseFloat(s2);
                //console.log(s2);
                if(vtotal >= 3000){
                    $('#wcppa_boxValidaTotal2').css('display','block');
                    $('#boxCartao').css('display','none');
                }else{
                    $('#wcppa_boxValidaTotal2').css('display','none');
                    $('#boxCartao').css('display','block');
                }
            });*/

            //function initMasks() {
            $("input").unmask();
            $(".codigo-sms").mask("0-0-0-0-0-0");
            $(".mdata").mask("00/00/0000");
            $(".dataexp").mask("00/0000");
            $(".cvv").mask("0000");
            $(".cep").mask("00000-000");
            $(".nrLogra").mask("000000");
            $(".nrCard").mask("0000000000000000");
            $(".cpf").mask("000.000.000-00");
            $(".cnpj").mask("00.000.000/0000-00", {
                reverse: true,
            });
            $(".phone_with_ddd").mask("(00) 0000-0000");
            $(".celular_with_ddd").mask("(00) 00000-0000");

            var options = {
                onKeyPress: function (cpf, ev, el, op) {
                    var masks = ["000.000.000-000", "00.000.000/0000-00"];
                    $(".cnpjecpf").mask(
                        cpf.length > 14 ? masks[1] : masks[0],
                        op
                    );
                },
            };
            $(".cnpjecpf").length > 11
                ? $(".cnpjecpf").mask("00.000.000/0000-00", options)
                : $(".cnpjecpf").mask("000.000.000-00#", options);

            var SPMaskBehavior = function (val) {
                    return val.replace(/\D/g, "").length === 11
                        ? "(00) 00000-0000"
                        : "(00) 0000-00009";
                },
                spOptions = {
                    onKeyPress: function (val, e, field, options) {
                        field.mask(
                            SPMaskBehavior.apply({}, arguments),
                            options
                        );
                    },
                };

            $(".fixocel").mask(SPMaskBehavior, spOptions);
            //}

            function wcppa_validCardNumber(cardNo) {
                let even = false;
                return (
                    cardNo
                        .split("")
                        .reverse()
                        .map(Number)
                        .reduce(
                            (sum, d) =>
                                (sum += (even = !even)
                                    ? d
                                    : d < 5
                                    ? d * 2
                                    : (d - 5) * 2 + 1),
                            0
                        ) %
                        10 ===
                    0
                );
            }

            function wcppa_getCep(target, cep) {
                var acc = $("#PARCELOW_ACC").val();
                var apihost = $("#PARCELOW_API_HOST").val();
                jQuery.ajax({
                    url: script_ajax.ajax_url,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        action: "wcppa_carrega_ajax",
                        acao: "WC_PARCELOW_BUSCA_CEP",
                        cep: cep,
                        acc: acc,
                        apihost: apihost,
                    },
                    success: function (obj) {
                        $(target).html("");
                        //console.log(obj);
                        var logradouro = "";
                        var bairro = "";
                        var cidade = "";
                        var uf = "";
                        $("#card_street_number").val("");
                        $("#card_street_supplement").val("");

                        logradouro = obj.logradouro;
                        bairro = obj.bairro;
                        cidade = obj.cidade;
                        uf = obj.uf;

                        $("#card_street").val(logradouro);
                        $("#card_street_bairro").val(bairro);
                        $("#card_street_city").val(cidade);
                        $("#card_street_state").val(uf).change();
                    },
                    beforeSend: function () {
                        $(target).html(
                            '<div class="spinner-border spinner-border-sm" role="status"><span class="visually-hidden">Loading...</span></div>'
                        );
                    },
                });
            }

            function wcppa_responseQuestion(target) {
                var base = $("#WCPPA_PARCELOW_GATEWAY_PLUGIN_URL").val();
                var order_id = $("#PARCELOW_COD_PED").val();
                var acc = $("#PARCELOW_ACC").val();
                var apihost = $("#PARCELOW_API_HOST").val();

                var vq1 = false;
                if ($("input[name='quest_1']").is(":checked")) {
                    vq1 = true;
                }

                var vq2 = false;
                if ($("input[name='quest_2']").is(":checked")) {
                    vq2 = true;
                }

                if (vq1 == false) {
                    $(target).html(
                        '<br><br><div class="alert alert-danger"><i class="bi bi-x-circle"></i> Marque 1 resposta da pergunta 1.</div><br><br>'
                    );
                } else if (vq2 == false) {
                    $(target).html(
                        '<br><br><div class="alert alert-danger"><i class="bi bi-x-circle"></i> Marque 1 resposta da pergunta 2.</div><br><br>'
                    );
                } else {
                    var q1 = $("input[name='quest_1']:checked").val();
                    q1 = q1.split(";");

                    var q2 = $("input[name='quest_2']:checked").val();
                    q2 = q2.split(";");

                    jQuery.ajax({
                        url: script_ajax.ajax_url,
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            action: "wcppa_carrega_ajax",
                            acao: "RESPONSEQUESTION",
                            order_id: order_id,
                            acc: acc,
                            apihost: apihost,
                            p1: q1[0],
                            r1: q1[1],
                            p2: q2[0],
                            r2: q2[1],
                        },
                        success: function (data) {
                            //console.log('RESPOSTA QUESTION: ' + data);
                            $(target).html("");
                            //console.log(data.status);
                            if (parseInt(data.status) == 1) {
                                $("#boxQuestions").html("&nbsp;");
                                //wcppa_showFormCard('#boxCartao');
                                wcppa_showMeiosPagto("#boxMeioPagto");
                            } else {
                                //console.log('Identidade não confirmada');
                                $(target).html(
                                    '<br><br><div class="alert alert-danger"><i class="bi bi-x-circle"></i> Identidade não confirmada.</div><br><br>'
                                );
                                setTimeout(function () {
                                    window.location.href =
                                        PARCELOW_URL_ATUAL + "/checkout";
                                }, 2000);
                            }
                        },
                        beforeSend: function () {
                            $(target).html(
                                '<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                            );
                        },
                    });
                }
            }

           var myInterval;
            function wcppa_pagarComPix(target) {
                var base = $("#WCPPA_PARCELOW_GATEWAY_PLUGIN_URL").val();
                var order_id = $("#PARCELOW_COD_PED").val();
                var acc = $("#PARCELOW_ACC").val();
                var apihost = $("#PARCELOW_API_HOST").val();
                var order_id_local = $("#PARCELOW_COD_PED_LOCAL").val();
                var url_atual = $("#PARCELOW_URL_ATUAL").val();
                var order_key = $("#WC_PARCELOW_ORDER_KEY").val();
                var total = $("#WC_PARCELOW_TOTAL").val();
                var total_ = localStorage.getItem("TOTAL_PED_GERAL");

                total_ = total_ + "";
                total_ = parseInt(total_.replace(/[\D]+/g, ""));
                total_ = total_ + "";
                total_ = total_.replace(/([0-9]{2})$/g, ",$1");

                if (total_.length > 6) {
                    total_ = total_.replace(
                        /([0-9]{3}),([0-9]{2}$)/g,
                        ".$1,$2"
                    );
                }

                $(target).css("display", "block");
                wcppa_hideMeiosPagto("#boxMeioPagto");

                jQuery.ajax({
                    url: script_ajax.ajax_url,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        action: "wcppa_carrega_ajax",
                        acao: "GERARPIX",
                        order_id: order_id,
                        acc: acc,
                        apihost: apihost,
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        console.log(error);
                    },
                    success: function (qrc) {
                        var pageURL = $(location).attr("href");
                        pageURL = pageURL.replaceAll("?show_parcelow", "");
                        //window.location.href = pageURL + 'order-received/' + order_id_local + '/?key=' + order_key;
                        pageURL =
                            pageURL +
                            "order-received/" +
                            order_id_local +
                            "/?key=" +
                            order_key;

                        $(target).html("");
                        var link = '<div id="boxQRCODE" style="margin:0 auto;width:399px;height:399px;"><img src="' +
                            qrc.link +
                            '" title="QR CODE PARCELOW" /></div><br><br>';
                        link +=
                            '<br><br><p class="text-center"><img onclick="navigator.clipboard.writeText(\'' +
                            qrc.link +
                            "');$('#box_parcelow_alert').css('display','block');\" style=\"cursor:pointer;\" src=\"" +
                            base +
                            'assets/imgs/copiaecola.png"></p>';
                        link +=
                            '<br><div id="box_parcelow_alert" style="display:none;"><div class="alert alert-success text-center" role="alert">Copiado com sucesso.</div></div>';

                        link += "<br><br>";
                        link +=
                            '<p style="text-align: center;"><span style="font-size: 2em;letter-spacing: -2px;color:#e07128;"><strong>R$ ' +
                            total_ +
                            "</stron></span><br>TOTAL A PAGAR</p>";
                        link +=
                            '<div id="timer" style="text-align: center; font-size: 2em;font-weight: bold;"></div>';
                            
                        link +=
                            '<div class="alert alert-info text-center txt" role="alert">Payment status checking</div>';
                            
                        link +=
                            '<div class="txtPaid"></div>';
                            
                        $(target).html(link);
                       
                        myInterval = setInterval(checaStatusPix, 5000); 
                        
                    
                        // link +=
                        //     '<p style="text-align: center;">Tempo restante para fechar a tela.</p>';
                        // link +=
                        //     '<p style="text-align: center;"><a href="' +
                        //     pageURL +
                        //     '">Clique aqui se já pagou.</a></p>';
                     

                        // display = $("#timer");
                        // wcppa_startTimer(360, display, pageURL);
                        
                        
                    },
                    beforeSend: function () {
                        $(target).html(
                            '<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                        );
                    },
                });
            }
            
            
            function checaStatusPix(){
                 var order_id = $("#PARCELOW_COD_PED").val();
                 var order_id_local = $("#PARCELOW_COD_PED_LOCAL").val();
                 var acc = $("#PARCELOW_ACC").val();
                 var apihost = $("#PARCELOW_API_HOST").val();
                 var order_key = $("#WC_PARCELOW_ORDER_KEY").val();
                    jQuery.ajax({
                        url: script_ajax.ajax_url,
                        type: "POST",
                        dataType: "JSON",
                        data: {
                            action: "wcppa_carrega_ajax",
                            acao: "STATUSPIX",
                            order_id: order_id,
                            acc: acc,
                            apihost: apihost,
                        },
                        beforeSend: function() {
            				$('.txt').text('Payment status checking');
            			},
                        success: function (res) {
                            if(res.status == 2){
                              $('.txt').css({"display" : "none"});
                              $('.txtPaid').html('<div class="alert alert-success text-center" role="alert">'+res.status_text+', redirecionando aguarde ...</div>');
                              var pageURL = $(location).attr("href");
                              pageURL = pageURL.replaceAll("?show_parcelow", "");
                              setTimeout(function() { 
                                 window.location.href = pageURL + 'order-received/' + order_id_local + '/?key=' + order_key;
                                  clearInterval(myInterval);
                              }, 7000);
                            }
                        },
                        error: function (request, status, error) {
                            console.log(request.responseText);
                            console.log(error);
                        },
                        complete: function() {
            				$('.txt').text('Awating for payment');
            			}
                        
                    })
            }

            function wcppa_startTimer(duration, display, pageURL) {
                var timer = duration,
                    minutes,
                    seconds;
                setInterval(function () {
                    minutes = parseInt(timer / 60, 10);
                    seconds = parseInt(timer % 60, 10);

                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;

                    display.text(minutes + ":" + seconds);

                    if (--timer < 0) {
                        timer = duration;
                    }
                    if (parseInt(minutes) == 0 && parseInt(seconds) == 0) {
                        window.location.href = pageURL;
                    }
                }, 1000);
            }

            function wcppa_finalizaPedidoCartao(target) {
                var base = $("#WCPPA_PARCELOW_GATEWAY_PLUGIN_URL").val();
                var order_id = $("#PARCELOW_COD_PED").val();
                var acc = $("#PARCELOW_ACC").val();
                var apihost = $("#PARCELOW_API_HOST").val();
                var order_id_local = $("#PARCELOW_COD_PED_LOCAL").val();
                var url_atual = $("#PARCELOW_URL_ATUAL").val();
                var order_key = $("#WC_PARCELOW_ORDER_KEY").val();

                var card_name = $("#card_name").val();
                var card_numero = $("#card_numero").val();
                var card_cvv = $("#card_cvv").val();
                var card_data_valid = $("#card_data_valid").val();
                var card_parcelas = $("#card_parcelas").val();
                var card_cep = $("#card_cep").val();
                var card_street = $("#card_street").val();
                var card_street_number = $("#card_street_number").val();
                var card_street_supplement = $("#card_street_supplement").val();
                var card_street_bairro = $("#card_street_bairro").val();
                var card_street_city = $("#card_street_city").val();
                var card_street_state = $("#card_street_state").val();

                jQuery.ajax({
                    url: script_ajax.ajax_url,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        action: "wcppa_carrega_ajax",
                        acao: "FINALIZAPAGTOCARTAO",
                        order_id: order_id,
                        acc: acc,
                        apihost: apihost,
                        card_name: card_name,
                        card_numero: card_numero,
                        card_cvv: card_cvv,
                        card_data_valid: card_data_valid,
                        card_parcelas: card_parcelas,
                        card_cep: card_cep,
                        card_street: card_street,
                        card_street_number: card_street_number,
                        card_street_supplement: card_street_supplement,
                        card_street_bairro: card_street_bairro,
                        card_street_city: card_street_city,
                        card_street_state: card_street_state,
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        console.log(error);
                    },
                    success: function (data) {
                        $(target).html(
                            '<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                        );
                        //console.log("FINALIZADO = "+data.result);
                        //$status + ";"+$json->message+";1";
                        var retorno = data.result;
                        var ret = retorno.split(";");
                        if (ret.length > 0) {
                            if (parseInt(ret[2]) == 1) {
                                $("#boxCartao").css("display", "none");
                                $(target).html(
                                    '<br><br><div class="alert alert-success"> <i class="bi bi-check-circle"></i> ' +
                                        ret[1] +
                                        "</div><br><br>"
                                );
                                setTimeout(function () {
                                    var pageURL = $(location).attr("href");
                                    pageURL = pageURL.replaceAll(
                                        "?show_parcelow",
                                        ""
                                    );
                                    window.location.href =
                                        pageURL +
                                        "order-received/" +
                                        order_id_local +
                                        "/?key=" +
                                        order_key;
                                }, 3000);
                            } else {
                                $(target).html(
                                    '<br><br><div class="alert alert-danger"><i class="bi bi-x-circle"></i> ' +
                                        ret[1] +
                                        "</div><br><br>"
                                );
                            }
                        }
                    },
                    beforeSend: function () {
                        $(target).html(
                            '<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                        );
                    },
                });
            }

            function wcppa_showQuest(target) {}

            function wcppa_hideMeiosPagto(target) {
                $(target).css("display", "none");
            }

            function wcppa_showMeiosPagto(target) {
                $(target).css("display", "block");
            }

            function wcppa_showFormCard(target) {
                $(target).css("display", "block");
                wcppa_hideMeiosPagto("#boxMeioPagto");
            }

            function wcppa_hiddMsg(target, t) {
                setTimeout(function () {
                    $(target).fadeOut();
                    $(target).html("&nbsp;");
                }, t);
            }

            function wcppa_showMsg(target) {
                $(target).fadeIn();
                $(target).html("&nbsp;");
            }

            function wcppa_apenasNumeros(string) {
                var numsStr = string.replace(/[^0-9]/g, "");
                return numsStr;
            }
        });
    }
});
