<?PHP
require_once('./func/connectDb.php');
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
            <?PHP
                $prd_id = $_GET['id'];
                $req_pre = mysqli_prepare($bdd, "SELECT * FROM products WHERE PRD_ID = ?");
                mysqli_stmt_bind_param($req_pre, 's', $prd_id);
                mysqli_stmt_execute($req_pre);
                mysqli_stmt_bind_result($req_pre, $data['id'], $data['name'], $data['type'], $data['description'], $data['amount'],  $data['prix'], $data['img'], $data['add_date']);
                mysqli_stmt_fetch($req_pre);
            ?>

            <br /><br />
            <div align="center" style="width:50%; height:600px; border:3px solid black;margin: 5% 0 0 25%">
            <h1><?PHP echo $data['name'];?></h1>
            <br /><br />
            Categorie: <?PHP echo $data['type'];?>
            <br /><br />
            Description: <?PHP echo $data['description'];?>
            <br /><br />
            Seulement:  <?php echo $data['amount'] > 0 ? "en stock" : "Rupture de stock"; ?>
            <br /><br />
            Prix: <?PHP echo $data['prix'];?>
            <br />
            <form action="cart.php" method="post">
                <input type="hidden" name="id" value="<?PHP echo $prd_id?>">
                <select name="quantity"> 
                <?PHP for($i = 0; $i <= $data['amount']; $i++)
                {
                	echo "<option value='$i'> $i </option>";
                }
                ?>
                </select>
                <input style="margin: 5% 0 0 60%" type="submit" name="addcart" value="Add to Cart">
            </form>
            <br>
            <img src="./img/<?php echo $data['img'] ?> " style="max-width:40%; max-height:200; margin:auto">
            </div>
        </div>
    </body>
</html>
