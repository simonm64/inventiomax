<?php



if(isset($_FILES["name"])){
	$up = new Upload($_FILES["name"]);
	if($up->uploaded){
		$up->Process("./");
		if($up->processed){
if ( $file = fopen( "./" . $up->file_dst_name , "r" ) ) {

$ok = 0;
$error = 0;
    while($x=fgets($file,4096)){
    	////////
    	if($_POST["kind"]==1){
    		$data = explode(",", $x);
    		if(count($data)>=5){
    			$ok++;
    			$sql = "insert into product (barcode,name,price_in,price_out,inventary_min,user_id) value (\"$data[0]\",\"$data[1]\",$data[2],$data[3],$data[4],$_SESSION[user_id])";
    			Executor::doit($sql);
    		}else{
    			$error++;
    		}
    	}

    	else if($_POST["kind"]==2){
    		$data = explode(",", $x);
    		if(count($data)>=6){
    			$ok++;
    			$sql = "insert into person (no,name,lastname,address1,email1,phone1,kind) value (\"$data[0]\",\"$data[1]\",\"$data[2]\",\"$data[3]\",\"$data[4]\",\"$data[5]\",1)";
    			Executor::doit($sql);
    		}else{
    			$error++;
    		}
    	}
    	else if($_POST["kind"]==3){
    		$data = explode(",", $x);
    		if(count($data)>=6){
    			$ok++;
    			$sql = "insert into person (no,name,lastname,address1,email1,phone1,kind) value (\"$data[0]\",\"$data[1]\",\"$data[2]\",\"$data[3]\",\"$data[4]\",\"$data[5]\",2)";
    			Executor::doit($sql);
    		}else{
    			$error++;
    		}
    	}



    }

		}
		unlink("./".$up->file_dst_name);
	}
	
}


}

Core::alert("Importacion $ok Ok, $error Error");
Core::redir("./?view=import");

?>