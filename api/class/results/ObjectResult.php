<?php
class ObjectResult extends Result {
    public ?object $object = null;

    public static function from_data(array $data) : ObjectResult {
        $result = new ObjectResult();

        if($data == null || count($data) == 0) {
            $result->success = false;
            $result->info = "No result was found";
        } 
        else {
            $result->success = true;
            $result->object = (object)$data[0];
        }
        return $result;
    }

    public static function from_error(Throwable $e) : ObjectResult 
    {
        $r = new ObjectResult();
        $r->set_error($e);
        return $r;
    }
}
?>