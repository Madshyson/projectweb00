<?PHP
require_once('./connectDb.php');
if ($_POST["login"] && $_POST["password"] && $_POST["submit"] == "OK")
{
	$mail = trim($_POST["login"]);
	$mdp = hash('whirlpool', $_POST["password"]);
	$req_pre = mysqli_prepare($bdd, "SELECT USR_ID, USR_Mail, USR_Password, USR_Active , USR_Confirmed, USR_AdminToken FROM users WHERE USR_Mail = ?");
	mysqli_stmt_bind_param($req_pre, 's', $mail);
	mysqli_stmt_execute($req_pre);
	mysqli_stmt_store_result($req_pre);
	mysqli_stmt_bind_result($req_pre, $data['id'], $data['mail'], $data['passwd'], $data['active'], $data['confirmed'], $data['adm_tok']);
	while(mysqli_stmt_fetch($req_pre))
	{
		if ($data['passwd'] == $mdp && $data['active'] && $data['confirmed'])
		{

			$cart = $_SESSION['cart'];
			$_SESSION = array();
			$_SESSION['login'] = $data['mail'];
			$_SESSION['id'] = $data['id'];
			$_SESSION['admin'] = $data['adm_tok'];
			$_SESSION['cart'] = $cart;
		}
	}
}
header ("Location: ../index.php");
?>