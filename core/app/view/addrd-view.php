<?php

if(count($_POST)>0){
	$user = new RdData();
	$user->name = $_POST["name"];
	$user->add();

print "<script>window.location='index.php?view=rd';</script>";


}


?>