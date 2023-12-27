<?php
class Result {
    public bool $success = false;
    public ?string $info = null;

    public function set_error(Throwable $e) 
    {
        $this->success = false;
        $this->info = $e->getMessage();
    }

    public static function from_error(Throwable $e) : Result
    {
        $r = new Result();
        $r->set_error($e);
        return $r;
    }
}
?>