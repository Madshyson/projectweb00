<?php
require_once('connectDb.php');
require_once('mail.php');

function error($msg, $post) {
	$cart = $_SESSION['cart'];
	$_SESSION = array('cart' => $cart);
	foreach ($post as $key => $value) {
		$_SESSION[$key] = $value;
	}
	$_SESSION['errorMsg'] = $msg;
	header('Location: ../inscription.php');
}

foreach ($_POST as $key => $value) {
	$post[$key] = $value;
}

if (!preg_match('/\S{2,}/', $post['Prenom'])) {
	error("Prenom incorrect", $post);
} else if (!preg_match('/\S{2,}/', $post['Nom'])) {
	error("Nom incorrect", $post);
} else if (!preg_match('/[0-9]{8,15}/', $post['Phone'])) {
	error("Numero de telephone incorrect.", $post);
} else if (!preg_match("/^(([a-zA-Z]|[0-9])|([-]|[_]|[.]))+[@](([a-zA-Z0-9])|([-])){2,63}[.](([a-zA-Z0-9]){2,63})+$/", $post['Mail'])){
	error("Adresse mail invalide", $post);
} else {
	$mail_prep = mysqli_query($bdd, "SELECT * FROM users WHERE USR_Mail = '" . $post['Mail']. "'");
	$nbr = mysqli_num_rows($mail_prep);
	if ($nbr > 0) {
		error("L'adresse mail est deja utilisee.", $post);
	} else if ($post["Password"] != $post["cfPassword"]) {
		error("Les mots de passe ne correspondent pas.", $post);
	} else {

		$date = date('Y-m-d H:i:s');
		$token = hash('sha256', $post['mail']);

		$insert_prep = mysqli_prepare($bdd, "INSERT INTO users (USR_Nom, USR_Prenom, USR_Mail, USR_Phone, USR_Password, USR_AddDate, USR_Token) VALUES (?, ?, ?, ?, ?, ?, ?)");
		mysqli_stmt_bind_param($insert_prep, "sssssss", $post['Nom'], $post['Prenom'], $post['Mail'], $post['Phone'], hash('whirlpool', $post['Password']), $date, $token);
		mysqli_stmt_execute($insert_prep);
		mysqli_stmt_close($insert_prep);

		$host = shell_exec('hostname') . ":8080";
		$pwd =  explode('/', shell_exec("pwd"));
		$q = 0;

		foreach ($pwd as $p => $key) {
			if ($key == 'http') {
			$q = $p + 1;
			}
		}

		while ($q >= 0) {
			unset($pwd[$q]);
			$q -=1 ;
		}

		foreach($pwd as $p => $key) {
			if (trim($key) == 'func') {
				break;
			}
			$host .= "/" . $key;
		}

		$url = "http://"  . trim(preg_replace('/\s+/', '', $host)) . "/confirmation?token=$token\n";

		$message = "
		Bonjour,<br>
		<br>
		Veuillez vous rendre sur ce lien pour valider votre inscription.<br>
		" . $url . "<br>
		<br>
		Cordialement,<br>
		<br>
		Le support.";

		envoiMail($post['mail'], $message, "Inscription");
		$cart = $_SESSION['cart'];
		$_SESSION = array('cart' => $cart);
		header("Location: ../index.php");
	}
}
?>
