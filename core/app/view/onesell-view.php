<section class="content">
<div class="btn-group pull-right">
  <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
    <i class="fa fa-download"></i> Descargar <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="report/onesell-word.php?id=<?php echo $_GET["id"];?>">Word 2007 (.docx)</a></li>
<li><a onclick="thePDF()" id="makepdf" class=""><i class="fa fa-download"></i> Descargar PDF</a>
  </ul>
</div>
<h1>Resumen de Venta</h1>
<?php if(isset($_GET["id"]) && $_GET["id"]!=""):?>
<?php
$sell = SellData::getById($_GET["id"]);
$operations = OperationData::getProductsDetailBySellId($_GET["id"]);
$total = 0;
?>
<?php
if(isset($_COOKIE["selled"])){
  foreach ($operations as $op) {
//    print_r($operation);
    $qx = OperationData::getQByStock($op->product_id,StockData::getPrincipal()->id);
    // print "qx=$qx";
    if($qx==0){
      echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $op->name</b> no tiene existencias en inventario.</p>";
    }else if($qx<=$op->inventary_min/2){
      echo "<p class='alert alert-danger'>El producto <b style='text-transform:uppercase;'> $op->name</b> tiene muy pocas existencias en inventario.</p>";
    }else if($qx<=$op->inventary_min){
      echo "<p class='alert alert-warning'>El producto <b style='text-transform:uppercase;'> $op->name</b> tiene pocas existencias en inventario.</p>";
    }
  }
  setcookie("selled","",time()-18600);
}

?>
<div class="box box-primary">
<table class="table table-bordered">
<?php if($sell->person_id!=""):
$client = $sell->getPerson();
?>
<tr>
  <td style="width:150px;">Cliente</td>
  <td><?php echo $client->name." ".$client->lastname;?></td>
</tr>

<?php endif; ?>
<?php if($sell->user_id!=""):
$user = $sell->getUser();
?>
<tr>
  <td>Atendido por</td>
  <td><?php echo $user->name." ".$user->lastname;?></td>
</tr>
<?php endif; ?>
</table>
</div>
<br>
<div class="box box-primary">
<table class="table table-bordered table-hover">
  <thead>
    <th>Codigo</th>
    <th>Cantidad</th>
    <th>RD</th>
    <th>Lote</th>
    <th>Nombre del Producto</th>
    <th>Precio Unitario</th>
    <th>Total</th>

  </thead>
<?php
  foreach($operations as $op){
?>
<tr>
  <td><?php echo $op->product_id ;?></td>
  <td><?php echo $op->q ;?></td>
    <td><?php echo $op->rd ;?></td>
    <td><?php echo $op->partida_lote ;?></td>
    <td><?php echo $op->name ;?></td>
  <td>$ <?php echo number_format($op->price_out,2,".",",") ;?></td>
  <td><b>$ <?php echo number_format($op->q*$op->price_out,2,".",",");$total+=$op->q*$op->price_out;?></b></td>
</tr>
<?php
  }
  ?>
</table>
</div>
<br><br>
<div class="row">
<div class="col-md-4">
<div class="box box-primary">
<table class="table table-bordered">
  <tr>
    <td><h4>Descuento:</h4></td>
    <td><h4>$ <?php echo number_format($sell->discount,2,'.',','); ?></h4></td>
  </tr>
  <tr>
    <td><h4>Subtotal:</h4></td>
    <td><h4>$ <?php echo number_format($total,2,'.',','); ?></h4></td>
  </tr>
  <tr>
    <td><h4>Total:</h4></td>
    <td><h4>$ <?php echo number_format($total-  $sell->discount,2,'.',','); ?></h4></td>
  </tr>
</table>
</div>

<?php if($sell->person_id!=""):
$credit=PaymentData::sumByClientId($sell->person_id)->total;

?>
<div class="box box-primary">
<table class="table table-bordered">
  <tr>
    <td><h4>Saldo anterior:</h4></td>
    <td><h4>$ <?php echo number_format($credit-$total,2,'.',','); ?></h4></td>
  </tr>
  <tr>
    <td><h4>Saldo Actual:</h4></td>
    <td><h4>$ <?php echo number_format($credit,2,'.',','); ?></h4></td>
  </tr>
</table>
</div>
<?php endif;?>
</div>
</div>






<script type="text/javascript">
        function thePDF() {
console.log("ddede");
var columns = [
//    {title: "Reten", dataKey: "reten"},
    /*{title: "Codigo", dataKey: "code"},
    {title: "Cantidad", dataKey: "q"},
    {title: "RD", dataKey: "rd"},
    {title: "Lote", dataKey: "partida_lote"},
    {title: "Nombre del Producto", dataKey: "product"}, 
    {title: "Precio unitario", dataKey: "pu"}, 
    {title: "Total", dataKey: "total"}, */

    {title: "R.D./C.D.", dataKey: "rd"},
    {title: "PARTIDA/LOTE", dataKey: "partida_lote"},
    {title: "ESTATUS", dataKey: "estatus"},
    {title: "CLAVE", dataKey: "code"},
    {title: "DESCRIPCION DE LA MERCANCIA", dataKey: "product"},
    {title: "UNIDADES", dataKey: "q"},

    //{title: "Total", dataKey: "total"},
//    ...
];


var columns2 = [
//    {title: "Reten", dataKey: "reten"},
    {title: "", dataKey: "clave"},
    {title: "", dataKey: "valor"},
//    ...
];


var rows = [
  <?php $total_q = 0; foreach($operations as $op):
  ?>
    {
        "rd": "<?php echo $op->rd; ?>",
        "partida_lote": "<?php echo $op->partida_lote; ?>",
        "estatus": "<?php echo 'Fiscal' ?>",
        "code": "<?php echo $op->product_id; ?>",
        "product": "<?php echo $op->name; ?>",
        "q": "<?php echo $op->q; $total_q += $op->q; ?>",
    },
 <?php endforeach;
 ?>
    {
    "rd": "",
    "partida_lote": "",
    "estatus": "",
    "code": "",
    "product": "TOTAL (UNIDADES):",
    "q": "<?php echo $total_q;  ?>",
    }
];

var rows2 = [
<?php if($sell->person_id!=""):
$person = $sell->getPerson();
?>

      <?php endif; ?>
    {
      "clave": "Atendido por",
      "valor": "<?php echo ""; // $user->name." ".$user->lastname; ?>",
      },

];

var rows3 = [

    {
      "clave": "Descuento",
      "valor": "$ <?php echo number_format($sell->discount,2,'.',',');; ?>",
      },
    {
      "clave": "Subtotal",
      "valor": "$ <?php echo number_format($sell->total,2,'.',',');; ?>",
      },
    {
      "clave": "Total",
      "valor": "$ <?php echo number_format($sell->total-$sell->discount,2,'.',',');; ?>",
      },
];


// Only pt supported (not mm or in)
var doc = new jsPDF('p', 'pt');
        doc.setFontSize(26);
        //doc.text("NOTA DE VENTA", 40, 65)
        doc.text("DIRONI COMERCIAL S.A de C.V.", 40, 65);
        doc.setFontSize(12);
        doc.text("CALLE CANADA #124 COL. LOS ALAMOS", 40, 80);
        doc.text("SALTILLO COAH. MEXICO CP. 25210", 40, 95);
        doc.text("TEL (844) 485.3300 / 485.1020", 40 ,110);
        doc.text("RFC OCO 061009 P43", 40, 125);
        doc.text("WWW.RAYOLAMN.COM", 40, 140);
        doc.setFontSize(14);
        doc.text("<?php echo "Fecha: ".date('d/m/Y'); ?>", 420, 170);
        doc.setFontSize(12);
        doc.text("Por medio de la presente solicito le entreguen a: <?php echo (isset($person->name))? $person->name." ".$person->lastname : ""; ?>", 40, 190);
        doc.text("la mercancía a continuación detallada", 40, 205);
        //doc.text("Fecha: <?php //echo $sell->created_at; ?>", 40, 170);
//        doc.text("Operador:", 40, 150);
//        doc.text("Header", 40, 30);
  //      doc.text("Header", 40, 30);

//TABLA DE CONTENIDO DE LA VENTA
doc.autoTable(columns, rows, {
    theme: 'grid',
    overflow:'linebreak',
    styles: {
        fillColor: [100, 100, 100]
    },
    columnStyles: {
        id: {fillColor: 255}
    },
    //margin: {top: doc.autoTableEndPosY()+15},
    margin: {top: 230},
    afterPageContent: function(data) {
//        doc.text("Header", 40, 30);
    }
});

//TOTAL
doc.autoTable(columns2, rows3, {
    theme: 'grid',
    overflow:'linebreak',
    styles: {
        fillColor: [100, 100, 100]
    },
    columnStyles: {
        id: {fillColor: 255}
    },
    //margin: {top: 230},
    margin: {top: doc.autoTableEndPosY()+15},
    afterPageContent: function(data) {
//        doc.text("Header", 40, 30);
    }
});


//CLIENTE Y ATENDIDO POR
doc.autoTable(columns2, rows2,{
    theme: 'grid',
    overflow:'linebreak',
    styles: {
        fillColor: [100, 100, 100]
    },
    columnStyles: {
        id: {fillColor: 255}
    },
    //margin: {top: 230},
    margin: {top: doc.autoTableEndPosY()+15},
    afterPageContent: function(data) {
//        doc.text("Header", 40, 30);
    }
});

//CLIENTE Y ATENDIDO POR
/*
doc.autoTable(columns2, rows2, {
    theme: 'grid',
    overflow:'linebreak',
    styles: {
        fillColor: [100, 100, 100]
    },
    columnStyles: {
        id: {fillColor: 255}
    },
    margin: {top: doc.autoTableEndPosY()+15},
    afterPageContent: function(data) {
//        doc.text("Header", 40, 30);
    }
});*/
//doc.setFontsize
//img = new Image();
//img.src = "liberacion2.jpg";
//doc.addImage(img, 'JPEG', 40, 10, 610, 100, 'monkey'); // Cache the image using the alias 'monkey'
doc.setFontSize(20);
doc.setFontSize(12);
doc.text("Generado por el Sistema de inventario", 40, doc.autoTableEndPosY()+25);
doc.save('sell-<?php echo date("d-m-Y h:i:s",time()); ?>.pdf');
//doc.output("datauri");

        }
    </script>

<script>
  $(document).ready(function(){
  //  $("#makepdf").trigger("click");
  });
</script>




<?php else:?>
  501 Internal Error
<?php endif; ?>
</section>