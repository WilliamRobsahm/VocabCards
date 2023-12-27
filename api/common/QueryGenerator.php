<?php
require_once(PROJECT_ROOT_PATH . "/models/Model.php");

class QueryGenerator {
    public static function generate_select_query(Model $model) : string
    {
        $table_name = $model->get_table_name();
        $primary_key = $model->get_primary_key_field();

        if (!$primary_key || !$table_name) return false;

        $sql = "SELECT * FROM " . $table_name . " WHERE " . $primary_key . " = ?";
        return $sql;
    }

    public static function generate_update_query(Model $model) : string
    {
        $table_name = $model->get_table_name();

        $primary_key = $model->get_primary_key_field();
        $primary_key_value = $model->get_primary_key_value();
    
        if (!$primary_key && !$primary_key_value) return false;

        $fields = $model->get_update_fields();
        $fields = array_map(fn($field) => ($field . " = ? "), $fields);
        $str_fields = implode(", ", $fields);

        $sql = "UPDATE " . $table_name . " SET " . $str_fields . " WHERE " . $primary_key . " = ?";
        return $sql;
    }

    public static function generate_insert_query(Model $model) : string 
    {
        $table_name = $model->get_table_name();

        $fields = $model->get_update_fields();
        $str_fields = implode(", ", $fields);
        
        $placeholders = implode(", ", array_map(fn() => "?", $fields));

        $sql = "INSERT INTO " . $table_name . " (" . $str_fields . ") VALUES (" . $placeholders . ")";
        return $sql;
    }
}

?>