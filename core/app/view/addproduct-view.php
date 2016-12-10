<?php

if(count($_POST)>0){
  $product = new ProductData();
  $product->barcode = $_POST["barcode"];
  $product->name = $_POST["name"];
  $product->partida_lote = $_POST["partida_lote"];  
  $product->price_in = $_POST["price_in"];
  $product->price_out = $_POST["price_out"];
  $product->unit = $_POST["unit"];
  $product->description = $_POST["description"];
  $product->presentation = $_POST["presentation"];
  //$product->inventary_min = $_POST["inventary_min"];
  $category_id="NULL";
  $rd_id="NULL";
  
  if($_POST["category_id"]!=""){ $category_id=$_POST["category_id"];}
  if($_POST["rd_id"]!=""){ $rd_id=$_POST["rd_id"];}  
  $inventary_min="\"\"";
  if($_POST["inventary_min"]!=""){ $inventary_min=$_POST["inventary_min"];}

  $product->category_id=$category_id;
  $product->rd_id=$rd_id;  
  $product->inventary_min=$inventary_min;
  $product->user_id = $_SESSION["user_id"];


  if(isset($_FILES["image"])){
    $image = new Upload($_FILES["image"]);
    if($image->uploaded){
      $image->Process("storage/products/");
      if($image->processed){
        $product->image = $image->file_dst_name;
        $prod = $product->add_with_image();
      }
    }else{

  $prod= $product->add();
    }
  }
  else{
  $prod= $product->add();

  }




if($_POST["q"]!="" || $_POST["q"]!="0"){
 $op = new OperationData();
 $op->product_id = $prod[1] ;
 $op->stock_id = StockData::getPrincipal()->id;
 $op->operation_type_id=OperationTypeData::getByName("entrada")->id;
 $op->partida_lote =$_POST["partida_lote"]; 
 $op->price_in =$_POST["price_in"];
 $op->price_out= $_POST["price_out"];
 $op->q= $_POST["q"];
 $op->sell_id="NULL";
$op->is_oficial=1;
$op->add();
}

print "<script>window.location='index.php?view=products';</script>";


}


?>