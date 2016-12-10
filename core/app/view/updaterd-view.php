<?php

if(count($_POST)>0){
	$user = RdData::getById($_POST["user_id"]);
	$user->name = $_POST["name"];
	$user->update();
print "<script>window.location='index.php?view=rd';</script>";


}


?>