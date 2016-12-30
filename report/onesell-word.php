<?php
include "../core/autoload.php";
include "../core/app/model/PersonData.php";
include "../core/app/model/UserData.php";
include "../core/app/model/SellData.php";
include "../core/app/model/OperationData.php";
include "../core/app/model/OperationTypeData.php";
include "../core/app/model/ProductData.php";

require_once '../core/controller/PhpWord/Autoloader.php';
use PhpOffice\PhpWord\Autoloader;
//use PhpOffice\PhpWord\Settings;

Autoloader::register();

$word = new PhpOffice\PhpWord\PhpWord();

$sell = SellData::getById($_GET["id"]);
//$operations = OperationData::getAllProductsBySellId($_GET["id"]);
$operations = OperationData:: getProductsDetailBySellId($_GET["id"]);
if($sell->person_id!=null){ $client = $sell->getPerson();}
$user = $sell->getUser();

/*
$section1 = $word->AddSection();
$section1->addText("RESUMEN DE VENTA",array("size"=>22,"bold"=>true,"align"=>"right"));
*/
$section1 = $word->AddSection();
$section1->addText("DIRONI COMERCIAL S.A. de C.V.",array("size"=>22,"bold"=>true,"align"=>"right"));
$section1->addText("CALLE CANADA #124 COL. LOS ALAMOS",array("size"=>12,"bold"=>true,"align"=>"right"));
$section1->addText("SALTILLO COAH. MEXICO CP. 25210",array("size"=>12,"bold"=>true,"align"=>"right"));
$section1->addText("TEL (844) 485.3300 / 485.1020",array("size"=>12,"bold"=>true,"align"=>"right"));
$section1->addText("RFC OCO 061009 P43",array("size"=>12,"bold"=>true,"align"=>"right"));
$section1->addText("WWW.RAYOLAMN.COM",array("size"=>12,"bold"=>true,"align"=>"right"));
$section1->addText();
$word->addParagraphStyle("leftRight", array("tabs" => array(new \PhpOffice\PhpWord\Style\Tab("right", 8500))));
$section1->addText("Fecha: ".date('d/m/Y'), array("size"=>14,"bold"=>false,"align"=>"right"), "leftRight");
$section1->addText();

$section1->addText("Por medio de la presente solicito le entreguen a: ".$client->name." ".$client->lastname ,array("size"=>12,"bold"=>false,"align"=>"right"));
$section1->addText("la mercancía a continuación detallada",array("size"=>12,"bold"=>false, "align"=>"right"));
$section1->addText();

$styleTable = array('borderSize' => 6, 'borderColor' => '888888', 'cellMargin' => 40);
$styleFirstRow = array('borderBottomColor' => '0000FF', 'bgColor' => 'AAAAAA');

$total=0;
/*
$table1 = $section1->addTable("table1");
$table1->addRow();
$table1->addCell(3000)->addText("Atendido por");
$table1->addCell(9000)->addText($user->name." ".$user->lastname);



if($sell->person_id!=null){
	$table1->addRow();
    $table1->addCell()->addText("Cliente");
    $table1->addCell()->addText($client->name." ".$client->lastname);
}
$section1->addText("");
*/

/*
$table2 = $section1->addTable("table2");
$table2->addRow();
$table2->addCell(1000)->addText("Codigo");
$table2->addCell(1000)->addText("Cantidad");
$table2->addCell(6000)->addText("Nombre del producto");
$table2->addCell(1000)->addText("P.U");
$table2->addCell(2000)->addText("Total");
*/

$table2 = $section1->addTable("table2");
$table2->addRow();
$table2->addCell(1000)->addText("R.D./C.D.");
$table2->addCell(1000)->addText("PARTIDA/LOTE");
$table2->addCell(6000)->addText("ESTATUS");
$table2->addCell(6000)->addText("CLAVE");
$table2->addCell(6000)->addText("DESCRIPCION DEL PRODUCTO");
$table2->addCell(1000)->addText("UNIDADES");
//$table2->addCell(2000)->addText("Total");

/*
$iUnidades = 0;
foreach($operations as $operation){
	$product = $operation->getProduct();
	$table2->addRow();
$table2->addCell()->addText($product->id);
$table2->addCell()->addText($operation->q);
$table2->addCell()->addText($product->name);
$table2->addCell()->addText("$".number_format($product->price_out,2,".",","));
$table2->addCell()->addText("$".number_format($operation->q*$product->price_out,2,".",","));
$total+=$operation->q*$product->price_out;
$iUnidades+=$operation->q;
}
*/

$iUnidades = 0;
$iSellTotal = 0;
foreach($operations as $operation) {
    //$product = $operation->getProduct();
    $table2->addRow();
    $table2->addCell()->addText($operation->rd);
    $table2->addCell()->addText($operation->partida_lote);
    $table2->addCell()->addText("Fiscal");
    $table2->addCell()->addText($operation->product_id);
    $table2->addCell()->addText($operation->name);
    $table2->addCell()->addText($operation->q);
    //$table2->addCell()->addText("$" . number_format($product->price_out, 2, ".", ","));
    //$table2->addCell()->addText("$" . number_format($operation->q * $operation->price_out, 2, ".", ","));
    //$total += $operation->q * $product->price_out;
    $total += $operation->q * $operation->price_out;
    $iUnidades += $operation->q;
}

//Total row
$table2->addRow();
$table2->addCell();
$table2->addCell();
$table2->addCell();
$table2->addCell();
$table2->addCell()->addText("TOTAL (UNIDADES)");
$table2->addCell()->addText($iUnidades);
//$table2->addCell()->addText("$".number_format($total,2,".",","),array("size"=>12));

//TOTAL section


$section1->addText("");
$section1->addText("Descuento: $".number_format($sell->discount,2,".",","),array("size"=>20));
$section1->addText("Subtotal: $".number_format($sell->total,2,".",","),array("size"=>20));
$section1->addText("Total: $".number_format($total,2,".",","),array("size"=>20));
$section1->addText("");

$table1 = $section1->addTable("table1");
$table1->addRow();
$table1->addCell(3000)->addText("Atendido por");
$table1->addCell(9000)->addText($user->name." ".$user->lastname);

/*
if($sell->person_id!=null){
    $table1->addRow();
    $table1->addCell()->addText("Cliente");
    $table1->addCell()->addText($client->name." ".$client->lastname);
}
*/
$section1->addText("");


$word->addTableStyle('table1', $styleTable);
$word->addTableStyle('table2', $styleTable,$styleFirstRow);


/// datos bancarios
$filename = "onesell-".time().".docx";
#$word->setReadDataOnly(true);
$saved = $word->save($filename,"Word2007",true);
//chmod($filename,0444);
header("Content-Disposition: attachment; filename='$filename'");
readfile($filename); // or echo file_get_contents($filename);
unlink($filename);  // remove temp file

?>