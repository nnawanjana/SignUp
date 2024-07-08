<?php

App::uses('Component', 'Controller');

class MailMessagingComponent extends Component{
	
	private $system_address = "okkey@electricitywizard.com.au";
	private $liaison_address = "jklease@electricitywizard.com.au";
	private $liaison_name = "Joel Klease";
	private $emailaddress="okkey.sumiyoshi@gmail.com"; 
	private $emailsubject="Heres An Email with a PDF"; 
	private $eol;
	private $headers;
	private $body;
	private $mime_boundary;
	private $msg;




	public function setComponent($messageContents = null){
	
		if (strtoupper(substr(PHP_OS,0,3)=='WIN')) { 
		  $this -> eol="\r\n"; 
		} elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) { 
		  $this -> eol="\r"; 
		} else { 
		  $this -> eol="\n"; 
		}


		$this->headers .= 'From: Online Signup System <'.$this -> system_address.'>'.$this -> eol; 
		$this->headers .= 'Reply-To: '. $this -> liaison_name .' <'. $this -> liaison_address .'>'.$this->eol; 
		$this->headers .= 'Return-Path: '. $this -> liaison_name.' <' . $this -> liaison_address .'>'.$this->eol; 
		$this->headers .= "Message-ID:<".$now." TheSystem@".$_SERVER['SERVER_NAME'].">".$this-> eol; 
		$this->headers .= "X-Mailer: PHP v".phpversion().$this-> eol;

		 
		$this->headers .= 'MIME-Version: 1.0'.$this->eol; 
		$this->headers .= "Content-Type: multipart/related; boundary=\"".$this->mime_boundary."\"".$this->eol; 
		$this -> mime_boundary = md5(time());
		$this -> body = $messageContents;
		
		$this -> msg = ""; 
		# HTML Version 
		$this->msg .= "--".$this->mime_boundary.$this->eol; 
		$this->msg .= "Content-Type: text/html; charset=iso-8859-1".$this->eol; 
		$this->msg .= "Content-Transfer-Encoding: 8bit".$this->eol; 
		$this->msg .= $this -> body.$this->eol.$this->eol; 
		
		
		# Finished 
		$this->msg .= "--".$this->mime_boundary."--".$this->eol.$this->eol;   // finish with two eol's for better security. see Injection. 



	}
	
	public function sendMessage(){
		# SEND THE EMAIL 
		ini_set(sendmail_from,$this->liaison_address);  // the INI lines are to force the From Address to be used ! 
		  mail($this->emailaddress, $this->emailsubject, $this->msg, $this->headers); 
		ini_restore(sendmail_from); 	
	}

}
