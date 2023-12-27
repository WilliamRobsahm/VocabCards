<?php
require PROJECT_ROOT_PATH . "/models/LanguageModel.php";
class LanguageController extends BaseController
{
    /**
     * "/language/get" Endpoint - Get language from id
     */
    public function get_action() : void 
    {
        $this->require_request_method("GET");
        $this->require_params("id");

        $params = $this->get_query_string_params();
        $id = $params["id"];

        try {
            $model = new Language();
            $result = $model->get($id);
            $this->output_ok($result);
        } catch (Error $e) {
            $this->output_error_500(ObjectResult::from_error($e));
        }
    }
    
    /**
     * "/language/get_list" Endpoint - Get list of all languages
     */
    public function get_list_action() : void
    {
        try {
            $model = new Language();
            $result = $model->get_all();
            $this->output_ok($result);
        } catch (Error $e) {
            $this->output_error_500(DataResult::from_error($e));
        }
    }

    /**
     * "/language/save" Endpoint - Save country
     */
    public function save_action() : void 
    {
        $this->require_request_method("GET");

        $params = $this->get_query_string_params();

        try {
            $model = new Language();
            $result = $model->save($params);
            $this->output_ok($result);
        } catch (Error $e) {
            $this->output_error_500(Result::from_error($e));
        }
    }

    /**
     * "/language/search" Endpoint - Get languages that match search name
     */
    public function search_action() : void
    {
        $this->require_request_method("GET");
        $this->require_params("value");
        
        $params = $this->get_query_string_params();

        try {
            $model = new Language();
            $result = $model->search($params["value"]);
            $this->output_ok($result);
        } catch (Error $e) {
            $this->output_error_500(DataResult::from_error($e));
        }
    }
}