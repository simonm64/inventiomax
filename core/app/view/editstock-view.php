<?php
$stock = StockData::getById($_GET["id"]);
?>
<section class="content">
<div class="row">
	<div class="col-md-12">
	<h1>Editar Stock</h1>
	<br>
  <div class="box box-primary">
  <table class="table">
  <tr><td>
		<form class="form-horizontal" method="post" id="addcategory" action="index.php?action=updatestock" role="form">
  <div class="form-group">
    <label for="inputEmail1" class="col-lg-2 control-label">Nombre*</label>
    <div class="col-md-6">
      <input type="text" name="name" required class="form-control" value="<?php echo $stock->name; ?>" id="name" placeholder="Nombre">
      <input type="hidden" name="id" value="<?php echo $stock->id; ?>">
    </div>
  </div>

  <div class="form-group">
    <div class="col-lg-offset-2 col-lg-10">
      <button type="submit" class="btn btn-success">Actualizar Stock</button>
    </div>
  </div>
</form>
</td>
</tr>
</table>
</div>
	</div>
</div>
</section>