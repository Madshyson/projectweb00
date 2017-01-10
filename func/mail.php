<?php

function envoiMail($to, $message, $sujet) {
	$to = "illaume55@hotmail.com";
	if (!preg_match("#^[a-z0-9._-]+@(hotmail|live|msn).[a-z]{2,4}$#", $to)) {
		$passage_ligne = "\r\n";
	} else {
		$passage_ligne = "\n";
	}
	$message_html = "<html><head></head><body>$message</body></html>";
	
	$boundary = "-----=".md5(rand());

	$header = "From: \"Support\"<noreplyb@support.fr>".$passage_ligne;
	$header.= "Reply-to: \"Support\" <noreplyb@suppport.fr>".$passage_ligne;
	$header.= "MIME-Version: 1.0".$passage_ligne;
	$header.= "Content-Type: multipart/alternative;".$passage_ligne." boundary=\"$boundary\"".$passage_ligne;

	$message = $passage_ligne."--".$boundary.$passage_ligne;

	$message.= "Content-Type: text/plain; charset=\"utf-8\"".$passage_ligne;
	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
	$message.= $passage_ligne."$message".$passage_ligne;

	$message.= $passage_ligne."--".$boundary.$passage_ligne;

	$message.= "Content-Type: text/html; charset=\"utf-8\"".$passage_ligne;
	$message.= "Content-Transfer-Encoding: 8bit".$passage_ligne;
	$message.= $passage_ligne.$message_html.$passage_ligne;

	$message.= $passage_ligne."--".$boundary.$passage_ligne;
	$message.= $passage_ligne."--".$boundary.$passage_ligne;

	mail($to,$sujet,$message,$header);
}

?>
