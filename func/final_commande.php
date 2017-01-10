<?PHP
require_once('./connectDb.php');
date_default_timezone_set('Europe/Paris');
if($_POST['final'] == "DONE")
{
	$req_pre = mysqli_prepare($bdd, "SELECT USR_Cmd FROM users WHERE USR_ID = ?");
	mysqli_stmt_bind_param($req_pre, 's', $_SESSION['id']);
	mysqli_stmt_execute($req_pre);
	mysqli_stmt_store_result($req_pre);
	mysqli_stmt_bind_result($req_pre, $tok['usr_cmd']);
	mysqli_stmt_fetch($req_pre);
	////////////////////////////////////////////////////////////
	$id_cmd = $_SESSION['id']."-".$tok['usr_cmd'];
	$req_up = mysqli_prepare($bdd, "UPDATE users SET USR_Cmd = ? WHERE USR_ID = ?");
	$new_usr_cmd = $tok['usr_cmd'] + 1;
	mysqli_stmt_bind_param($req_up, 'ss', $new_usr_cmd, $_SESSION['id']);
	mysqli_stmt_execute($req_up);

	$req_ins = mysqli_prepare($bdd, "INSERT INTO commands (ID_CMD, ID_USR, CMD_Total) VALUES (?, ?, ?)");
	mysqli_stmt_bind_param($req_ins, 'sss', $id_cmd, $_SESSION['id'], $_POST['big_tot']);
	mysqli_stmt_execute($req_ins);
	////////////////////////////////////////////////////////////////
	foreach($_SESSION['cart'] as $key => $value)
	{
		$req_pri = mysqli_prepare($bdd, "SELECT PRD_Prix, PRD_Amount FROM products WHERE PRD_ID = ?");
		mysqli_stmt_bind_param($req_pri, 's', $key);
		mysqli_stmt_execute($req_pri);
		mysqli_stmt_store_result($req_pri);
		mysqli_stmt_bind_result($req_pri, $prd['price'], $prd['amount']);
		mysqli_stmt_fetch($req_pri);
		$date = date('Y-m-d');
		$req_li = mysqli_prepare($bdd, "INSERT INTO lists (ID_CMD, LST_PRD, LST_Quantity, LST_Date, LST_Prix) VALUES (?, ?, ?, ?, ?)");
		mysqli_stmt_bind_param($req_li, 'sssss', $id_cmd, $key, $value, $date, $prd['price']);
		mysqli_stmt_execute($req_li);
		$new_amount = $prd['amount'] - $value; 
		$req_upprod = mysqli_prepare($bdd, "UPDATE products SET PRD_Amount = ? WHERE PRD_ID = ?");
		mysqli_stmt_bind_param($req_upprod, 'ss', $new_amount, $key);
		mysqli_stmt_execute($req_upprod);
	}
	$_SESSION['cart'] = array();
}
header("Location: ./../profil.php?m=2");
?>