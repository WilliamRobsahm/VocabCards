<?php
class DataResult extends Result {
    public array $data = [];

    public static function from_data(array $data) : DataResult {
        $result = new DataResult();

        if($data == null) {
            $result->info = "Data was null";
        } 
        else if(count($data) == 0) {
            $result->success = true;
            $result->info = "No results";
        }
        else {
            $result->success = true;
            $result->data = $data;
        }
        return $result;
    }

    public static function from_error(Throwable $e) : DataResult 
    {
        $r = new DataResult();
        $r->set_error($e);
        return $r;
    }
}
?>