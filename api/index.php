<?php
require __DIR__."/vendor/autoload.php";
require_once( explode( "wp-content" , __FILE__ )[0] . "wp-load.php" );

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use PA\Parcelow\Parcelow;
use PA\Helper\Helper;
$p = new Parcelow();
$h = new Helper();

$gmtDate = gmdate("D, d M Y H:i:s");
header("Expires: {$gmtDate} UTC/GMT");
header("Last-Modified: {$gmtDate} UTC/GMT");
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Content-Type: text/html;  charset=utf-8", true);

if (isset($_GET["acao"])) {
    $acao = sanitize_text_field($_GET["acao"]);
}
if (isset($_POST["acao"])) {
    $acao = sanitize_text_field($_POST["acao"]);
}

switch ($acao) {
    
    case 'SHOWQUETIONS':
        $order_id = sanitize_text_field($_POST["order_id"]);
        $access_token = $_POST["acc"];
        $apihost = $_POST["apihost"];
        echo $p->showQuetions($order_id, $access_token, $apihost);
        break;

    case 'RESPONSEQUESTION':
        $order_id = sanitize_text_field($_POST["order_id"]);
        $access_token = $_POST["acc"];
        $apihost = $_POST["apihost"];

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

        $data = json_encode($data);
       
        echo $p->responseQuetions($order_id, $access_token, $data, $apihost);
        break;

    case 'CARREGAPARCELAS':
        $order_id = sanitize_text_field($_POST["order_id"]);
        $access_token = $_POST["acc"];
        $apihost = $_POST["apihost"];
        $total = sanitize_text_field($_POST["total"]);
        echo $p->jsonParcelas($order_id, $access_token, $apihost, $total);
        break;
    
    case 'FINALIZAPAGTOCARTAO':

        $order_id = sanitize_text_field($_POST["order_id"]);
        $access_token = $_POST["acc"];
        $apihost = $_POST["apihost"];

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

        echo $p->finalizaPagtoCartao($order_id, $access_token, $data, $apihost);

        break;

    case 'GERARPIX':
        $order_id = sanitize_text_field($_POST["order_id"]);
        $access_token = $_POST["acc"];
        $data = array("method" => "pix");
        $apihost = $_POST["apihost"];
        echo $p->finalizaPagtoPIX($order_id, $access_token, $data, $apihost);
        break;

    case 'DADOSORDER':
        $order_id = sanitize_text_field($_POST["order_id"]);
        $access_token = $_POST["acc"];
        $data = array("method" => "pix");
        $apihost = $_POST["apihost"];
        echo $p->getDadosOrder($order_id, $access_token, $data, $apihost);
        break;

}
