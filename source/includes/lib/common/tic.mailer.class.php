<?php
class Mailer {
	
	function __construct(){
	}	
	
	function __set($var, $val){
        $this->$var = $val;    
    }
	
	static function sendMail($to,$subject,$body,$template = 'mail'){
		global $config;
		extract($config['email']);
		$transport = Swift_SmtpTransport::newInstance($server, $port, $protocol)
			->setUsername($user)
			->setPassword($pass);
		
		$mailer = Swift_Mailer::newInstance($transport);
		
		$message = Swift_Message::newInstance($subject)
			->setFrom(array($senderMail => $senderName))
			->setTo(array($to['email'] => $to['nome']));
		
		$message->setBody(
		Core::applyTemplateEmail($body,$template),
		'text/html' //Definimos o tipo da mensagem para text/html, ao invés de texto puro
		);
		//$message->setBody('<img alt="logo FAUBAI 2012" src="" title="FAUBAI 2012"/> ', 'text/html');
		
		$result = $mailer->send($message);
		Core::applyTemplateEmail($body,$template);
		return $result;
	}
}
?>
