<?php
	require_once('./func/connectDb.php');

	if (isLoggued() == FALSE) {
		header("./index.php");
	}

	$id = $_SESSION['id'];		



	if(isset($_POST['submit']) && $_POST['submit'] == "delete") {
		$del = mysqli_prepare($bdd, "DELETE FROM users WHERE USR_ID = ?");
		mysqli_stmt_bind_param($del, 's', $id);
		mysqli_stmt_execute($del);
		header('Location: ./func/logoutDb.php');
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
						<th><a href="./profil.php?m=1">Profil</a></th>
						<th><a href="./profil.php?m=2">Commandes</a></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td colspan="2">
							<?php if ($_GET['m'] == 2) : ?>
								<table>
									<thead>
										<tr>
											<td>Numero</td>
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
																	JOIN users ON users.USR_ID = commands.ID_USR
																	WHERE commands.ID_USR = " . $id);
											while ($c = mysqli_fetch_assoc($cmd)) {
												echo "
													<tr>
														<td>" . $c['ID_CMD'] . "</td>
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
														<td></td>
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
								<?php
									if (isset($_POST['submit']) && $_POST['submit'] == 'modify') {

										foreach ($_POST as $key => $value) {
											$post[$key] = $value;
										}

										if(!preg_match('/\S{2,}/', $post['name']))
											echo "prenom incorrect";
										else if (!preg_match('/\S{2,}/', $post['surname']))
											echo "nom incorrect";
										else if (!preg_match('/[0-9]{10}/', $post['phone']))
											echo "numero de telephone incorrect";
										else {
											$update = mysqli_prepare($bdd, "UPDATE users SET USR_Phone = ?, USR_Voie = ?, USR_Ville = ?, USR_Cp = ? WHERE USR_ID = ?");
											mysqli_stmt_bind_param($update, 'sssss', $post['phone'], $post['voie'], $post['ville'], $post['cp'], $_SESSION['id']);
											mysqli_stmt_execute($update);
										}
									} 

									$req_pre = mysqli_prepare($bdd, "SELECT USR_Nom, USR_Prenom, USR_Phone, USR_Mail, USR_Voie, USR_Ville, USR_Cp FROM users WHERE USR_ID = ?");
									mysqli_stmt_bind_param($req_pre, 's', $id);
									mysqli_stmt_execute($req_pre);
									mysqli_stmt_store_result($req_pre);
									mysqli_stmt_bind_result($req_pre, $data['name'], $data['surname'], $data['phone'], $data['mail'], $data['voie'], $data['ville'], $data['cp']);
									mysqli_stmt_fetch($req_pre);
								?>
								<form action="./profil.php" method="post">
									<label for="name">Nom :</label> 
									<input type='text' id='name' name='name' value='<?php echo $data['name'] ?>' readonly="readonly"><br/>

									<label for="surname">Prenom :</label> 
									<input type='text' id='surname' name='surname' value='<?php echo $data['surname'] ?>' readonly="readonly"><br/>

									<label for="mail">Adresse Mail :</label> 
									<input type='text' id='mail' name='mail' value='<?php echo $data['mail'] ?>' readonly="readonly"><br/>

									<label for="phone">Telephone :</label> 
									<input type='text' id='phone' name='phone' value='<?php echo $data['phone'] ?>' required="required"><br/>

									<label for="voie">Rue :</label> 
									<input type='text' id='voie' name='voie' value='<?php echo $data['voie'] ?>' required="required"><br/>

									<label for="ville">Ville :</label> 
									<input type='text' id='ville' name='ville' value='<?php echo $data['ville'] ?>' required="required"><br/>

									<label for="cp">Code Postal:</label> 
									<input type='text' id='cp' name='cp' value='<?php echo $data['cp'] ?>' required="required"><br/>

									<input type='submit' name='submit' value='modify'>
								</form>
								<hr>
								<form action="./profil.php" method="post">
									Supprimer le compte :
									<input type='submit' name='submit' value='delete'>
								</form>
							<?php endif ; ?>
						</td>
					</tr>
				</tbody>
			</table>
			
		</div>
	</body>
</html>
