<?php
require PROJECT_ROOT_PATH . "/models/DefinitionModel.php";
class DefinitionController extends BaseController
{
    /**
     * "/definition/get" Endpoint - Get definition from id
     */
    public function get_action() : void 
    {
        $this->require_request_method("GET");
        $this->require_params("id");

        $params = $this->get_query_string_params();
        $id = $params["id"];

        try {
            $model = new Definition();
            $result = $model->get($id);
            $this->output_ok($result);
        } catch (Error $e) {
            $this->output_error_500(ObjectResult::from_error($e));
        }
    }
    
    /**
     * "/definition/get_list" Endpoint - Get list of all definitions
     */
    public function get_list_action() : void
    {
        try {
            $model = new Definition();
            $result = $model->get_all();
            $this->output_ok($result);
        } catch (Error $e) {
            $this->output_error_500(DataResult::from_error($e));
        }
    }

    public function get_for_card_action() : void 
    {
        $this->require_request_method("GET");
        $this->require_params("card_id", "lang");

        $params = $this->get_query_string_params();
        $id = $params["card_id"];
        $lang = $params["lang"];

        try {
            $model = new Definition();
            $result = $model->get_all_for_card($id, $lang);
            $this->output_ok($result);
        } catch (Error $e) {
            $this->output_error_500(DataResult::from_error($e));
        }
    }

    public function get_for_language_action() : void 
    {
        $this->require_request_method("GET");
        $this->require_params("card_lang", "lang");

        $params = $this->get_query_string_params();
        $clang = $params["card_lang"];
        $dlang = $params["lang"];

        try {
            $model = new Definition();
            $result = $model->get_all_for_language($clang, $dlang);
            $this->output_ok($result);
        } catch (Error $e) {
            $this->output_error_500(DataResult::from_error($e));
        }
    }

    /**
     * "/definition/save" Endpoint - Save definition
     */
    public function save_action() : void 
    {
        $this->require_request_method("GET");

        $params = $this->get_query_string_params();

        try {
            $model = new Definition();
            $result = $model->save($params);
            $this->output_ok($result);
        } catch (Error $e) {
            $this->output_error_500(Result::from_error($e));
        }
    }
}