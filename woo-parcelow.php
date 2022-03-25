<?php
/**
 * Plugin Name: Parcelow
 * Plugin URI: https://wordpress.org/plugins/parcelow/
 * Description: Take credit card payments on your store using Parcelow.
 * Author: Parcelow
 * Author URI: https://parcelow.com/
 * Version: 1.3
 * Requires at least: 5.9
 * Tested up to: 5.9.2
 * WC requires at least: 6.3.1
 * WC tested up to: 6.3.1
 * Text Domain: parcelow
 * Domain Path: /languages
 *
 */

/*
Parcelow is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
Parcelow is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
You should have received a copy of the GNU General Public License
along with Parcelow. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

 /////////////////////////////////////////////////////////////////////////////////////////////////
//CUSTOM STATUS
// Register New Order Statuses

/////////////////////////////////////////////////////////////////
// 0 - Open
function wcppa_register_post_statuses() {
    register_post_status( 'wc-open', array(
        'label'                     => _x( 'Open', 'WooCommerce Order status', 'text_domain' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Open (%s)', 'Open (%s)', 'text_domain' )
    ) );
}
add_filter( 'init', 'wcppa_register_post_statuses' );

// Add New Order Statuses to WooCommerce
function wcppa_add_order_statuses( $order_statuses ) {
    $order_statuses['wc-open'] = _x( 'Open', 'WooCommerce Order status', 'text_domain' );
    return $order_statuses;
}
add_filter( 'wc_order_statuses', 'wcppa_add_order_statuses' );

/////////////////////////////////////////////////////////////////
// 5 - Awaiting Receipt
function wcppa_register_post_statuses_5() {
    register_post_status( 'wc-waiting-receipt', array(
        'label'                     => _x( 'Awaiting Receipt', 'WooCommerce Order status', 'text_domain' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Awaiting Receipt (%s)', 'Awaiting Receipt (%s)', 'text_domain' )
    ) );
}
add_filter( 'init', 'wcppa_register_post_statuses_5' );

function wcppa_add_order_statuses_5( $order_statuses ) {
    $order_statuses['wc-waiting-receipt'] = _x( 'Awaiting Receipt', 'WooCommerce Order status', 'text_domain' );
    return $order_statuses;
}
add_filter( 'wc_order_statuses', 'wcppa_add_order_statuses_5' );

/////////////////////////////////////////////////////////////////
// 6 - waiting-docs
function wcppa_register_post_statuses_6() {
    register_post_status( 'wc-waiting-docs', array(
        'label'                     => _x( 'Awaiting Docs', 'WooCommerce Order status', 'text_domain' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Awaiting Docs (%s)', 'Awaiting Docs (%s)', 'text_domain' )
    ) );
}
add_filter( 'init', 'wcppa_register_post_statuses_6' );

function wcppa_add_order_statuses_6( $order_statuses ) {
    $order_statuses['wc-waiting-docs'] = _x( 'Awaiting Docs', 'WooCommerce Order status', 'text_domain' );
    return $order_statuses;
}
add_filter( 'wc_order_statuses', 'wcppa_add_order_statuses_6' );

/////////////////////////////////////////////////////////////////
// 7 - in-review
function wcppa_register_post_statuses_7() {
    register_post_status( 'wc-in-review', array(
        'label'                     => _x( 'In Review', 'WooCommerce Order status', 'text_domain' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'In Review (%s)', 'In Review (%s)', 'text_domain' )
    ) );
}
add_filter( 'init', 'wcppa_register_post_statuses_7' );

function wcppa_add_order_statuses_7( $order_statuses ) {
    $order_statuses['wc-in-review'] = _x( 'In Review', 'WooCommerce Order status', 'text_domain' );
    return $order_statuses;
}
add_filter( 'wc_order_statuses', 'wcppa_add_order_statuses_7' );

/////////////////////////////////////////////////////////////////
// 8 - wc-in-antifraund
function wcppa_register_post_statuses_8() {
    register_post_status( 'wc-in-antifraund', array(
        'label'                     => _x( 'In Antifraund', 'WooCommerce Order status', 'text_domain' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'In Antifraund (%s)', 'In Antifraund (%s)', 'text_domain' )
    ) );
}
add_filter( 'init', 'wcppa_register_post_statuses_8' );

function wcppa_add_order_statuses_8( $order_statuses ) {
    $order_statuses['wc-in-antifraund'] = _x( 'In Antifraund', 'WooCommerce Order status', 'text_domain' );
    return $order_statuses;
}
add_filter( 'wc_order_statuses', 'wcppa_add_order_statuses_8' );

/////////////////////////////////////////////////////////////////
// 9 - wc-waiting-payment
function wcppa_register_post_statuses_9() {
    register_post_status( 'wc-waiting-payment', array(
        'label'                     => _x( 'Awaiting Payment', 'WooCommerce Order status', 'text_domain' ),
        'public'                    => true,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop( 'Awaiting Payment (%s)', 'Awaiting Payment (%s)', 'text_domain' )
    ) );
}
add_filter( 'init', 'wcppa_register_post_statuses_9' );

function wcppa_add_order_statuses_9( $order_statuses ) {
    $order_statuses['wc-waiting-payment'] = _x( 'Awaiting Payment', 'WooCommerce Order status', 'text_domain' );
    return $order_statuses;
}
add_filter( 'wc_order_statuses', 'wcppa_add_order_statuses_9' );



//CUSTOM STATUS
 /////////////////////////////////////////////////////////////////////////////////////////////////

 
function wcppa_register_paid_by_customer() {
    register_post_status( 'wc-by-customer', array(
            'label' => _x( 'Payment Received', 'Order Status', 'woo-parcelow' ),
            'public' => true,
            'exclude_from_search' => false,
            'show_in_all_admin_list' => true,
            'show_in_admin_status_list' => true,
            'label_count' => _n_noop(
                'Invoiced <span class="count">(%s)</span>',
                'Invoiced <span class="count">(%s)</span>',
                'woo-parcelow' )
        )
    );
}
add_action( 'init', 'wcppa_register_paid_by_customer' );

function wcppa_paid_by_customer_status( $order_statuses ){
    $order_statuses['wc-by-customer'] = _x( 'Payment Received', 'Order Status', 'woo-parcelow' );
    return $order_statuses;
}

add_filter( 'wc_order_statuses', 'wcppa_paid_by_customer_status' );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter('woocommerce_checkout_fields', 'wcppa_custom_override_checkout_fields');

function wcppa_custom_override_checkout_fields($fields)
{
    unset($fields['billing']['billing_address_2']);
    $fields['billing']['billing_company']['placeholder'] = 'Business Name';
    $fields['billing']['billing_company']['label'] = 'Business Name';
    $fields['billing']['billing_first_name']['placeholder'] = 'First Name'; 
    $fields['shipping']['shipping_first_name']['placeholder'] = 'First Name';
    $fields['shipping']['shipping_last_name']['placeholder'] = 'Last Name';
    $fields['shipping']['shipping_company']['placeholder'] = 'Company Name'; 
    $fields['billing']['billing_last_name']['placeholder'] = 'Last Name';
    $fields['billing']['billing_email']['placeholder'] = 'Email Address ';
    $fields['billing']['billing_phone']['placeholder'] = 'Phone ';
    return $fields;
}

add_filter('woocommerce_billing_fields', 'wcppa_custom_woocommerce_billing_fields');

function wcppa_custom_woocommerce_billing_fields($fields)
{

    $fields['billing_cpf'] = array(
        'label' => __('CPF (Brazil) - only number', 'woocommerce'), // Add custom field label
        'placeholder' => _x('Your CPF here', 'placeholder', 'woocommerce'), // Add custom field placeholder
        'required' => false, // if field is required or not
        'clear' => false, // add clear or not
        'type' => 'text', // add field type
        'class' => array('my-css')    // add class name
    );

    return $fields;
}

/*
 * This action hook registers our PHP class as a WooCommerce payment gateway
 */
define('WCPPA_PARCELOW_GATEWAY_PLUGIN_URL', plugin_dir_url(__FILE__));

global $wp;
//$current_url = home_url($_SERVER['REQUEST_URI']);
//http://localhost/wp/wp/?wc-ajax=checkout
//$current_url = explode("?", $current_url);
//define('PARCELOW_URL_ATUAL', $current_url[0]."checkout");


/** Add Valor Aproximado ao lado de cada produto */ 
add_filter( 'woocommerce_get_price_html', 'wcppa_add_approximately_price', 10, 2 );
function wcppa_add_approximately_price( $price_html, $product ) {
    if(class_exists('WOOMULTI_CURRENCY_F_Data')){
        $settingMC= new WOOMULTI_CURRENCY_F_Data();
        $valorReais= $settingMC->get_list_currencies()[ 'BRL' ]['rate'];
        // $settingMC->getcookie( 'wmc_currency_rate' );
        $unit_price= $product->get_price();
        if( $settingMC->get_enable() && !empty($unit_price)  && 'USD' ==  $settingMC->get_current_currency()  ){
            $priceReais= $product->get_price() * $valorReais;
            $price_html = '<span class="amount">'. wc_price( $unit_price ) . '<br/><small>Aprox R$ '. number_format($priceReais, 2, ",", ".") . '</small></span>';
        }
    } 
    return  $price_html;
}

add_filter( 'woocommerce_payment_gateways', 'wcppa_parcelow_add_gateway_class' );

function wcppa_parcelow_add_gateway_class( $gateways ) {
	$gateways[] = 'WCPPA_WC_Parcelow_Gateway'; // your class name is here
	return $gateways;
}
 
function wcppa_showOrdersDetails( $content ) {
	global $wmc_settings;
	global $post;

	return $content;
}

/*
 * The class itself, please note that it is inside plugins_loaded action hook
 */
add_action( 'plugins_loaded', 'wcppa_woocommerce_gateway_parcelow_init' );

function wcppa_carrega_scripts()
{

    wp_enqueue_style('bootstrap', WCPPA_PARCELOW_GATEWAY_PLUGIN_URL  . 'assets/css/bootstrap.min.css');

    wp_enqueue_style('bootstrap_icones', WCPPA_PARCELOW_GATEWAY_PLUGIN_URL  . 'assets/css/bootstrap-icons.min.css');

    wp_enqueue_script('bootstrap.bundle', WCPPA_PARCELOW_GATEWAY_PLUGIN_URL  . 'assets/js/bootstrap.bundle.min.js');
    
    wp_enqueue_script('scriptajax', WCPPA_PARCELOW_GATEWAY_PLUGIN_URL  . 'assets/js/ajax.js', ['jquery'], '1.0', true);

    wp_localize_script(
        'scriptajax',
        'script_ajax',
        array(
            'ajax_url' => admin_url('admin-ajax.php')
        )
    );

}
add_action( 'wp_enqueue_scripts', 'wcppa_carrega_scripts' );

//AJAX
add_action('wp_ajax_wcppa_carrega_ajax', 'wcppa_carrega_ajax');
add_action('wp_ajax_nopriv_wcppa_carrega_ajax', 'wcppa_carrega_ajax');

function wcppa_carrega_ajax()
{
    if(sanitize_text_field($_POST["acao"]) == 'FINALIZAPAGTOCARTAO'){
        $order_id = sanitize_text_field($_POST["order_id"]);
        $access_token = sanitize_text_field($_POST["acc"]);
        $apihost = sanitize_text_field($_POST["apihost"]);

        $card_name = sanitize_text_field($_POST["card_name"]);
        $card_numero = sanitize_text_field($_POST["card_numero"]);
        $card_cvv = sanitize_text_field($_POST["card_cvv"]);
        $card_data_valid = sanitize_text_field($_POST["card_data_valid"]);
        $card_data_valid = explode("/", $card_data_valid);
        $card_parcela = sanitize_text_field($_POST["card_parcelas"]);
        $card_cep = sanitize_text_field($_POST["card_cep"]);
        $card_street = sanitize_text_field($_POST["card_street"]);
        $card_street_number = sanitize_text_field($_POST["card_street_number"]);
        $card_street_supplement = sanitize_text_field($_POST["card_street_supplement"]);
        $card_street_bairro = sanitize_text_field($_POST["card_street_bairro"]);
        $card_street_city = sanitize_text_field($_POST["card_street_city"]);
        $card_street_state = sanitize_text_field($_POST["card_street_state"]);

        $data = array("method" => "credit-card", "installment" => $card_parcela, "card" => array(
            "number" => $card_numero,
            "holder" => $card_name,
            "exp_month" => $card_data_valid[0],
            "exp_year" => $card_data_valid[1],
            "cvv" => $card_cvv,
            "brand" => "visa",
            "address_cep" => $card_cep,
            "address_street" => $card_street,
            "address_number" => $card_street_number,
            "address_complement" => $card_street_supplement,
            "address_neighborhood" => $card_street_bairro,
            "address_city" => $card_street_city,
            "address_state" => $card_street_state
        ));

        $access_token = openssl_decrypt(base64_decode($access_token), "AES-128-ECB", "e4X412AfCJv247");
        $apihost = openssl_decrypt(base64_decode($apihost), "AES-128-ECB", "e4X412AfCJv247");

        $payload = array(
            'method' => 'POST',
            'headers' => array(
                'Authorization' => $access_token,
                'Content-Type' => "application/x-www-form-urlencoded",
                    'Accept' => "application/json"
                ),
            'timeout' => 90,
            'body' => $data
            
        );

        $urlapi = $apihost . "/api/order/".$order_id."/payment";

        $response = wp_remote_post( $urlapi , $payload );
        if (is_wp_error($response)) {
            throw new Exception(__('Há um problema para o gateway de pagamento connectin. Desculpe pela inconveniência.','wc-gateway-nequi'));
        }
    
        if (empty($response['body'])) {
            throw new Exception(__('A resposta de Parcelow.com não obteve nenhum dado.', 'wc-gateway-nequi'));
        }

        $json = wp_remote_retrieve_body( $response );
        
        $json = json_decode($json);
        $status_code = wp_remote_retrieve_response_code($response);
        $result = "";
        if($status_code == 200){
            $result = $status_code . ";".$json->message.";1";
        }
        if($status_code == 400){
            $result = $status_code . ";".$json->message.";2";
        }

        $retorno = [
            "result" => $result
        ];

        echo wp_send_json($retorno);
        
    } else if(sanitize_text_field($_POST["acao"]) == 'GERARPIX'){
        $order_id = sanitize_text_field($_POST["order_id"]);
        $access_token = sanitize_text_field($_POST["acc"]);
        $apihost = sanitize_text_field($_POST["apihost"]);

        $data = array("method" => "pix");

        $access_token = openssl_decrypt(base64_decode($access_token), "AES-128-ECB", "e4X412AfCJv247");
        $apihost = openssl_decrypt(base64_decode($apihost), "AES-128-ECB", "e4X412AfCJv247");

        $payload = array(
            'method' => 'POST',
            'headers' => array(
                'Authorization' => $access_token,
                'Content-Type' => "application/x-www-form-urlencoded",
                    'Accept' => "application/json"
                ),
            'timeout' => 90,
            'body' => $data
            
        );

        $urlapi = $apihost . "/api/order/".$order_id."/payment";

        $response = wp_remote_post( $urlapi , $payload );
        if (is_wp_error($response)) {
            throw new Exception(__('Há um problema para o gateway de pagamento connectin. Desculpe pela inconveniência.','wc-gateway-nequi'));
        }
    
        if (empty($response['body'])) {
            throw new Exception(__('A resposta de Parcelow.com não obteve nenhum dado.', 'wc-gateway-nequi'));
        }

        $json = wp_remote_retrieve_body( $response );
        
        $json = json_decode($json);

        $retorno = [
            "link" => $json->qrcode
        ];

        echo wp_send_json($retorno);

    } else if(sanitize_text_field($_POST["acao"]) == 'RESPONSEQUESTION'){
        
        $order_id = sanitize_text_field($_POST["order_id"]);
        $access_token = sanitize_text_field($_POST["acc"]);
        $apihost = sanitize_text_field($_POST["apihost"]);

        $p1 = sanitize_text_field($_POST["p1"]);
        $r1 = sanitize_text_field($_POST["r1"]);

        $p2 = sanitize_text_field($_POST["p2"]);
        $r2 = sanitize_text_field($_POST["r2"]);

        $data = array('questions' => array(
            array(
                'id' => $p1,
                'answer' => $r1
            ),
            array(
                'id' => $p2,
                'answer' => $r2
            )
        ));

        $access_token = openssl_decrypt(base64_decode($access_token), "AES-128-ECB", "e4X412AfCJv247");
        $apihost = openssl_decrypt(base64_decode($apihost), "AES-128-ECB", "e4X412AfCJv247");

        $payload = array(
            'method' => 'POST',
            'headers' => array(
                'Authorization' => $access_token,
                'Content-Type' => "application/x-www-form-urlencoded",
                    'Accept' => "application/json"
                ),
            'timeout' => 90,
            'body' => $data
            
        );

        $urlapi = $apihost . "/api/order/".$order_id."/questions/answers";

        $response = wp_remote_post( $urlapi , $payload );
        if (is_wp_error($response)) {
            throw new Exception(__('Há um problema para o gateway de pagamento connectin. Desculpe pela inconveniência.','wc-gateway-nequi'));
        }
    
        if (empty($response['body'])) {
            throw new Exception(__('A resposta de Parcelow.com não obteve nenhum dado.', 'wc-gateway-nequi'));
        }

        $json = wp_remote_retrieve_body( $response );
        
        $json = json_decode($json);
        $status = 0;
        if($json->success == true){
            $status = 1;
        }

        $retorno = [
            "status" => $status
        ];

        echo wp_send_json($retorno);

    } else if(sanitize_text_field($_POST["acao"]) == 'WC_PARCELOW_TOTAL'){
        
        $order_id = sanitize_text_field($_POST["order_id"]);
        $access_token = sanitize_text_field($_POST["acc"]);
        $apihost = sanitize_text_field($_POST["apihost"]);
        $total = sanitize_text_field($_POST["total"]);

        $access_token = openssl_decrypt(base64_decode($access_token), "AES-128-ECB", "e4X412AfCJv247");
        $apihost = openssl_decrypt(base64_decode($apihost), "AES-128-ECB", "e4X412AfCJv247");

        $payload = array(
            'method' => 'GET',
            'headers' => array(
                'Authorization' => $access_token,
                'Content-Type' => "application/x-www-form-urlencoded",
                    'Accept' => "application/json"
                ),
            'timeout' => 90
        );
        $urlapi = $apihost . "/api/simulate?amount=" . $total;

        $response = wp_remote_get($urlapi , $payload );
        if (is_wp_error($response)) {
            throw new Exception(__('Há um problema para o gateway de pagamento connectin. Desculpe pela inconveniência.','wc-gateway-nequi'));
        }
    
        if (empty($response['body'])) {
            throw new Exception(__('A resposta de Parcelow.com não obteve nenhum dado.', 'wc-gateway-nequi'));
        }

        $json = json_decode( wp_remote_retrieve_body( $response ) );
        $json = $json->data->creditcard->installments;
        $retorno = [
            "status" => 1,
            "json" => json_encode($json)
        ];
        echo wp_send_json($retorno);

    } else if(sanitize_text_field($_POST["acao"]) == 'SHOWQUETIONS'){ //MOSTRA AS PERGUNTAS
        
        $order_id = sanitize_text_field($_POST["order_id"]);
        $access_token = sanitize_text_field($_POST["acc"]);
        $apihost = sanitize_text_field($_POST["apihost"]);

        $access_token = openssl_decrypt(base64_decode($access_token), "AES-128-ECB", "e4X412AfCJv247");
        $apihost = openssl_decrypt(base64_decode($apihost), "AES-128-ECB", "e4X412AfCJv247");

        $payload = array(
            'method' => 'GET',
            'headers' => array(
                'Authorization' => $access_token,
                'Content-Type' => "application/x-www-form-urlencoded",
                    'Accept' => "application/json"
                ),
            'timeout' => 90
        );
        $urlapi = $apihost . "/api/order/".$order_id."/questions";

        $response = wp_remote_get($urlapi , $payload );
        if (is_wp_error($response)) {
            throw new Exception(__('Há um problema para o gateway de pagamento connectin. Desculpe pela inconveniência.','wc-gateway-nequi'));
        }
    
        if (empty($response['body'])) {
            throw new Exception(__('A resposta de Parcelow.com não obteve nenhum dado.', 'wc-gateway-nequi'));
        }

        $json = json_decode( wp_remote_retrieve_body( $response ) );
        $html = '<h4>Confirmação dados pessoais</h4><br>';
        $id = 0;
        $ant = '';
        $quest = 0;
        foreach($json->questions as $r){
    
            if($r->id != $ant){
                $html .= '<h6>' . $r->question . '</h6>';
                $html .= '<ul class="list-group list-group-flush">';
                $quest++;
            }
    
            foreach($r->answers as $a){
                $html .= '<label class="list-group-item">
                <input class="form-check-input me-1 " type="radio" name="quest_'.$quest.'" value="' . $r->id .";". $a->id . '">
                ' . $a->answer . '
                </label>';
            }
    
            if($r->id != $ant){
                $html .= '</ul>';
            }
    
            $ant = $r->id;
        }
        $html .= '<br><a class="btn btn-warning" id="btn_response_question">Prosseguir</a>';
        $html .= '<div id="boxRespQuests"></div><br><br>';
        $retorno = [
            "status" => 1,
            "texto" => $html
        ];
        echo wp_send_json($retorno);

    } else if(sanitize_text_field($_POST["acao"]) == 'INICIAFRONTPARCELOW'){
        global $wp;
        $urlstual = home_url( $wp->request );

        if (isset(WC()->session)) {
            $apihost = sanitize_text_field(WC()->session->get( 'WC_PARCELOW_API_HOST' ));
            $bearer = sanitize_text_field(WC()->session->get( 'WC_COD_AUT_PARCELOW' ));
            $order_id_parcelow = sanitize_text_field(WC()->session->get( 'WC_COD_PEDIDO_NA_PARCELOW' ));
            $order_id = sanitize_text_field(WC()->session->get( 'WC_COD_PEDIDO_LOCAL' ));
            $order_key = "";
        } else{
            $apihost = "";
            $bearer = "";
            $order_id_parcelow = "";
            $order_id = "";
            $order_key = "";
        }
        
        $order = wc_get_order( $order_id );
        if($order){
            
            $data = $order->get_data();
            $order_status = $data['status'];
            $total = $order->get_total();
            $total = (string) $total;
            $order_key = $order->get_order_key();
            
        } else{
            $order_status = '';
            $total = 0;
        }

        $descrip_method = '<input type="hidden" name="WCPPA_PARCELOW_GATEWAY_PLUGIN_URL" id="WCPPA_PARCELOW_GATEWAY_PLUGIN_URL" value="'.WCPPA_PARCELOW_GATEWAY_PLUGIN_URL.'">';
        $descrip_method .= '<input type="hidden" name="PARCELOW_URL_ATUAL" id="PARCELOW_URL_ATUAL" value="'.$urlstual.'">';
        $descrip_method .= '<input type="hidden" name="PARCELOW_COD_PED_LOCAL" id="PARCELOW_COD_PED_LOCAL" value="'.$order_id.'">';
        $descrip_method .= '<input type="hidden" name="PARCELOW_COD_PED" id="PARCELOW_COD_PED" value="'.$order_id_parcelow.'">';
        $descrip_method .= '<input type="hidden" name="PARCELOW_STATUS_PED_LOCAL" id="PARCELOW_STATUS_PED_LOCAL" value="'.$order_status.'">';
        $descrip_method .= '<input type="hidden" name="PARCELOW_ACC" id="PARCELOW_ACC" value="'.$bearer.'">';
        $descrip_method .= '<input type="hidden" name="PARCELOW_API_HOST" id="PARCELOW_API_HOST" value="'.$apihost.'">';
        $descrip_method .= '<input type="hidden" name="WC_PARCELOW_TOTAL" id="WC_PARCELOW_TOTAL" value="'.$total.'">';
        $descrip_method .= '<input type="hidden" name="WC_PARCELOW_ORDER_KEY" id="WC_PARCELOW_ORDER_KEY" value="'.$order_key.'">';

        $descrip_method .= '<!-- Modal -->
        <div class="modal fade" id="mod_gatway_parcelow" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="mod_gatway_parcelow" aria-hidden="true">
            <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom: none;">
                <h5 class="modal-title" id="mod_gatway_parcelow"><img src="' . WCPPA_PARCELOW_GATEWAY_PLUGIN_URL . 'assets/imgs/logo-parcelow.png"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div style="width:100%;height:auto;">
        
                    <div id="boxMsgOrder"></div>
        
                    <div id="boxQuestions"></div>
        
                    <div id="boxMeioPagto" style="display:none">

                        <div class="row">

                            <div class="col-md-12 text-center">
                                <h4>Escolha um meio de pagamento</h4><br><br>
                            </div>
        
                            <div class="col-md-6 text-center">
                                <img src="' . WCPPA_PARCELOW_GATEWAY_PLUGIN_URL . 'assets/imgs/cartao.png" style="cursor:pointer;width:206px;" id="btn_show_form_card">
                            </div>
        
                            <div class="col-md-6 text-center">
                                <img src="' . WCPPA_PARCELOW_GATEWAY_PLUGIN_URL . 'assets/imgs/pix.png" style="cursor:pointer;width:206px;" id="btn_show_pix">
                            </div>

                        </div>

                    </div>
        
                    <div id="boxPix" style="display:none"></div>
        
                    <div id="boxCartao" style="display:none">
        
                        <div class="row">
                        
                            <div class="col-md-12">
                                <h3>Enter the Card data</h3>
                            </div>
        
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="card_name">Name printed on Card <span style="color:red;">*</span></lablel>
                                    <input type="text" class="form-control" placeholder="" name="card_name" id="card_name" value="' . get_user_meta( get_current_user_id(), 'billing_first_name', true ) .' ' . get_user_meta( get_current_user_id(), 'billing_last_name', true ) .'">
                                </div>
                            </div>
        
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="card_numero">Card number <span style="color:red;">*</span></lablel>
                                    <input type="text" class="form-control" placeholder="" name="card_numero" id="card_numero" maxlength="16" value="">
                                </div>
                            </div>
        
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="card_cvv">CVV <span style="color:red;">*</span></lablel>
                                    <input type="text" class="form-control" placeholder="" name="card_cvv" id="card_cvv" maxlength="4" value="">
                                </div>
                            </div>
        
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="card_data_valid">Expiration date <span style="color:red;">*</span></lablel>
                                    <input type="text" class="form-control" placeholder="MM/YYYY" name="card_data_valid" id="card_data_valid" value="">
                                </div>
                            </div>
                            
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="card_parcelas">Select the number of installments <span style="color:red;">*</span></lablel>
                                    <select class="form-control" name="card_parcelas" id="card_parcelas">
        
                                    </select>
                                </div>
                            </div>
        
                            <div class="col-md-12">
                                <hr>
                            </div>
        
                            <div class="col-md-12">
                                <h6>Card billing address</h6>
                                <br>
                            </div>
        
                            <div class="col-md-12">
                                <div class="form-group">
                                    <lablel for="card_cep">Postcode / ZIP <span style="color:red;">*</span> <span id="boxCepCard"></span></lablel>
                                    <input type="text" class="form-control" placeholder="" name="card_cep" id="card_cep" value="' . get_user_meta( get_current_user_id(), 'billing_postcode', true ) . '">
                                </div>
                            </div>
        
                            <div class="col-md-12">
                                <div class="form-group">
                                    <lablel for="card_street">Street <span style="color:red;">*</span></lablel>
                                    <input type="text" class="form-control" placeholder="" name="card_street" id="card_street" value="' . get_user_meta( get_current_user_id(), 'billing_address_1', true ) . '">
                                </div>
                            </div>
        
                            <div class="col-md-12">
                                <div class="form-group">
                                    <lablel for="card_street_number">Number <span style="color:red;">*</span></lablel>
                                    <input type="text" class="form-control" placeholder="" name="card_street_number" id="card_street_number" value="' . get_user_meta( get_current_user_id(), 'address_number', true ) . '">
                                </div>
                            </div>
        
                            <div class="col-md-12">
                                <div class="form-group">
                                    <lablel for="card_street_supplement">Supplement </lablel>
                                    <input type="text" class="form-control" placeholder="" name="card_street_supplement" id="card_street_supplement">
                                </div>
                            </div>
        
                            <div class="col-md-12">
                                <div class="form-group">
                                    <lablel for="card_street_bairro">Neighborhood of billing address <span style="color:red;">*</span></lablel>
                                    <input type="text" class="form-control" placeholder="" name="card_street_bairro" id="card_street_bairro" value="' . get_user_meta( get_current_user_id(), 'billing_city', true ) . '">
                                </div>
                            </div>
        
                            <div class="col-md-12">
                                <div class="form-group">
                                    <lablel for="card_street_city">City <span style="color:red;">*</span></lablel>
                                    <input type="text" class="form-control" placeholder="" name="card_street_city" id="card_street_city" value="' . get_user_meta( get_current_user_id(), 'billing_city', true ) . '">
                                </div>
                            </div>
        
                            <div class="col-md-12">
                                <div class="form-group">
                                    <lablel for="card_street_state">State <span style="color:red;">*</span></lablel>
                                    <input type="text" class="form-control" placeholder="" name="card_street_state" id="card_street_state" value="' . get_user_meta( get_current_user_id(), 'billing_state', true ) . '">
                                </div>
                            </div>
        
                            <div class="col-md-12">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" name="li_termo" id="li_termo">
                                    <label class="form-check-label" for="li_termo">
                                        <span>Li e aceito os <a href="https://parcelow.com/terms-of-use-and-privacy" target="_blank" class="color-primary">termos de uso</a> e 
                                        <a href="https://parcelow.com/privacy-policies" target="_blank" class="color-primary">política de privacidade</a> da plataforma Parcelow.</span>
        
                                    </label>
                                </div>
        
                            </div>
        
                            
        
                            <div class="col-md-12">
                                <div class="form-group">
                                    <a class="btn btn-warning" id="btn_finaliza_com_cartao">Finalizar pagamento</a>
                                </div>
                            </div>
        

        
                        </div>
        
        
                    </div>

                    <br style="clear:both;">

                    <div id="boxMsgFinalizaCard"></div>

                    <br style="clear:both;">

                </div>
            </div>


            </div>
            </div>
        </div>';

        

        $urlcheck = wc_get_checkout_url();
        $uri_atual = wc_get_page_permalink(get_the_ID());
        $uripag = sanitize_text_field($_POST["uripag"]);
        $status = 0;

        if(strpos($uripag, "show_parcelow")){
            $status = 1;
        }
        
        $retorno = [
            "status" => $status,
            "texto" => $descrip_method,
            "uri_atual" => $uripag,
            "urlcheck" => $urlcheck
        ];
        echo wp_send_json($retorno);
    }


}




//wc-checkout 	
function wcppa_woocommerce_gateway_parcelow_init() {

    if ( ! class_exists( 'WooCommerce' ) ) {
	//	add_action( 'admin_notices', 'woocommerce_stripe_missing_wc_notice' );
		return;
	}
	
	class WCPPA_WC_Parcelow_Gateway extends WC_Payment_Gateway {
		
 		/**
 		 * Class constructor, more about it in Step 3
 		 */
 		public function __construct() 
        {

            
            

            $this->id = 'parcelow'; // payment gateway plugin ID
            //$this->icon = WCPPA_PARCELOW_GATEWAY_PLUGIN_URL.'assets/imgs/gateway_parcelow_img.jpg';
            $this->title = $this->get_option('metodo_pagto_parcelow');
            $this->has_fields = false; // in case you need a custom credit card form
            $this->method_title = 'Parcelow Transparent Gateway';
            $this->method_description = 'Pague sem taxas escondidas e com segurança'; // will be displayed on the options page

            $this->supports = array(
                'products'
            );

            /*
            WEBHOOK
            */
            add_action( 'woocommerce_api_parcelow_webhook', array( $this, 'webhook'));
            
            // Method with all the options fields
            $this->init_form_fields();

            // Load the settings.
            $this->init_settings();

            $this->enabled = $this->get_option('enabled');
            $ambiente = $this->get_option('ambiente');
            $this->ambiente = $ambiente;

            $secret_key_sandbox = $this->get_option('secret_key_sandbox');
            $client_id_sandbox = $this->get_option('client_id_sandbox');

            $secret_key_producao = $this->get_option('secret_key_producao');
            $client_id_producao = $this->get_option('client_id_producao');

            if($ambiente == '0'){
                $this->secret_key = $secret_key_sandbox;
                $this->client_id = $client_id_sandbox;
                $this->host = $this->get_option('host_sandbox');
            } else{
                $this->secret_key = $secret_key_producao;
                $this->client_id = $client_id_producao;
                $this->host = $this->get_option('host_producao');
            }

            //$this->title .= "<br>" . $this->host;
            // Save settings
            
            
  			if ( is_admin() ) {
                // Versions over 2.0
                // Save our administration options. Since we are not going to be doing anything special
                // we have not defined 'process_admin_options' in this class so the method in the parent
                // class will be used instead
                add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
				add_action( 'woocommerce_webhook_process_delivery', 'wcppa_my_custom_wc_webhook_process_delivery', 10, 2 );
				/*https://woocommerce.wordpress.com/2017/12/08/wc-3-3-new-webhooks-crud/ */
			}

            if(class_exists('WOOMULTI_CURRENCY_F_Data')){
                $settingMC= new WOOMULTI_CURRENCY_F_Data();
                $this->moedaSelecionada= $settingMC->get_current_currency(); // moeda selecionada no produto
                $this->valorReais= $settingMC->get_list_currencies()[ 'BRL' ]['rate'];
                //$settingMC->getcookie( 'wmc_currency_rate' );
                $this->enableMultiCurrency=  $settingMC->get_enable();              
            } 

            $this->writehtml();

        }

        public function writehtml()
        {
            //echo "<div id='boxHTMLModalParcelow'></div>";
        }


        public function wcppa_secured_encrypt($plaintext)
        {
            $password = "e4X412AfCJv247";
            return base64_encode(openssl_encrypt($plaintext,"AES-128-ECB",$password));
        }

		function wcppa_installmentPaymentsSimulator($idOrder) {
            $order = wc_get_order();

            print_r('testado');
		}

		function wcppa_my_custom_wc_webhook_process_delivery( $webhook, $arg ) {
				print_r($arg);
		}

		/**
 		 * Plugin options, we deal with it in Step 3 too
 		 */
 		public function init_form_fields(){
            global $wp; 
             $this->form_fields = array(
                'enabled' => array(
                    'title'       => 'Enable/Disable',
                    'label'       => 'Enable Parcelow Gateway',
                    'type'        => 'checkbox',
                    'description' => '',
                    'default'     => 'yes'
                ),
                'ambiente' =>  array(
                    'title'       => 'Ambiente ativo atual',
                    'label'       => __('Tipos de ambiente', 'woocommerce'),
                    'placeholder' => _x('', 'placeholder', 'woocommerce'),
                    'required'    => false,
                    'clear'       => false,
                    'type'        => 'select',
                    'options'     => array(
                        0 => __('SANDBOX', 'woocommerce' ),
                        1 => __('PRODUÇÃO', 'woocommerce' )
                        )
                    ),

                'host_sandbox' => array(
                    'title'       => 'Host sandbox (teste)',
                    'type'        => 'text',
                    'default'     => 'https://sandbox.parcelow.com',
                    'description' => 'Host de testes',
                ),
                'host_producao' => array(
                    'title'       => 'Host produção',
                    'type'        => 'text',
                    'default'     => 'https://app.parcelow.com',
                    'description' => 'Host de produção',
                ),

				'client_id_sandbox' => array(
                    'title'       => 'Client id sandbox',
                    'type'        => 'text'
                ),
                'secret_key_sandbox' => array(
                    'title'       => 'Secret key sandbox',
                    'type'        => 'text'
                ),


				'client_id_producao' => array(
                    'title'       => 'Client id produção',
                    'type'        => 'text'
                ),
                'secret_key_producao' => array(
                    'title'       => 'Secret key produção',
                    'type'        => 'text'
                ),

                'metodo_pagto_parcelow' => array(
                    'title'       => 'Descrição forma de pagamento',
                    'type'        => 'textarea',
                    'description' => 'Esta descrição será o nome do método de pagamento no checkout',
                    'default'     => 'Parcelow - Pague em até 12x em reais.',
                ),

                'webHook' => array(
                    'title'       => 'WebHook',
                    'type'        => 'textarea',
                    'description' => 'Link que receberá o status dos pedidos da API',
                    'default'     => home_url( $wp->request ).'/wc-api/parcelow_webhook',
                ),
            );
         }

		/**
		 * You will need it if you want your custom credit card form, Step 4 is about it
		 */
		public function payment_fields() {
            /**
             * billing_first_name, billing_last_name, billing_company, billing_address_1, billing_address_2, billing_city, billing_state, billing_postcode, billing_country, billing_email, billing_phone, shipping_first_name, shipping_last_name, shipping_company, shipping_address_1, shipping_address_2, shipping_city, shipping_state, shipping_postcode, shipping_country, customer_ip_address
             */           
        }


 
		/*
		 * Custom CSS and JS, in most cases required only when you decided to go with a custom credit card form
		 */
	 	public function payment_scripts() { 
	 	}
  
		/*
 		 * Fields validation, more in Step 5
		 */
		public function validate_fields() {

            return true;
		}
       
        public function calcMultiCurrencyBRLtoUSD( $vlr ) {
            if($this->enableMultiCurrency  && 'BRL' == $this->moedaSelecionada ){
                return $vlr / $this->valorReais;
            }
            return $vlr;
        }
		
		function oauthAccesssToken(){
		    
			$reqJson = array('client_id' => $this->client_id,
							  'client_secret' => $this->secret_key,
							  'grant_type' => "client_credentials");
			$payload = array(
					'method' => 'POST',
					'headers' => array('Content-Type' => "application/x-www-form-urlencoded",
									   'Accept' => "application/json",
									   'X-Requested-With' => "XMLHttpRequest"
								 ),
					'body' =>  $reqJson,
					'timeout' => 90
				);  
			//print_r(wp_json_encode($payload));
			$response = wp_remote_post( $this->host . '/oauth/token', $payload );
			$body = json_decode( wp_remote_retrieve_body( $response ), true );
			
			//print_r($body['access_token']);
			
			return $body['access_token'];
		}
 
		/*
		 * We're processing the payments here, everything about it is in Step 5
		 */
		public function process_payment( $order_id )
        {

			global $wp;
			$token = $this->oauthAccesssToken();
			 
            // we need it to get any order detailes
            $order = wc_get_order( $order_id );
            //$order->update_status('aberto', sprintf( __( 'Pedido criado na parcelow', 'woocommerce-gateway-parcelow' ) ) );

            /* Array with parameters for API interaction */
            $billing_address_2 = sanitize_text_field($_POST['billing_address_2']);
            $args = array(
                'redirect[success]' => $this->get_return_url( $order ), 
                'redirect[failed]'  => home_url( $wp->request ) . '/carrinho', 
                'client[email]' => sanitize_email($_POST['billing_email']),
                'client[name]'  => sanitize_text_field($_POST['billing_first_name']) . ' ' . sanitize_text_field($_POST['billing_last_name']),
                'client[cep]'   => sanitize_text_field($_POST['billing_postcode']),
                'client[phone]' => sanitize_text_field($_POST['billing_phone']),
				'client[address_street]' => sanitize_text_field($_POST['billing_address_1']),
				'client[address_complement]' => (!isset($billing_address_2) ? '' : $billing_address_2),
				'client[address_number]' => (!isset($billing_address_2) ? '' : $billing_address_2),
				'client[address_neighborhood]' => sanitize_text_field($_POST['billing_city']),
				'client[address_city]' => sanitize_text_field($_POST['billing_city']),
				'client[address_state]' => sanitize_text_field($_POST['billing_state']),
                'client[cpf]' => sanitize_text_field($_POST['billing_cpf']),
				'shipping[amount]' => $order->get_total_shipping() * 100,
				'reference' => $this->wcppa_geraCODRandNumber(6) . "_" . $order_id
            );

			$i=0; 
         
            // Get and Loop Over Order Items
            foreach ($order->get_items() as $item_id => $item) {
            
                $product_id = $item->get_product_id();
                $variation_id = $item->get_variation_id();
                $product = $item->get_product();
                $name = $item->get_name();
                $quantity = $item->get_quantity();
            
                $price = ($order->get_item_subtotal($item,false)) * 100;
                $price= $this->calcMultiCurrencyBRLtoUSD($price); // BRL change to USD
                $item_name= $name;
   
                $args= array_merge($args, array('items['.$i.'][description]' => $item_name,
                'items['.$i.'][quantity]' => $quantity,
                'items['.$i.'][amount]' => $price)); 
	
                $i++;
            }
            
            $item_name = "TAXES";
            $quantity = 1;
            $taxesAmount = (0 + $order->get_total_tax())*100;
            $taxesAmount= $this->calcMultiCurrencyBRLtoUSD($taxesAmount); //BRL change to USD

            if ($taxesAmount > 0) {
                
                $args= array_merge($args, array('items['.$i.'][description]' => $item_name,
                'items['.$i.'][quantity]' => $quantity,
                'items['.$i.'][amount]' =>  $this->calcMultiCurrencyBRLtoUSD(  ($order->get_total_tax()*100) ) )); //BRL change to USD
                
            }
            
            $couponCode = "COUPON";
            $discount = (0 + WC()->cart->get_discount_total())*100;
            $discount = $this->calcMultiCurrencyBRLtoUSD($discount);// BRL change to USD

            if($discount > 0 ) {
                $args= array_merge($args, array('coupon[code]' => $couponCode,
                'coupon[value]' => $discount,
                'coupon[issuer]' => "merchant_api"));
            }
            
            //print_r($args);

            $existOrder = $this->wcppa_checkExistOrderNaParcelow($order_id, $token);

            if($existOrder == false)
            {
                /*  Your API interaction could be built with wp_remote_post() */
                $payload = array(
                    'method' => 'POST',
                    'headers' => array(
                        'Authorization' => "Bearer ". $token,
                        'Content-Type' => "application/x-www-form-urlencoded",
                            'Accept' => "application/json"
                        ),
                    'body' =>  $args,
                    'timeout' => 90
                );
                $urlapi = $this->host . '/api/orders/brl';
                
                $response = wp_remote_post(  $urlapi, $payload );
                if (is_wp_error($response)) {
                    throw new Exception(__('There is issue for connectin payment gateway. Sorry for the inconvenience.',
                        'wc-gateway-nequi'));
                }
            
                if (empty($response['body'])) {
                    throw new Exception(__('Parcelow.com\'s Response was not get any data.', 'wc-gateway-nequi'));
                }

                $body = json_decode( wp_remote_retrieve_body( $response ), true );
                //var_dump($body);

                if ( $body['success'] == true ) {
                    
                    $order->update_status('wc-pending', __( 'Awaiting  payment', 'woocommerce' ));

                    $data = $body['data'];
                    $total = (string) number_format($body['total'],2,",",".");
                    $total = str_replace(",","",$total);
                    $total = str_replace(".","",$total);
                    $bearer = $this->wcppa_secured_encrypt("Bearer ". $token);
                    $host = $this->wcppa_secured_encrypt($this->host);
                    WC()->session->set('WC_COD_PEDIDO_NA_PARCELOW', $data['order_id']);
                    WC()->session->set('WC_COD_PEDIDO_LOCAL', $order_id);
                    WC()->session->set('WC_COD_AUT_PARCELOW', $bearer);
                    WC()->session->set('WC_PARCELOW_API_HOST', $host);

                    //echo $data['order_id'];
                    // $data['order_id'];
                    // $data['url_checkout'];
                    
                    //echo $data;
                    /*return [
                        'result' => 'success',
                        'redirect' => urldecode( $data['url_checkout'] ),
                    ];*/
                    return [
                        'result' => 'success',
                        'redirect' => "?show_parcelow"
                    ];
                } else {
                    wc_add_notice(  'Please try again.', 'error' );
                    print_r( $response );
                    return;
                }
            } else{
                return [
                    'result' => 'success',
                    'redirect' => "?show_parcelow"
                ];
            }

	 	}

        public function wcppa_geraCODRandNumber($n)
        {
            return "WC".strtoupper( substr(uniqid(mt_rand()), 0, $n) );
        }

        public function wcppa_checkExistOrderNaParcelow($order_id, $token)
        {
            $payload = array(
                'method' => 'GET',
                'headers' => array(
                    'Authorization' => "Bearer ". $token,
                    'Content-Type' => "application/x-www-form-urlencoded",
                        'Accept' => "application/json"
                    ),
                'timeout' => 90
            );
            $urlapi = $this->host . '/api/order/' . $order_id;

            $response = wp_remote_get($urlapi , $payload );
            if (is_wp_error($response)) {
                throw new Exception(__('There is issue for connectin payment gateway. Sorry for the inconvenience.',
                    'wc-gateway-nequi'));
            }
        
            if (empty($response['body'])) {
                throw new Exception(__('Parcelow.com\'s Response was not get any data.', 'wc-gateway-nequi'));
            }

            $json = json_decode( wp_remote_retrieve_body( $response ) );
            $order_id_parcelow = $json->data->reference;
            $pos = strpos($order_id_parcelow, '_');
            if ($pos !== true) {
                return false;
            } else {
                $or = explode('_', $order_id_parcelow);
                if(isset($or[1])) {
                    if($or[1] > 0 && !is_null($or[1])){
                        return true;
                    } else{
                        return false;
                    }
                } else{
                    return false;
                }
            }

        }
	 
		/*
		 * In case you need a webhook, like PayPal IPN etc
		 */
		public function webhook() {

            function remove_change_comment($id, $comment) {
                if( strpos($comment->comment_content, 'Status do pedido alterado de') !== false ) {
                    wp_delete_comment( $id );
                }
            }

			$raw_post = file_get_contents("php://input");
			
			//print_r($raw_post); 
            
            /*
            error_log($raw_post, 3, $_SERVER["DOCUMENT_ROOT"] . "/log/api.txt");
            $arquivo = $_SERVER["DOCUMENT_ROOT"] . "/log/api.log";
            $fp = fopen($arquivo, "a+");
            fwrite($fp, $raw_post);
            fclose($fp);
            */

			$decoded = json_decode( $raw_post );
            
            //WC100686_129
            $num_pedido = explode("_", $decoded->order->reference);
            $num_pedido = (int) trim($num_pedido[1]);
			$order = wc_get_order( $num_pedido );
			
            /*
            Quando o usuário acessa a tela de pagamento, e informa seus dados pessoais, a order é CONFIRMADA.
            */
            /*if ($decoded->order->status == 0){ //open
                $order->set_status('aberto');
                $order->add_order_note('Order criado', true );
                add_action('wp_insert_comment', 'remove_change_comment', 10, 2);
                $order->save();
                return;
			}  */
            
            /*
            Quando o usuário acessa a tela de pagamento, e informa seus dados pessoais, a order é CONFIRMADA.
            */
            if ($decoded->order->status == 1){ //confirmed
				$order->update_status( 'wc-on-hold');
				return;
			}

            /*
            Quando o usuário realiza o pagamento, e ele é capturado pela Cielo.
            Ou quando o ADMIN, assincronamente, marca a order como pago.
            */
			if ($decoded->order->status == 2 ){ //Order paid
                $order->set_status('by-customer');
                $order->add_order_note('Payment Received', true );
                add_action('wp_insert_comment', 'remove_change_comment', 10, 2);
                $order->save();
                return;
			}

            /*
            Quando a order é cancelada pela Parcelow, ou pelo Partner.
            */
			
			if ($decoded->order->status == 3 ){ //cancelled
				$order->update_status('wc-cancelled', sprintf( __( 'Cancelled', 'woocommerce-gateway-parcelow' ) ) );
				return;
			}
            
            /*
            Quando a order é marcada como DECLINED pela Parcelow.
            Ou quando a Cielo retorna código não reversível (sem possibilidade de nova tentativa).
            */
			if ($decoded->order->status == 4){ // Declined 
				$order->update_status('wc-failed', sprintf( __( 'Failed', 'woocommerce-gateway-parcelow' ) ) );
				return;
			}

            /*
            Quando a order é feita por TED ou PIX, fica nesse status até que a Parcelow analise e marque como pago.
            */
            if ($decoded->order->status == 5){
				$order->update_status('wp-waiting-receipt', sprintf( __( 'Awaiting Receipt', 'woocommerce-gateway-parcelow' ) ) );
				return;
			}

            /*
            Quando a order possui valor maior que USD $ 3,000.00, após a confirmação de pagamento, entra nesse status 
            até que a Parcelow confirme documentos pessoais enviados pelo comprador.
            */
            if ($decoded->order->status == 6){
                $order->update_status('wc-waiting-docs', sprintf( __( 'Awaiting Docs', 'woocommerce-gateway-parcelow' ) ) );
				return;
			}

            /*
            Quando a order possui suspeita de fraude no cartão de crédito, devido ao nome do titular do cartão ser 
            diferente do nome do comprador, até que  a Parcelow decida marcar como PAID ou DECLINED.
            */
            if ($decoded->order->status == 7){
                $order->update_status('wc-in-review', sprintf( __( 'In Review', 'woocommerce-gateway-parcelow' ) ) );
				return;
			}

            /*
            Quando a Konduto (antifraude) marca a order como REVIEW, até que a Parcelow decida marcar como PAID ou DECLINED.
            */
            if ($decoded->order->status == 8){
                $order->update_status('wc-in-antifraund', sprintf( __( 'In Antifraund', 'woocommerce-gateway-parcelow' ) ) );
				return;
			}

            /*
            Quando a Cielo não autoriza o pagamento, fica nesse status Aguardando Pagamento, até que o comprador faça uma nova tentativa de pagamento.
            */
            if ($decoded->order->status == 9){
                $order->update_status('wc-waiting-payment', sprintf( __( 'Awaiting Payment', 'woocommerce-gateway-parcelow' ) ) );
				return;
			}

			$order->add_order_note('On Hold', true );
			return; 
	 	}
 	}
}

