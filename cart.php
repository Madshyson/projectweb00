<?php
	require_once('./func/connectDb.php');
	if (isset($_POST['id']) && isset($_POST['quantity']))
	{
		$req_stock = mysqli_prepare($bdd, "SELECT PRD_Amount FROM products WHERE PRD_ID = ?");
		mysqli_stmt_bind_param($req_stock, 's', $_POST['id']);
		mysqli_stmt_execute($req_stock);
		mysqli_stmt_store_result($req_stock);
		mysqli_stmt_bind_result($req_stock, $st['stock']);
		mysqli_stmt_fetch($req_stock);
		if ($st['stock'] >= $_POST['quantity'])
		{
			$addline = 0;
			foreach($_SESSION['cart'] as $elem => $qty)
			{
				if($elem == $_POST['id'])
				{
					$_SESSION['cart'][$elem] += $_POST['quantity'];
					$addline = 1;
				}
			}
			if ($addline == 0) {
				$_SESSION['cart'][$_POST['id']] = $_POST['quantity'];
			}
		}
		else
			echo "stock insuffisant";
	header("Location: ./cart.php");

	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset='utf-8'>
		<link rel="stylesheet" type="text/css" href="./css/style.css">
		<link rel="stylesheet" type="text/css" href="./css/menu.css">
		<title>Consolatoria Sedes</title>
	</head>
	<body>
		<div id="header">
			<div class="connect">
				<span><a href="cart.php">Cart</a> |&nbsp;</span>
				<?php if ($_SESSION['login'] == guest) : ?>
				<form action="./func/loginDb.php" method="post">
					<label for="login">E-Mail : </label>
					<input id="login" type="text" name="login" />
					<label for="password">Password : </label>
					<input id="password" type="password" name="password" />
					<input type="submit" name="submit" value="OK" class="button">
				</form>
				<?php else: ?>
				<form action="./func/logoutDb.php" method="post">
					<label for="logout"><?php echo $_SESSION['login']?></label>
					<input type="submit" value="Logout" class="button">
				</form>
				<?php endif?>
			</div>
			<div class="banner"><span id="titre">CONSOLATORIA SEDES</span></div>
			<div class="menu">
				<ul id="menu" class="lv1">
					<li><a href="index.php">Home</a></li>
					<li><a>Armes</a>
						<ul class="lv2">
						<li><a href="type.php?type=1&page=1">Catapulte</a></li>
						<li><a href="type.php?type=2&page=1">Baliste</a></li>
						<li><a href="type.php?type=3&page=1">Trebuchet</a></li>
						</ul>
					</li>
					<li><a>Equipement</a>
						<ul class="lv2">
							<li><a href="type.php?type=4&page=1">Tentes</a></li>
							<li><a href="#">Consommables</a>
								<ul class="lv3">
									<li><a class="op" href="type.php?type=5&page=1">Aliments</a></li>
									<li><a class="op" href="type.php?type=6&page=1">Boissons</a></li>
								</ul>
							</li>
							<li><a href="type.php?type=7&page=1">Munitions</a></li>
							<li><a href="type.php?type=8&page=1">Armes Soldats</a></li>
						</ul>
					</li>
					<li><a href="#">Menu</a>
						<ul class="lv2">
							<li><a href="cart.php">Mon Panier</a></li>
							<?php if ($_SESSION['login'] === 'guest') : ?>
								<li><a href="inscription.php">Inscription</a></li>
							<?php else : ?>
								<li><a href="profil.php">Mon Profil</a></li>
								<?php if (isAdmin($bdd) == TRUE) :?>
								<li><a href="admin.php">Administration</a></li>
								<?php endif; ?>
							<?php endif; ?>
						</ul>
					</li>
				</ul>
			</div>
		</div>
		<div class="corps">
		
		<div style="width:75%; height:500; margin:20%; border:3px solid black;">
		<table>
				<th>Nom</th>
				<th>Prix</th>
				<th>Quantite</th>
				<th>Total</th>
			<?PHP
			foreach($_SESSION['cart'] as $key => $value)
			{
				echo "<tr>";
				$req_pre = mysqli_prepare($bdd, "SELECT PRD_Name, PRD_Prix FROM products WHERE PRD_ID = ?");
				mysqli_stmt_bind_param($req_pre, 's', $key);
				mysqli_stmt_execute($req_pre);
				mysqli_stmt_store_result($req_pre);
				mysqli_stmt_bind_result($req_pre, $data['prd_nom'], $data['prd_prix']);
				mysqli_stmt_fetch($req_pre);
				$tot = $data['prd_prix'] * $value;
				$big_tot += $tot;
				echo "<td>";
				echo $data['prd_nom'];
				echo "</td>";
				echo "<td>";
                echo $data['prd_prix'];
                echo "</td>";
				echo "<td>";
                echo $value;
                echo "</td>";
				echo "<td>";
                echo $tot;
                echo "</td>";
				echo "</tr>";
			}
			?>
		<tr>
		<td colspan="3" style="border:none;"></td>
		<td><?PHP echo $big_tot; ?></td>
		</tr>
		</table>
		<form action="./func/final_commande.php" method="post">
		<input type="hidden" name="big_tot" value="<?php echo $big_tot ?>">
        <input style="margin:0 0 0 50%" type="submit" name="final" value="DONE">
        </form>
		</div>
        </div>
    </body>
</html>
