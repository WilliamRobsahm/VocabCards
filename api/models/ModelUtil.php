
<?php

function update_model(IStandardModel $model, array $params, string $primary_field) {
    if(isset($params[$primary_field])) {
        apply_where_attribute($model, $model->get($params[$primary_field])->object, DataColumn::class);
    }
    apply_where_attribute($model, $params, DataColumn::class);
}

function table_name_of(string $model_class_name) : string 
{
    $reflector = new ReflectionClass($model_class_name);
    $attr = get_attr($reflector, TableName::class);
    $args = $attr->getArguments();
    if($args == null || count($args) == 0) {
        throw new Exception("Class does not have a 'TableName' attribute");
    }
    return $args[0];
}
?>