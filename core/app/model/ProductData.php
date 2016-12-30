<?php
class ProductData {
	public static $tablename = "product";

	public function ProductData(){
		$this->name = "";
		$this->partida_lote = "";
		$this->rd_id = "";
		$this->price_in = "";
		$this->price_out = "";
		$this->unit = "";
		$this->user_id = "";
		$this->presentation = "0";
		$this->created_at = "NOW()";
	}

	public function getCategory(){ return CategoryData::getById($this->category_id);}
	
		public function getRd(){ return RdData::getById($this->rd_id);}

	public function add(){
		$sql = "insert into ".self::$tablename." (barcode,name,partida_lote, description,price_in,price_out,user_id,presentation,unit,category_id, rd_id,inventary_min,created_at) ";
		$sql .= "value (\"$this->barcode\",\"$this->name\",\"$this->partida_lote\",\"$this->description\",\"$this->price_in\",\"$this->price_out\",$this->user_id,\"$this->presentation\",\"$this->unit\",$this->category_id,$this->rd_id,$this->inventary_min,NOW())";
		return Executor::doit($sql);
	}
	public function add_with_image(){
		$sql = "insert into ".self::$tablename." (barcode,image,name,partida_lote,description,price_in,price_out,user_id,presentation,unit,category_id, rd_id,inventary_min) ";
		$sql .= "value (\"$this->barcode\",\"$this->image\",\"$this->name\",\"$this->partida_lote\",\"$this->description\",\"$this->price_in\",\"$this->price_out\",$this->user_id,\"$this->presentation\",\"$this->unit\",$this->category_id,$this->rd_id,$this->inventary_min)";
		return Executor::doit($sql);
	}


	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "delete from ".self::$tablename." where id=$this->id";
		Executor::doit($sql);
	}

// partiendo de que ya tenemos creado un objecto ProductData previamente utilizamos el contexto
	public function update(){
		$sql = "update ".self::$tablename." set barcode=\"$this->barcode\",name=\"$this->name\",partida_lote=\"$this->partida_lote\",price_in=\"$this->price_in\",price_out=\"$this->price_out\",unit=\"$this->unit\",presentation=\"$this->presentation\",category_id=$this->category_id,rd_id=$this->rd_id,inventary_min=\"$this->inventary_min\",description=\"$this->description\",is_active=\"$this->is_active\" where id=$this->id";
		Executor::doit($sql);
	}

	public function del_category(){
		$sql = "update ".self::$tablename." set category_id=NULL where id=$this->id";
		Executor::doit($sql);
	}
	
		public function del_rd(){
		$sql = "update ".self::$tablename." set rd_id=NULL where id=$this->id";
		Executor::doit($sql);
	}


	public function update_image(){
		$sql = "update ".self::$tablename." set image=\"$this->image\" where id=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ProductData());

	}

    public static function getByIdToSell($id){
        $sql = "select
                  p.id,
                  p.unit,
                  rd.name rd,
                  p.partida_lote lote,
                  p.name,
                  p.price_out
                FROM
                  product p LEFT JOIN rd
                  ON p.rd_id = rd.id
                  where p.id={$id}";
		$query = Executor::doit($sql);
		return Model::one($query[0],new ProductData());

	}


	public static function getAll(){
		$sql = "select * from ".self::$tablename;
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

    public static function getAllInv(){
        #$sql = "select * from ".self::$tablename;
        $sql = "select
                p.id,
                p.name,
                rd.name rd,
                p.barcode
            from
              product p
            join rd on (
              p.rd_id = rd.id
              );";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getAllByCategoryId($id){
		$sql = "select * from ".self::$tablename." where category_id=$id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

	public static function getAllByPage($start_from,$limit){
		$sql = "select * from ".self::$tablename." where id>=$start_from limit $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}


	public static function getLike($p){
        $sql = "select * from ".self::$tablename." where barcode like '%$p%' or name like '%$p%' or id like '%$p%'";
        $query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

    public static function getLikeToSell($p){
        $sql = "select
                    p.id,
                    p.name,
                    p.unit,
                    rd.name rd,
                    p.inventary_min,
                    p.price_out,
                    p.price_in
                from
                  ".self::$tablename." p
                join rd on (
                  p.rd_id = rd.id
                  ) where p.barcode like '%$p%' or p.name like '%$p%' or p.id like '%$p%'";
        $query = Executor::doit($sql);
        return Model::many($query[0],new ProductData());
    }

	public static function getAllByUserId($user_id){
		$sql = "select * from ".self::$tablename." where user_id=$user_id order by created_at desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}

}

?>