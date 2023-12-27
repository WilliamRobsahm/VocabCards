<?php
require_once(PROJECT_ROOT_PATH . "/models/ModelUtil.php");

#[TableName("definition")]
class Definition extends Model implements IStandardModel {
    #[DataColumn, PrimaryKey]
    public $id;
    #[DataColumn]
    public $card_id;
    #[DataColumn]
    public string $language;
    #[DataColumn]
    public string $word;

    public function get($artist_id) : ObjectResult 
    {
        return $this->std_get($artist_id);
    }

    public function get_all() : DataResult 
    {
        $table = table_name_of(Definition::class);
        $sql = "SELECT * FROM $table";
        $data = $this->select($sql);
        return DataResult::from_data($data);
    }

    public function get_all_for_card($card_id, $lang) : DataResult 
    {
        $table = table_name_of(Definition::class);
        $sql = "SELECT * FROM $table 
            WHERE card_id = ? AND language = ?";

        $data = $this->select($sql, "ss", [$card_id, $lang]);
        return DataResult::from_data($data);
    }

    public function get_all_for_language($card_lang, $lang) : DataResult 
    {
        $def_table = table_name_of(Definition::class);
        $card_table = table_name_of(Card::class);

        $sql = "SELECT d.* FROM $def_table AS d
            JOIN $card_table AS c ON d.card_id = c.id
            WHERE d.language = ? AND c.language = ?";


        $data = $this->select($sql, "ss", [$lang, $card_lang]);
        return DataResult::from_data($data);
    }

    public function save(array $params) : Result
    {
        update_model($this, $params, $this->get_primary_key_field());
        return $this->std_save();
    }
}
?>