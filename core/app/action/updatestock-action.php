<?php

if(count($_POST)>0){
	$user = StockData::getById($_POST["id"]);
	$user->name = $_POST["name"];
	$user->update();
print "<script>window.location='index.php?view=stocks';</script>";


}


?>