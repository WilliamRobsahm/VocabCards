<?php
require_once(PROJECT_ROOT_PATH . "/common/QueryGenerator.php");
class Model {
    protected $connection = null;

    public function __construct() {
        try {
            $this->connection = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);
            
            if ( mysqli_connect_errno()) {
                throw new Exception("Could not connect to database.");   
            }
        }
        catch (Exception $e) {
            throw new Exception($e->getMessage());   
        }
    }

    public function get_table_name() : string 
    {
        $attr = get_attr($this, TableName::class);
        $args = $attr->getArguments();
        if($args == null || count($args) == 0) {
            throw new Exception("Class does not have a 'TableName' attribute");
        }
        return $args[0];
    }

    public function apply_values(array|object $params) 
    {
        apply_where_attribute($this, $params, DataColumn::class);
    }

    public function get_primary_key_value() 
    {
        $property = get_property_with_attr($this, PrimaryKey::class);

        if($property) {
            try {
                return $property->getValue($this);
            }
            catch(Exception $e) { }
        }
        return null;
    }

    public function get_primary_key_field() 
    {
        $property = get_property_with_attr($this, PrimaryKey::class);
        return $property ? $property->getName() : null;
    }

    public function get_all_columns() 
    {
        $reflector = new ReflectionClass(get_class($this));
        $properties = $reflector->getProperties();

        // Filter out any non-column properties
        $col_props = array_filter($properties, fn(ReflectionProperty $prop) => 
            count($prop->getAttributes(DataColumn::class)) > 0);

        return array_map(fn(ReflectionProperty $prop) => 
            ($prop->getName()), $col_props);
    }

    /**
     * Get object values as array
     * !!! DOES NOT INCLUDE PRIMARY KEY VALUE
     */
    public function get_update_values(): array 
    {
        $properties = $this->get_column_properties();

        $values = [];
        $primary_key_field = $this->get_primary_key_field();
        
        foreach($properties as $prop) {
            if($prop->getName() !== $primary_key_field) {
                $values[] = $prop->getValue($this);
            }
        }

        return $values;
    }

    /**
     * Get object fields as array
     * !!! DOES NOT INCLUDE PRIMARY KEY FIELD
     */
    public function get_update_fields(): array 
    {
        $properties = $this->get_column_properties();

        $fields = [];
        $primary_key_field = $this->get_primary_key_field();
        
        foreach($properties as $prop) {
            if($prop->getName() !== $primary_key_field) {
                $fields[] = $prop->getName();
            }
        }

        return $fields;
    }

    private function get_column_properties() 
    {
        $reflection = new ReflectionClass(get_class($this));
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        // Filter out any non-column properties
        return array_filter($properties, fn(ReflectionProperty $prop) => 
            count($prop->getAttributes(DataColumn::class)) > 0);
    }

    #region Statements

    protected function select(string $query = "", ?string $types = null, $values = []) {
        try {
            $stmt = $this->execute_statement($query, $types, $values);
            $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);				
            $stmt->close();
            return $result;
        } 
        catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }
    }

    protected function execute_statement(string $query = "", ?string $types = null, $values = []): mysqli_stmt
    {
        try {
            $stmt = $this->connection->prepare($query);

            if($stmt === false) {
                throw New Exception("Unable to do prepared statement: " . $query);
            }
            if($types !== null && count($values) > 0) {
                $stmt->bind_param($types, ...$values);
            }
            $stmt->execute();
            return $stmt;
        } 
        catch(Exception $e) {
            throw New Exception( $e->getMessage() );
        }	
    }

    protected function insert(): mysqli_stmt 
    {
        $sql = QueryGenerator::generate_insert_query($this);

        // Construct an array with values to bind
        $insert_values = $this->get_update_values();
        $types = str_repeat('s', count($insert_values));

        $stmt = $this->execute_statement($sql, $types, $insert_values);
        return $stmt;
    }

    protected function update(): mysqli_stmt 
    {
        $sql = QueryGenerator::generate_update_query($this);

        // Construct an array with values to bind
        $update_values = $this->get_update_values();
        $update_values[] = $this->get_primary_key_value();

        $types = str_repeat('s', count($update_values));

        $stmt = $this->execute_statement($sql, $types, $update_values);
        return $stmt;
    }

    #endregion
    #region Standard methods

    protected function std_get($id) : ObjectResult 
    {
        $result = new ObjectResult();
        try {
            $sql = QueryGenerator::generate_select_query($this);
            $data = $this->select($sql, "s", [$id]);
            $result = ObjectResult::from_data($data);
        }
        catch(Exception $e) {
            $result->set_error($e);
        }
        return $result;
    }

    protected function std_get_all() : DataResult 
    {
        $result = new DataResult();
        $table_name = $this->get_table_name();

        try {
            $sql = "SELECT * FROM " . $table_name;
            $data = $this->select($sql);
            $result->info = $sql;
            $result->data = $data;
            $result->success = true;
        }
        catch(Exception $e) {
            $result->set_error($e);
        }
        return $result;
    }

    protected function std_save() : Result 
    {
        $result = new Result();

        try {
            $primary_key = $this->get_primary_key_value();

            if(!$primary_key) {
                $stmt = $this->insert();
                $result->info = $this->connection->insert_id;
            }
            else {
                $stmt = $this->update();
                $result->info = $this->get_primary_key_value();
            }

            $result->success = true;
        }
        catch(Exception $e) {
            $result->set_error($e);
        }
        return $result;
    }
    #endregion
}

?>