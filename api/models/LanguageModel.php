<?php
require_once(PROJECT_ROOT_PATH . "/models/ModelUtil.php");

#[TableName("language")]
class Language extends Model implements IStandardModel {
    #[DataColumn, PrimaryKey]
    public $id;
    #[DataColumn]
    public string $language_code;
    #[DataColumn]
    public string $language_name;

    public function get($lang_id) : ObjectResult 
    {
        return $this->std_get($lang_id);
    }

    public function get_all() : DataResult 
    {
        $table = table_name_of(Language::class);
        $sql = "SELECT * FROM $table";
        $data = $this->select($sql);
        return DataResult::from_data($data);
    }

    public function save(array $params) : Result
    {
        update_model($this, $params, $this->get_primary_key_field());
        return $this->std_save();
    }

    public function search($keyword) : DataResult 
    {
        $table = table_name_of(Language::class);
        $sql = "SELECT * FROM $table 
            WHERE language_name LIKE ? 
            LIMIT 20";
        $data = $this->select($sql, "s", ["%$keyword%"]);
        return DataResult::from_data($data);
    }
}
?>