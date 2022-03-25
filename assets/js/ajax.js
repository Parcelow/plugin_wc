const $ = jQuery;

var uri_parc = window.location.href;
var exist_parc = false;
if(uri_parc.indexOf("show_parcelow") !== -1){
    exist_parc = true;
}

$(document).ready(function($){
    //e.preventDefault();
    $('body').append('<div id="boxHTMLModalParcelow"></div>');



    if(exist_parc == true){
        jQuery.ajax({
            url: script_ajax.ajax_url,
            type: "POST",
            dataType: "JSON",
            data: {
                action: 'wcppa_carrega_ajax',
                acao: 'INICIAFRONTPARCELOW',
                uripag: window.location.href
            },
            success: function(data){
                if(data.status == 1){
                    $('#boxHTMLModalParcelow').html(data.texto);
                    setTimeout(() => {
                        var ped_cod_parcelow = $('#PARCELOW_COD_PED').val();
                        var acc = $('#PARCELOW_ACC').val();
                        var apihost = $('#PARCELOW_API_HOST').val();
                        
                        var myModal = new bootstrap.Modal(document.getElementById('mod_gatway_parcelow'), {
                            keyboard: false
                        });
                        myModal.show();
                        wcppa_getQuestions('#boxQuestions', ped_cod_parcelow, acc, apihost);
                    }, 1000);

                }
            },
            error: function (request, status, error) {
                console.log(request.responseText);
            }
        });
    }



    function wcppa_getQuestions(target, order_id, acc, apihost)
    {
        jQuery.ajax({
            url: script_ajax.ajax_url,
            type: "POST",
            dataType: "JSON",
            data: {
                action: 'wcppa_carrega_ajax',
                acao: 'SHOWQUETIONS',
                order_id: order_id,
                acc: acc,
                apihost: apihost
            },
            error: function (request, status, error) {
                console.log(request.responseText);
                console.log(error);
            },
            success: function (data) {
                $(target).html('');
                //console.log('QUESTIONS = ' + retorno);
                //$('#boxMsgOrder').html('&nbsp;');
                $('#boxQuestions').html(data.texto);

                //carrega parcelas
                wcppa_getParcelas('card_parcelas', order_id, acc, apihost);

                wcppa_iniJS();
            },
            beforeSend: function () {
                $(target).html('<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>');
            }
        });
    }

    function wcppa_getParcelas(target, order_id, acc, apihost){
        var total = $('#WC_PARCELOW_TOTAL').val();
        jQuery.ajax({
            url: script_ajax.ajax_url,
            type: "POST",
            dataType: "JSON",
            data: {
                action: 'wcppa_carrega_ajax',
                acao: 'WC_PARCELOW_TOTAL',
                order_id: order_id,
                acc: acc,
                total: total,
                apihost: apihost
            },
            success: function (data) {
                //console.log("PARCELAS = " + data.json);
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

                    $("#" + target).append($("<option></option>").val(obj.installment).html(obj.installment + "X de R$ "+ v_mes + ", TOTAL R$ " + v_total));
                });
                //$('select[name=' + target + ']').selectpicker('refresh');
            },
            beforeSend: function () {
                $("#" + target).find('option').remove();
                $("#" + target).append($("<option></option>").val('').html("[Carregando...]"));
                //$('select[name=' + target + ']').selectpicker('refresh');
            }
        });
    }

    function wcppa_iniJS()
    {
        jQuery(document).ready(function($){
    
            $( "#btn_response_question" ).on( "click", function() {
                wcppa_responseQuestion('#boxRespQuests');
            });
    
            $( "#btn_show_form_card" ).on( "click", function() {
                wcppa_showFormCard('#boxCartao');
            });
    
            $( "#btn_show_pix" ).on( "click", function() {
                wcppa_pagarComPix('#boxPix');
            });
    
            $( "#btn_finaliza_com_cartao" ).on( "click", function() {
                wcppa_finalizaPedidoCartao('#boxMsgFinalizaCard');
            });



    
            
            function wcppa_responseQuestion(target)
            {
                var base = $('#WCPPA_PARCELOW_GATEWAY_PLUGIN_URL').val();
                var order_id = $('#PARCELOW_COD_PED').val();
                var acc = $('#PARCELOW_ACC').val();
                var apihost = $('#PARCELOW_API_HOST').val();
    
                var q1 = $("input[name='quest_1']:checked").val()
                q1 = q1.split(';');
    
                var q2 = $("input[name='quest_2']:checked").val()
                q2 = q2.split(';');
    
                jQuery.ajax({
                    url: script_ajax.ajax_url,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        action: 'wcppa_carrega_ajax',
                        acao: 'RESPONSEQUESTION',
                        order_id: order_id,
                        acc: acc,
                        apihost: apihost,
                        p1: q1[0],
                        r1: q1[1],
                        p2: q2[0],
                        r2: q2[1]
                    },
                    success: function (data) {
                        $(target).html('');
                        console.log(data.status);
                        if(parseInt(data.status) == 1){
                            $('#boxQuestions').html('&nbsp;');
                            //wcppa_showFormCard('#boxCartao');
                            wcppa_showMeiosPagto('#boxMeioPagto');
                        } else{
                            console.log('Identidade não confirmada');
                        }
                    },
                    beforeSend: function () {
                        $(target).html('<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                    }
                });
            }
    
            function wcppa_pagarComPix(target)
            {
                var base = $('#WCPPA_PARCELOW_GATEWAY_PLUGIN_URL').val();
                var order_id = $('#PARCELOW_COD_PED').val();
                var acc = $('#PARCELOW_ACC').val();
                var apihost = $('#PARCELOW_API_HOST').val();
                var order_id_local = $('#PARCELOW_COD_PED_LOCAL').val();
                var url_atual = $('#PARCELOW_URL_ATUAL').val();
                var order_key = $('#WC_PARCELOW_ORDER_KEY').val();
                
                $(target).css('display','block');
                wcppa_hideMeiosPagto('#boxMeioPagto');
   
                jQuery.ajax({
                    url: script_ajax.ajax_url,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        action: 'wcppa_carrega_ajax',
                        acao: 'GERARPIX',
                        order_id: order_id,
                        acc: acc,
                        apihost: apihost
                    },
                    error: function (request, status, error) {
                        console.log(request.responseText);
                        console.log(error);
                    },
                    success: function (qrc) {

                        $(target).html('');
                        var link = '<div id="boxQRCODE" style="margin:0 auto;width:399px;height:399px;"><img src="' + qrc.link + '" title="QR CODE PARCELOW" /></div><br><br>';
                        link += '<br><br><p class="text-center"><img onclick="navigator.clipboard.writeText(\'' + qrc.link + '\');$(\'#box_parcelow_alert\').css(\'display\',\'block\');" style="cursor:pointer;" src="' + base + 'assets/imgs/copiaecola.png"></p>';
                        link += '<br><div id="box_parcelow_alert" style="display:none;"><div class="alert alert-success text-center" role="alert">Copiado com sucesso.</div></div>';

                        link += '<br><br>';
                        link += '<div id="timer" style="text-align: center; font-size: 2em;font-weight: bold;"></div>';
                        link += '<p style="text-align: center;">Tempo restante para fechar a tela.</p>';
                        $(target).html(link);

                        

    
                        var pageURL = $(location).attr("href");
                        pageURL = pageURL.replaceAll("?show_parcelow", "");
                        //window.location.href = pageURL + 'order-received/' + order_id_local + '/?key=' + order_key;
                        pageURL = pageURL + 'order-received/' + order_id_local + '/?key=' + order_key;
                        display = $('#timer');
                        wcppa_startTimer(60, display, pageURL);
    

                    },
                    beforeSend: function () {
                        $(target).html('<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                    }
                });
            }
    
            function wcppa_startTimer(duration, display, pageURL)
            {
                var timer = duration, minutes, seconds;
                setInterval(function () {
                    minutes = parseInt(timer / 60, 10);
                    seconds = parseInt(timer % 60, 10);
            
                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;
            
                    display.text(minutes + ":" + seconds);
            
                    if (--timer < 0) {
                        timer = duration;
                    }
                    if(parseInt(minutes) == 0 && parseInt(seconds) == 0) {
                        window.location.href = pageURL;
                    }
                }, 1000);
            }
    
            function wcppa_finalizaPedidoCartao(target)
            {
                var base = $('#WCPPA_PARCELOW_GATEWAY_PLUGIN_URL').val();
                var order_id = $('#PARCELOW_COD_PED').val();
                var acc = $('#PARCELOW_ACC').val();
                var apihost = $('#PARCELOW_API_HOST').val();
                var order_id_local = $('#PARCELOW_COD_PED_LOCAL').val();
                var url_atual = $('#PARCELOW_URL_ATUAL').val();
                var order_key = $('#WC_PARCELOW_ORDER_KEY').val();
    
                var card_name = $('#card_name').val();
                var card_numero = $('#card_numero').val();
                var card_cvv = $('#card_cvv').val();
                var card_data_valid = $('#card_data_valid').val();
                var card_parcelas = $('#card_parcelas option:selected').val();
                var card_cep = $('#card_cep').val();
                var card_street = $('#card_street').val();
                var card_street_number = $('#card_street_number').val();
                var card_street_supplement = $('#card_street_supplement').val();
                var card_street_bairro = $('#card_street_bairro').val();
                var card_street_city = $('#card_street_city').val();
                var card_street_state = $('#card_street_state').val();

                jQuery.ajax({
                    url: script_ajax.ajax_url,
                    type: "POST",
                    dataType: "JSON",
                    data: {
                        action: 'wcppa_carrega_ajax',
                        acao: 'FINALIZAPAGTOCARTAO',
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
                        $(target).html('<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                        console.log("FINALIZADO = "+data.result);
                        //$status + ";"+$json->message+";1";
                        var retorno = data.result;
                        var ret = retorno.split(';');
                        if(ret.length > 0){
                            if(parseInt(ret[2]) == 1){
                                $('#boxCartao').css("display", "none");
                                $(target).html('<br><br><div class="alert alert-success"> <i class="bi bi-check-circle"></i> ' + ret[1] + '</div><br><br>');
                                setTimeout(function(){
                                    var pageURL = $(location).attr("href");
                                    pageURL = pageURL.replaceAll("?show_parcelow", "");
                                    window.location.href = pageURL + 'order-received/' + order_id_local + '/?key=' + order_key;
                                
                                }, 3000);
                            } else{
                                $(target).html('<br><br><div class="alert alert-danger"><i class="bi bi-x-circle"></i> ' + ret[1] + '</div><br><br>');
                            }

                        }
                    },
                    beforeSend: function () {
                        $(target).html('<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                    }
                });
            }
    
            function wcppa_hideMeiosPagto(target){
                $(target).css('display','none');
            }
    
            function wcppa_showMeiosPagto(target){
                $(target).css('display','block');
            }
    
            function wcppa_showFormCard(target){
                $(target).css('display','block');
                wcppa_hideMeiosPagto('#boxMeioPagto');
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
                var numsStr = string.replace(/[^0-9]/g, '');
                return numsStr;
            }
    
        });
    }

});