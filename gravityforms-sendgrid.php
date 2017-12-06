<?php
/*
Plugin Name: Gravity Forms Sendgrid
Plugin URI: http://www.fastdone.com.br
Description: Envie e-mails usando o sendgrid e Gravity Forms
Version: 1.0.0
Author: Raphael Nikson
Author URI: http://www.facebook.com.br/ranikson
*/


if ( ! defined( 'ABSPATH' ) ) {
	die();
}

// Página de Administração

include("options-page.php");

// SendGrid SDK PHP
require("sendgrid-php/sendgrid-php.php");


class sendgridGF {

	function __construct()	{
		add_action( 'gform_after_submission', array('sendgridGF', 'sendForm'), 10, 2 );
	}

	public static function sendForm($entry, $form){

		// SendGrid Configurations

		$sendGridKEY 	= get_option('sendgrid-api-key');
		$remetenteName 	= get_option('rementente-name');
		$remetenteMail 	= get_option('rementente-mail');

		// Pega o e-mail do administrador do site

		$adminSiteMail = get_option('admin_email', true);

		// Pega o nome do site

		$siteName = get_option('blogname', true);

		// Pega o nome do formulário

		$formNameGF = $form['title'];

		// Pega lista de e-mails que receberá o e-mail que foi definindo nas configurações do formulário.
		
		rsort($form['notifications']);
		$formTo 	= $form['notifications'][0]['to'];
		$formToList = explode(',', $formTo);

		// Pega todos os campos do formulário

		$campos = [];
		$msg 	= "";

		foreach($form['fields'] as $field){
			$item['name'] = $field->label;
			$item['value'] = $entry[$field->id];
			array_push($campos, $item);
		}

		// Cria o html da mensagem contendo todos os campos do formulário

		foreach ($campos as $campo) {
			$msg .= "<div><strong>{$campo['name']}:</strong> {$campo['value']}</div>";
		}

		// SendGrid 

		$apiKey  	= isset($sendGridKEY)? $sendGridKEY : '';
		$subject  	= $formNameGF;
		$from     	= new SendGrid\Email($remetenteName, $remetenteMail);
		$to 		= new SendGrid\Email($siteName, $adminSiteMail);
		$content  	= new SendGrid\Content("text/html", $msg);
		$mail     	= new SendGrid\Mail($from, $subject, $to, $content);
		$sg       	= new \SendGrid($apiKey);
		$custoSend 	= new SendGrid\Personalization();

		// SendGrid Personalization

		foreach($formToList AS $toMail){
			if ($toMail == "{admin_email}") {
				$to = new SendGrid\Email('Administrator', $adminSiteMail);
			}else{
				$to = new SendGrid\Email(null, $toMail);
			}
			$custoSend->addTo($to);
		}
    	$mail->addPersonalization($custoSend);
		$response = $sg->client->mail()->send()->post($mail);

	}

}

$send = new sendgridGF;

include("save.php");

// Plugin Assets

function sendGridGFAssets(){
	wp_enqueue_script( 'gravity-forms-sendgrid', plugin_dir_url( __FILE__ ) . 'plugin.js', 1, 1, true );
	$gfSendGridVars = [
		'nonce' => wp_create_nonce( "sendgridnonce" ),
	];
	wp_localize_script( 'gravity-forms-sendgrid', 'gfsendgrid', $gfSendGridVars );
}

add_action( 'admin_enqueue_scripts', 'sendGridGFAssets',99);

