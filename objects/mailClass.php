<?php
	class mailClass {
		var $db;
		function __construct() {
			$this->db = dataClass::load("gamedb");
		}
		function mail($to,$subject,$content) {
			$sql = "SELECT value FROM settings WHERE name = 'site_email_name'";
			$this->db->query($sql);
			$site_email_name = $this->db->fetchResult();
			
			$sql = "SELECT value FROM settings WHERE name = 'site_email'";
			$this->db->query($sql);
			$site_email = $this->db->fetchResult();

			$header  = 'MIME-Version: 1.0' . "\n";
			$header .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
			$header .= 'From: '.$site_email_name.' <'.$site_email.'>' . "\n";
			//$header .= 'Bcc: renjayliu@gmail.com' . "\n";
		
			if(mail($to, $subject, $content, $header)) return TRUE;
			else return FALSE;
		}
	}
?>