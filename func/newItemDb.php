<?php
require_once('./connectDb.php');	


foreach ($_POST as $key => $value) {
	$post[$key] = $value;
}

$uploads_dir = '/nfs/2014/g/gthiry/http/MyWebSite/rush/img';
foreach ($_FILES["img"]["error"] as $key => $error) {
	if ($error == UPLOAD_ERR_OK) {
		$tmp_name = $_FILES["img"]["tmp_name"][$key];
		$name = $_FILES["img"]["name"][$key];
		move_uploaded_file($tmp_name, "$uploads_dir/$name");
	}
}

$date = date('Y-m-d H:i:s');

$insert_prep = mysqli_prepare($bdd, "INSERT INTO products (PRD_Name, PRD_Type, PRD_Description, PRD_Prix, PRD_Img, PRD_AddDate) VALUES (?, ?, ?, ?, ?, ?)");
mysqli_stmt_bind_param($insert_prep, "sissss", $post['name'], $post['type'], $post['description'], $post['prix'], $name, $date);
mysqli_stmt_execute($insert_prep);
mysqli_stmt_close($insert_prep);

header("Location: ../admin.php?m=2");
?>