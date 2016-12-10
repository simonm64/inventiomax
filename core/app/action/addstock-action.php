<?php

if(count($_POST)>0){
	$user = new StockData();
	$user->name = $_POST["name"];
	$user->add();
	print "<script>window.location='index.php?view=stocks';</script>";


}


?>