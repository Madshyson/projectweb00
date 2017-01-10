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
			<form action="./func/newItemDb.php" method="post" enctype="multipart/form-data">
				<label for="name">Nom :</label> 
				<input type='text' id='name' name='name' required="required"><br/>

				<label for="description">Description</label><br />
				<textarea id="description" name='description' style="width: 350px; height:150px;"></textarea><br/>

				<label for="type">Type :</label>
				<select id="type" name="type">
					<?php
						$type = mysqli_query($bdd, "SELECT * FROM type");
						while ($t = mysqli_fetch_assoc($type)) {
							echo "<option value='" . $t['TYP_ID'] . "'>" . $t['TYP_Name'] . "</option>";
						}
					?>
				</select><br />

				<label for="prix">Prix :</label> 
				<input type='text' id='prix' name='prix' required="required"><br/>
				
				<label for="file">Image</label><br>
				<input id="file" type="file" name="img[]"/><br><br>
				
				<input type="submit" value="Add">
			</form>
		</div>
	</body>
</html>

