<?php
session_start();

if (!$_SESSION['login']) {
	$_SESSION['login'] = "guest";
	$_SESSION['cart'] = array();
}

if (!($bdd = mysqli_connect('localhost', 'root', '', 'rush'))) {
	echo "ERREUR\n";
}

function isAdmin($bdd) {
	if (isset($_SESSION['admin']) && strlen($_SESSION['admin']) > 1) {
		$adm_prep = mysqli_prepare($bdd, "SELECT USR_AdminToken FROM users WHERE USR_ID = ?");
		mysqli_stmt_bind_param($adm_prep, "i", intval($_SESSION['id']));
		mysqli_stmt_execute($adm_prep);
		mysqli_stmt_bind_result($adm_prep, $data['tkn']);
		while($u = mysqli_stmt_fetch($adm_prep)){
			if ($data['tkn'] == $_SESSION['admin']) {
				return TRUE;
			}
		}
	}
	return FALSE;
}

function isLoggued() {
	if (isset($_SESSION['id']) && isset($_SESSION['admin']) && $_SESSION['login'] != "guest") {
		return TRUE;
	}
	return FALSE;
}

?>
