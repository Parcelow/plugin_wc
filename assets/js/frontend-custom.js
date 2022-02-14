jQuery(document).ready(function($) {
    $('body').on('.payment_method_splipay .place_order',function(e,data) {
        //alert('Added ' + data['div.widget_shopping_cart_content']);
       // if ($('#hidden_cart').length == 0) { //add cart contents only once
            //$('.added_to_cart').after('<a href="#TB_inline?width=600&height=550&inlineId=hidden_cart" class="thickbox">View my inline content!</a>');
        //    $(this).append('<a href="#TB_inline?width=300&height=550&inlineId=hidden_cart" id="show_hidden_cart" title="<h2>Cart</h2>" class="thickbox" style="display:none"></a>');
        //    $(this).append('<div id="hidden_cart" style="display:none">'+data['div.widget_shopping_cart_content']+'</div>');
       // }
       // $('#show_hidden_cart').click();
	   
	   console.log('cliquei no botao checkout');
    });

});


function iniJS() {
    jQuery(document).ready(function($){

        $( "#btn_response_question" ).on( "click", function() {
            responseQuestion('#boxRespQuests');
        });

        $( "#btn_show_form_card" ).on( "click", function() {
            showFormCard('#boxCartao');
        });

        $( "#btn_show_pix" ).on( "click", function() {
            pagarComPix('#boxPix');
        });

        $( "#btn_finaliza_com_cartao" ).on( "click", function() {
            finalizaPedidoCartao('#boxMsgFinalizaCard');
        });

        
        function responseQuestion(target){
            var base = $('#PARCELOW_GATEWAY_PLUGIN_URL').val();
            var order_id = $('#PARCELOW_COD_PED').val();
            var acc = $('#PARCELOW_ACC').val();
            var apihost = $('#PARCELOW_API_HOST').val();

            var q1 = $("input[name='quest_1']:checked").val()
            q1 = q1.split(';');

            var q2 = $("input[name='quest_2']:checked").val()
            q2 = q2.split(';');

            var dados = "acao=RESPONSEQUESTION&p1=" + q1[0] + "&r1=" + q1[1] + "&p2=" + q2[0] + "&r2=" + q2[1] + "&acc=" + acc + "&order_id=" +order_id;
            dados += "&apihost=" + apihost;

            $.ajax({
                type: "POST",
                url: base + "api/index.php",
                cache: false,
                data: dados,
                dataType: 'text',
                error: function (request, status, error) {
                    console.log(request.responseText);
                    console.log(error);
                },
                success: function (retorno) {
                    $(target).html('');
                    console.log("RESPOSTA = "+retorno);
                    if(parseInt(retorno) == 1){
                        $('#boxQuestions').html('&nbsp;');
                        //showFormCard('#boxCartao');
                        showMeiosPagto('#boxMeioPagto');
                    } else{
                        console.log('Identidade não confirmada');
                    }
                },
                beforeSend: function () {
                    $(target).html('<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                }
            });
        }

        function pagarComPix(target)
        {
            var base = $('#PARCELOW_GATEWAY_PLUGIN_URL').val();
            var order_id = $('#PARCELOW_COD_PED').val();
            var acc = $('#PARCELOW_ACC').val();
            var apihost = $('#PARCELOW_API_HOST').val();
            var order_id_local = $('#PARCELOW_COD_PED_LOCAL').val();
            var url_atual = $('#PARCELOW_URL_ATUAL').val();
            var order_key = $('#WC_PARCELOW_ORDER_KEY').val();
            
            $(target).css('display','block');
            hideMeiosPagto('#boxMeioPagto');
            var dados = "acao=GERARPIX&acc=" + acc + "&order_id=" +order_id;
            dados += "&apihost=" + apihost;
            $.ajax({
                type: "POST",
                url: base + "api/index.php",
                cache: false,
                data: dados,
                dataType: 'text',
                error: function (request, status, error) {
                    console.log(request.responseText);
                    console.log(error);
                },
                success: function (qrc) {
                    //$(target).html('<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                    $(target).html('');
                    var link = '<h5 class="text-center">Clique no link abaixo para gerar o QRCODE.</h5><br><br><p class="text-center"><a target="_blank" href="'+qrc+'">'+qrc+'</a></p><br><br>';
                    link += '<div id="boxQRCODE" style="margin:0 auto;width:300px;height:300px;"></div>';
                    link += '<br><br>';
                    link += '<div id="timer" style="text-align: center; font-size: 2em;font-weight: bold;"></div>';
                    link += '<p style="text-align: center;">Tempo restante para fechar a tela.</p>';
                    $(target).html(link);
                    new QRCode(document.getElementById("boxQRCODE"), {
                        text: qrc,
                        width: 300,
                        height: 300,
                        colorDark : "#000000",
                        colorLight : "#ffffff",
                        correctLevel : QRCode.CorrectLevel.H
                    });

                    var pageURL = $(location).attr("href");
                    pageURL = pageURL.replaceAll("?show_parcelow", "");
                    //window.location.href = pageURL + 'order-received/' + order_id_local + '/?key=' + order_key;
                    pageURL = pageURL + 'order-received/' + order_id_local + '/?key=' + order_key;
                    display = $('#timer');
                    startTimer(60, display, pageURL);

                    //window.open(qrc,'new_win');

                    

                },
                beforeSend: function () {
                    $(target).html('<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                }
            });
        }

        function startTimer(duration, display, pageURL)
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

        function finalizaPedidoCartao(target)
        {
            var base = $('#PARCELOW_GATEWAY_PLUGIN_URL').val();
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


            var dados = "acao=FINALIZAPAGTOCARTAO";
            dados += "&card_name=" + card_name + "&card_numero=" + card_numero + "&card_cvv=" + card_cvv + "&card_data_valid=" + card_data_valid;
            dados += "&card_parcelas=" + card_parcelas + "&card_cep=" + card_cep + "&card_street=" + card_street + "&card_street_number=" + card_street_number;
            dados += "&card_street_supplement=" + card_street_supplement + "&card_street_bairro=" + card_street_bairro + "&card_street_city=" + card_street_city + "&card_street_state=" + card_street_state;
            dados += "&acc=" + acc + "&order_id=" +order_id;
            dados += "&apihost=" + apihost;
            $.ajax({
                type: "post",
                url: base + "api/index.php",
                cache: false,
                data: dados,
                dataType: 'text',
                error: function (request, status, error) {
                    console.log(request.responseText);
                    console.log(error);
                },
                success: function (retorno) {
                    $(target).html('<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                    console.log("FINALIZADO = "+retorno);
                    //$status + ";"+$json->message+";1";
                    var ret = retorno.split(';');
                    if(ret.length > 0){
                        if(parseInt(ret[2]) == 1){
                            $('#boxCartao').css("display", "none");
                            $(target).html('<br><br><div class="alert alert-success"> <i class="bi bi-check-circle"></i> ' + ret[1] + '</div><br><br>');
                            setTimeout(function(){
                                var pageURL = $(location).attr("href");
                                pageURL = pageURL.replaceAll("?show_parcelow", "");
                                window.location.href = pageURL + 'order-received/' + order_id_local + '/?key=' + order_key;
                            
                            }, 6000);
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

        function hideMeiosPagto(target){
            $(target).css('display','none');
        }

        function showMeiosPagto(target){
            $(target).css('display','block');
        }

        function showFormCard(target){
            $(target).css('display','block');
            hideMeiosPagto('#boxMeioPagto');
        }

        function hiddMsg(target, t) {
            setTimeout(function () {
                $(target).fadeOut();
                $(target).html("&nbsp;");
            }, t);
        }

        function showMsg(target) {
            $(target).fadeIn();
            $(target).html("&nbsp;");
        }

        function apenasNumeros(string) {
            var numsStr = string.replace(/[^0-9]/g, '');
            return numsStr;
        }

    });
}


function iniPayParcelow()
{
    jQuery(document).ready(function($)
    {
        var BASE = $('#PARCELOW_GATEWAY_PLUGIN_URL').val();
        var url_atual = $('#PARCELOW_URL_ATUAL').val();
        var ped_status = $('#PARCELOW_STATUS_PED_LOCAL').val();
        var ped_cod = $('#PARCELOW_COD_PED_LOCAL').val();
        var ped_cod_parcelow = $('#PARCELOW_COD_PED').val();
        var acc = $('#PARCELOW_ACC').val();
        var apihost = $('#PARCELOW_API_HOST').val();

        var uri_parc = window.location.href;
        var exist_parc = false;
        if(uri_parc.indexOf("show_parcelow") !== -1){
            exist_parc = true;
        }
        
        ped_cod = parseInt(ped_cod);
        var method = false;
        if($('#payment_method_parcelow').is(":checked")){
            method = true;
        }

        if(exist_parc == true){
            var myModal = new bootstrap.Modal(document.getElementById('mod_gatway_parcelow'), {
                keyboard: false
            });
            myModal.show();
            getQuestions('#boxQuestions', BASE, ped_cod_parcelow, acc, apihost);
        }


        function getQuestions(target, base, order_id, acc, apihost)
        {
            var dados = "acao=SHOWQUETIONS&acc=" + acc + "&order_id=" +order_id + "&apihost=" + apihost;
            $.ajax({
                type: "post",
                url: base + "api/index.php",
                cache: false,
                data: dados,
                dataType: 'text',
                error: function (request, status, error) {
                    console.log(request.responseText);
                    console.log(error);
                },
                success: function (retorno) {
                    $(target).html('');
                    //console.log('QUESTIONS = ' + retorno);
                    //$('#boxMsgOrder').html('&nbsp;');
                    $('#boxQuestions').html(retorno);

                    //carrega parcelas
                    getParcelas('card_parcelas', base, order_id, acc);

                    iniJS();
                },
                beforeSend: function () {
                    $(target).html('<div class="text-center"><div class="spinner-border" style="width: 3rem; height: 3rem;" role="status"><span class="visually-hidden">Loading...</span></div></div>');
                }
            });
        }


        function getParcelas(target, base, order_id, acc){
            var total = $('#WC_PARCELOW_TOTAL').val();
            var dados = "acao=CARREGAPARCELAS&acc=" + acc + "&order_id=" + order_id + '&total=' + total;
            dados += "&apihost=" + apihost;
       
            $.ajax({
                type: "POST",
                url: base + "api/index.php",
                data: dados,
                cache: false,
                dataType: 'text',
                success: function (json) {
                    //console.log("PARCELAS = " + json);
                    var json2 = $.parseJSON(json);
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
        

        $("#cep").blur(function () {
            showMsg('#boxCep');
            var cep = $(this).val();
            if (cep != '') {
                cep = cep.replace("-", "");
                cep = apenasNumeros(cep); //38400-389
                if (cep.length >= 8) {
                    $dados = "acao=CEP&cep=" + cep;
                    $pag = "https://agedi3.com/cep/index.php";
                    $.ajax({
                        type: "post",
                        url: $pag,
                        cache: false,
                        data: $dados,
                        dataType: 'text',
                        success: function (retorno) {
                            //console.log(retorno);
                            $('#boxCep').html('&nbsp;');
                            //[{"logradouro":"Avenida Estr\u00eala do Sul","bairro":"Osvaldo Rezende","cidade":"UBERL\u00c2NDIA","cep":"38400389","uf":"MG"}]
                            var obj = JSON.parse(retorno);
                            var strjson = '';
                            var logradouro = "";
                            var bairro = "";
                            var cidade = "";
                            var uf = "";
                            $('#number').val('');
                            obj.forEach(function (o, index) {
                                logradouro = o.logradouro;
                                bairro = o.bairro;
                                cidade = o.cidade;
                                uf = o.uf;
                            });

                            $('#boxCep').html('&nbsp;');
                            $('#street').val($.trim(logradouro));
                            $('#city').val(cidade);
                            $('#state').val(uf);
                            

                        },
                        beforeSend: function () {
                            $('#boxCep').html('<i class=\"fa fa-refresh fa-spin\"></i>');
                        }
                    });
                } else {
                    $('#boxCep').html('<span style="color:red;">CEP não encontrado!');
                    hiddMsg('#boxCep', 3000);
                }
            }

        });

        $("#card_cep").blur(function () {
            showMsg('#boxCepCard');
            var cep = $(this).val();
            if (cep != '') {
                cep = cep.replace("-", "");
                cep = apenasNumeros(cep); //38400-389
                if (cep.length >= 8) {
                    $dados = "acao=CEP&cep=" + cep;
                    $pag = "https://agedi3.com/cep/index.php";
                    $.ajax({
                        type: "POST",
                        url: $pag,
                        cache: false,
                        data: $dados,
                        dataType: 'text',
                        success: function (retorno) {
                            //console.log(retorno);
                            $('#boxCepCard').html('&nbsp;');
                            //[{"logradouro":"Avenida Estr\u00eala do Sul","bairro":"Osvaldo Rezende","cidade":"UBERL\u00c2NDIA","cep":"38400389","uf":"MG"}]
                            var obj = JSON.parse(retorno);
                            var strjson = '';
                            var logradouro = "";
                            var bairro = "";
                            var cidade = "";
                            var uf = "";
                            $('#number').val('');
                            obj.forEach(function (o, index) {
                                logradouro = o.logradouro;
                                bairro = o.bairro;
                                cidade = o.cidade;
                                uf = o.uf;
                            });

                            $('#boxCepCard').html('&nbsp;');
                            $('#card_street').val($.trim(logradouro));
                            $('#card_street_bairro').val(bairro);
                            $('#card_street_city').val(cidade);
                            $('#card_street_state').val(uf);
                            

                        },
                        beforeSend: function () {
                            $('#boxCepCard').html('<i class=\"fa fa-refresh fa-spin\"></i>');
                        }
                    });
                } else {
                    $('#boxCepCard').html('<span style="color:red;">CEP não encontrado!');
                    hiddMsg('#boxCepCard', 3000);
                }
            }

        });


        









    });
}

setTimeout(function() {
    iniPayParcelow();
}, 2000);





