<?php
	require_once('./func/connectDb.php');
	if (isAdmin($bdd) === FALSE) {
		header("Location: index.php");
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
			<table id="adminTable">
				<thead>
					<tr>
						<th><a href="./admin.php?m=1">Utilisateurs</a></th>
						<th><a href="./admin.php?m=2">Produits</a></th>
						<th><a href="./admin.php?m=3">Commandes</a></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="3">
							<?php if ($_GET['m'] == 2) : 
						
							if (isset($_POST['add'])) {
								mysqli_query($bdd, "UPDATE products SET PRD_Amount = " . $_POST['amount'] . " WHERE PRD_ID = " . $_POST['id']);
							} 

							?>
								<a href="./newItem.php">Nouvel Item</a>
								<br>
								<table>
									<thead>
										<tr>
											<td>Name</td>
											<td>Type</td>
											<td>Quantite</td>
											<td>Prix</td>
											<td></td>
										</tr>
									</thead>
									<tbody>
									<?php
										$products = mysqli_query($bdd, "
																	SELECT * 
																	FROM products
																	JOIN type ON type.TYP_ID = products.PRD_Type");
										while ($p = mysqli_fetch_assoc($products)) {
											echo "
												<tr>
													<td>" . $p['PRD_Name'] . "</td>
													<td>" . $p['TYP_Name'] . "</td>
													<td>" . $p['PRD_Amount'] . "</td>
													<td>" . $p['PRD_Prix'] . "</td>
													<td>
													<form method='post' action='./admin.php?m=2'/>
													<input type='hidden' name='id' value='" . $p['PRD_ID'] . "'/>
													<select name='amount'>";
											for ($i = 0; $i < 21; $i++) {
												echo "<option value='$i'>$i</option>";
											}

											echo "	</select>
													<input type='submit' name='add' value='add'>
													</form>
													</td>	
												</tr>";
										}
									?>
									</tbody>
								</table>
							<?php elseif ($_GET['m'] == 3) : ?>
								<table>
									<thead>
										<tr>
											<td>Numero</td>
											<td>Acheteur</td>
											<td>Objet</td>
											<td>Quantite</td>
											<td>Prix</td>
											<td>Total</td>
										</tr>
									</thead>
									<tbody>
										<?php
											$cmd = mysqli_query($bdd, "
																	SELECT * 
																	FROM commands
																	JOIN users ON users.USR_ID = commands.ID_USR");
											while ($c = mysqli_fetch_assoc($cmd)) {
												echo "
													<tr>
														<td>" . $c['ID_CMD'] . "</td>
														<td>" . $c['USR_Nom'] . "</td>
														<td colspan='3'>&nbsp;</td>
														<td>" . $c['CMD_Total'] . "</td>
													</tr>";
												$products = mysqli_query($bdd, "
																	SELECT * 
																	FROM lists
																	JOIN products ON products.PRD_ID = lists.LST_PRD 
																	WHERE lists.ID_CMD = " . $c['ID_CMD'] );
												while ($p = mysqli_fetch_assoc($products)) {
													echo "
													<tr>
														<td colspan='2'></td>
														<td>" . $p['PRD_Name'] . "</td>
														<td>" . $p['LST_Prix'] . "</td>
														<td>" . $p['LST_Quantity'] . "</td>
														<td>&nbsp;</td>
													</tr>";
												}
											}
										?>
									</tbody>
								</table>
							<?php else : ?>
								<table>
									<thead>
										<tr>
											<td>Numero</td>
											<td>Prenom</td>
											<td>Mail</td>
											<td>Phone</td>
											<td>Confirme</td>
											<td>Actif</td>
										</tr>
									</thead>
									<tbody>
										<?php
											if (isset($_POST['active'])) {
												mysqli_query($bdd, "UPDATE users SET USR_Active = " . $_POST['active'] . " WHERE USR_ID = " . $_POST['id']);
											} else if (isset($_POST['confirm'])) {
												mysqli_query($bdd, "UPDATE users SET USR_Confirmed = " . $_POST['confirm'] . " WHERE USR_ID = " . $_POST['id']);
											}
											$users = mysqli_query($bdd, "SELECT USR_ID, USR_Nom, USR_Prenom, USR_Mail, USR_Phone, USR_Confirmed, USR_Active FROM users");
											while ($u = mysqli_fetch_assoc($users)) {
												echo "
													<tr>	
														<td>" . $u['USR_Nom'] . "</td>
														<td>" . $u['USR_Prenom'] . "</td>
														<td>" . $u['USR_Mail'] . "</td>
														<td>" . $u['USR_Phone'] . "</td>";
												if ($u['USR_Confirmed'] == 1) {
													echo "<td>Confirme</td>";
												} else {
													echo "
													<td>
														<form action='./admin.php?m=1' method='post'>
															<input type='hidden' name='id' value='" . $u['USR_ID'] . "'>
															<input type='hidden' name='confirm' value='1'>
															<input type='submit' value='Confirmer'>
														</form>
													</td>";
												}
												if ($u['USR_Active'] == 1) {
													echo "
													<td>
														<form action='./admin.php?m=1' method='post'>
															<input type='hidden' name='id' value='" . $u['USR_ID'] . "'>
															<input type='hidden' name='active' value='0'>
															<input type='submit' value='Bannir'>
														</form>
													</td>
													";
												} else {
													echo "
													<td>
														<form action='./admin.php?m=1' method='post'>
															<input type='hidden' name='id' value='" . $u['USR_ID'] . "'>
															<input type='hidden' name='active' value='1'>
															<input type='submit' value='Activer'>
														</form>
													</td>
													";
												}
												echo "</tr>";
											}
										?>
									</tbody>
								</table>
							<?php endif ; ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</body>
</html>

