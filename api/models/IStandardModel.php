<?php

interface IStandardModel {
    public function get($id) : ObjectResult;
    public function get_all() : DataResult;
    public function save(array $params) : Result;
    //public function delete($id) : Result;
}

?>