<?php
require_once(PROJECT_ROOT_PATH . "/models/ModelUtil.php");

#[TableName("card")]
class Card extends Model implements IStandardModel {
    #[DataColumn, PrimaryKey]
    public $id;
    #[DataColumn]
    public string $word;
    #[DataColumn]
    public string $language;

    public function get($artist_id) : ObjectResult 
    {
        return $this->std_get($artist_id);
    }

    public function get_all() : DataResult 
    {
        $table = table_name_of(Card::class);
        $sql = "SELECT * FROM $table";
        $data = $this->select($sql);
        return DataResult::from_data($data);
    }

    public function get_all_for_language($lang) : DataResult 
    {
        $table = table_name_of(Card::class);
        $sql = "SELECT * FROM $table WHERE language = ?";
        $data = $this->select($sql, "s", [$lang]);
        return DataResult::from_data($data);
    }

    public function save(array $params) : Result
    {
        update_model($this, $params, $this->get_primary_key_field());
        return $this->std_save();
    }
}
?>