<?php
/**
 * Plugin Name: Parcelow Transparent Payment Gateway 
 * Plugin URI: https://wordpress.org/plugins/woocommerce-parcelow-transparent-pay/
 * Description: Take credit card payments on your store using Parcelow.
 * Author: Parcelow
 * Author URI: https://parcelow.com/
 * Version: 1.0.1
 * Requires at least: 1.0.0
 * Tested up to: 1.0.1
 * WC requires at least: 6.1.0
 * WC tested up to: 6.1.0
 * Text Domain: woocommerce-parcelow-transparent-pay
 * Domain Path: /languages
 *
 */

 
function register_paid_by_customer() {
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
add_action( 'init', 'register_paid_by_customer' );

function paid_by_customer_status( $order_statuses ){
    $order_statuses['wc-by-customer'] = _x( 'Payment Received', 'Order Status', 'woo-parcelow' );
    return $order_statuses;
}

add_filter( 'wc_order_statuses', 'paid_by_customer_status' );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_filter('woocommerce_checkout_fields', 'custom_override_checkout_fields');
function custom_override_checkout_fields($fields)
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

add_filter('woocommerce_billing_fields', 'custom_woocommerce_billing_fields');

function custom_woocommerce_billing_fields($fields)
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
define('PARCELOW_GATEWAY_PLUGIN_URL', plugin_dir_url(__FILE__));

global $wp;
//$current_url = home_url($_SERVER['REQUEST_URI']);
//http://localhost/wp/wp/?wc-ajax=checkout
//$current_url = explode("?", $current_url);
//define('PARCELOW_URL_ATUAL', $current_url[0]."checkout");


/** Add Valor Aproximado ao lado de cada produto */ 
add_filter( 'woocommerce_get_price_html', 'add_approximately_price', 10, 2 );
function add_approximately_price( $price_html, $product ) {
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

add_filter( 'woocommerce_payment_gateways', 'parcelow_add_gateway_class' );

function parcelow_add_gateway_class( $gateways ) {
	$gateways[] = 'WC_Parcelow_Gateway'; // your class name is here
	return $gateways;
}
 
function showOrdersDetails( $content ) {
	global $wmc_settings;
	global $post;

	return $content;
}

/*
 * The class itself, please note that it is inside plugins_loaded action hook
 */
add_action( 'plugins_loaded', 'woocommerce_gateway_parcelow_init' );

function carrega_scripts() {
    wp_enqueue_style('bootstrap', PARCELOW_GATEWAY_PLUGIN_URL  . 'assets/css/bootstrap.min.css');
    wp_enqueue_style('bootstrap_icones', PARCELOW_GATEWAY_PLUGIN_URL  . 'assets/css/bootstrap-icons.min.css');

    
    wp_enqueue_script('jquery.min', PARCELOW_GATEWAY_PLUGIN_URL  . 'assets/js/jquery.min.js');
    wp_enqueue_script('bootstrap.bundle', PARCELOW_GATEWAY_PLUGIN_URL  . 'assets/js/bootstrap.bundle.min.js');
	wp_enqueue_script('frontend-custom', PARCELOW_GATEWAY_PLUGIN_URL  . 'assets/js/frontend-custom.js');
    wp_enqueue_script('qrcode', PARCELOW_GATEWAY_PLUGIN_URL  . 'assets/js/qrcode.min.js');

}
add_action( 'wp_enqueue_scripts', 'carrega_scripts' );

//wc-checkout 	
function woocommerce_gateway_parcelow_init() {

    if ( ! class_exists( 'WooCommerce' ) ) {
	//	add_action( 'admin_notices', 'woocommerce_stripe_missing_wc_notice' );
		return;
	}
	
	class WC_Parcelow_Gateway extends WC_Payment_Gateway {
		
 		/**
 		 * Class constructor, more about it in Step 3
 		 */
 		public function __construct() 
        {
            global $wp;
            $urlstual = home_url( $wp->request );

            if (isset(WC()->session)) {
                $apihost = WC()->session->get( 'WC_PARCELOW_API_HOST' );
                $bearer = WC()->session->get( 'WC_COD_AUT_PARCELOW' );
                $order_id_parcelow = WC()->session->get( 'WC_COD_PEDIDO_NA_PARCELOW' );
                $order_id = WC()->session->get( 'WC_COD_PEDIDO_LOCAL' );
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



            $descrip_method = '<input type="hidden" name="PARCELOW_GATEWAY_PLUGIN_URL" id="PARCELOW_GATEWAY_PLUGIN_URL" value="'.PARCELOW_GATEWAY_PLUGIN_URL.'">';
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
                    <h5 class="modal-title" id="mod_gatway_parcelow"><img src="' . PARCELOW_GATEWAY_PLUGIN_URL . 'assets/imgs/logo-parcelow.png"></h5>
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
                                    <img src="' . PARCELOW_GATEWAY_PLUGIN_URL . 'assets/imgs/cartao.png" style="cursor:pointer;width:206px;" id="btn_show_form_card">
                                </div>
            
                                <div class="col-md-6 text-center">
                                    <img src="' . PARCELOW_GATEWAY_PLUGIN_URL . 'assets/imgs/pix.png" style="cursor:pointer;width:206px;" id="btn_show_pix">
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
                                            <a href="https://parcelow.com/privacy-policies" target="_blank" class="color-primary">política de privacidade</a> da plataforma ParcelowSandbox.</span>
            
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

            add_action( 'woocommerce_api_parcelow_callback', array( $this, 'callback_handler' ) );

            $urlcheck = $this->wc_get_checkout_url();
            $uri_atual = get_permalink();
            if($uri_atual == $urlcheck){
                echo $descrip_method;
            }



            $this->id = 'parcelow'; // payment gateway plugin ID
            //$this->icon = PARCELOW_GATEWAY_PLUGIN_URL.'assets/imgs/gateway_parcelow_img.jpg';
            $this->title = $this->get_option('metodo_pagto_parcelow');
            $this->has_fields = false; // in case you need a custom credit card form
            $this->method_title = 'Parcelow Transparent Gateway';
            $this->method_description = 'Pague sem taxas escondidas e com segurança'; // will be displayed on the options page

            $this->supports = array(
                'products'
            );

            
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
				add_action( 'woocommerce_webhook_process_delivery', 'my_custom_wc_webhook_process_delivery', 10, 2 );
				/*https://woocommerce.wordpress.com/2017/12/08/wc-3-3-new-webhooks-crud/ */
			}

            if(class_exists('WOOMULTI_CURRENCY_F_Data')){
                $settingMC= new WOOMULTI_CURRENCY_F_Data();
                $this->moedaSelecionada= $settingMC->get_current_currency(); // moeda selecionada no produto
                $this->valorReais= $settingMC->get_list_currencies()[ 'BRL' ]['rate'];
                //$settingMC->getcookie( 'wmc_currency_rate' );
                $this->enableMultiCurrency=  $settingMC->get_enable();              
            } 
        }

        public function wc_get_checkout_url()
        {
            $checkout_url = wc_get_page_permalink( 'checkout' );
            if ( $checkout_url ) {
              // Force SSL if needed.
              if ( is_ssl() || 'yes' === get_option( 'woocommerce_force_ssl_checkout' ) ) {
                $checkout_url = str_replace( 'http:', 'https:', $checkout_url );
              }
            }
          
            return apply_filters( 'woocommerce_get_checkout_url', $checkout_url );
          }

        public function secured_encrypt($plaintext)
        {
            $password = "e4X412AfCJv247";
            return base64_encode(openssl_encrypt($plaintext,"AES-128-ECB",$password));
        }
    
        public static function get_order( $order_id = false ) {
            $order_id = self::get_order_id( $order_id );
        
            if ( ! $order_id ) {
              return false;
            }
        
            $order_type      = WC_Data_Store::load( 'order' )->get_order_type( $order_id );
            $order_type_data = wc_get_order_type( $order_type );
            if ( $order_type_data ) {
              $classname = $order_type_data['class_name'];
            } else {
              $classname = false;
            }
        
            // Filter classname so that the class can be overridden if extended.
            $classname = apply_filters( 'woocommerce_order_class', $classname, $order_type, $order_id );
        
            if ( ! class_exists( $classname ) ) {
              return false;
            }
        
            try {
              return new $classname( $order_id );
            } catch ( Exception $e ) {
              wc_caught_exception( $e, __FUNCTION__, array( $order_id ) );
              return false;
            }
        }

		function installmentPaymentsSimulator($idOrder) {
            $order = wc_get_order();

            print_r('testado');
		}

		function my_custom_wc_webhook_process_delivery( $webhook, $arg ) {
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
                    'default'     => home_url( $wp->request ).'/wc-api/parcelow_callback',
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
			//$order->update_status('on-hold', __( 'Awaiting  payment', 'woocommerce' ));

            /* Array with parameters for API interaction */
            $args = array(
                'redirect[success]' => $this->get_return_url( $order ), 
                'redirect[failed]'  => home_url( $wp->request ) . '/carrinho', 
                'client[email]' => $_POST['billing_email'],
                'client[name]'  => $_POST['billing_first_name'].' '.$_POST['billing_last_name'],
                'client[cep]'   => $_POST['billing_postcode'],
                'client[phone]' => $_POST['billing_phone'],
				'client[address_street]' => $_POST['billing_address_1'],
				'client[address_complement]' => (!isset($_POST['billing_address_2']) ? '' : $_POST['billing_address_2']),
				'client[address_number]' => (!isset($_POST['billing_number']) ? '' : $_POST['billing_number']),
				'client[address_neighborhood]' => $_POST['billing_city'],
				'client[address_city]' => $_POST['billing_city'],
				'client[address_state]' => $_POST['billing_state'],
                'client[cpf]' => $_POST['billing_cpf'],
				'shipping[amount]' => $order->get_total_shipping()*100,
				'reference' => $this->geraCODRandNumber(6) . "_" . $order_id
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

            $existOrder = $this->checkExistOrderNaParcelow($order_id, $token);

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
                    $bearer = $this->secured_encrypt("Bearer ". $token);
                    $host = $this->secured_encrypt($this->host);
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

        public function geraCODRandNumber($n)
        {
            return "WC".strtoupper( substr(uniqid(mt_rand()), 0, $n) );
        }

        public function checkExistOrderNaParcelow($order_id, $token)
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
		public function callback_handler() {
		    
            function remove_change_comment($id, $comment) {
                if( strpos($comment->comment_content, 'Status do pedido alterado de') !== false ) {
                    wp_delete_comment( $id );
                }
            }

			$raw_post= file_get_contents("php://input");
			
			print_r($raw_post); 
			$decoded= json_decode( $raw_post );
			$order = wc_get_order( $decoded->order->reference );
		
			
		 	if ($decoded->order->status == 1){ //confirmed
				$order->update_status( 'wc-on-hold');
				return;
			}
		 	
			if ($decoded->order->status == 2 ){ //Order paid
                $order->set_status('by-customer');
                $order->add_order_note('Payment Received', true );
                add_action('wp_insert_comment', 'remove_change_comment', 10, 2);
                $order->save();
                return;
			}
			
			if ($decoded->order->status == 3 ){ //cancelled
				$order->update_status('wc-cancelled', sprintf( __( 'A Ordem foi cancelada', 'woo-parcelow' ) ) );
				return;
			}
			
			if ($decoded->order->status == 4){ // Declined 
				$order->update_status('wc-failed', sprintf( __( 'Tentativa de pagamento não autorizado.', 'woo-parcelow' ) ) );
				return;
			}

			$order->add_order_note('On Hold', true );
			return; 
	 	}
 	}
}

