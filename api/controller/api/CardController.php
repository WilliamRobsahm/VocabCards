<?php
require PROJECT_ROOT_PATH . "/models/CardModel.php";
class CardController extends BaseController
{
    /**
     * "/card/get" Endpoint - Get card from id
     */
    public function get_action() : void 
    {
        $this->require_request_method("GET");
        $this->require_params("id");

        $params = $this->get_query_string_params();
        $id = $params["id"];

        try {
            $model = new Card();
            $result = $model->get($id);
            $this->output_ok($result);
        } catch (Error $e) {
            $this->output_error_500(ObjectResult::from_error($e));
        }
    }
    
    /**
     * "/card/get_list" Endpoint - Get list of all cards
     */
    public function get_list_action() : void
    {
        try {
            $model = new Card();
            $result = $model->get_all();
            $this->output_ok($result);
        } catch (Error $e) {
            $this->output_error_500(DataResult::from_error($e));
        }
    }

    public function get_for_language_action() : void 
    {
        $this->require_request_method("GET");
        $this->require_params("lang");

        $params = $this->get_query_string_params();

        try {
            $model = new Card();
            $result = $model->get_all_for_language($params["lang"]);
            $this->output_ok($result);
        } catch (Error $e) {
            $this->output_error_500(DataResult::from_error($e));
        }
    }

    /**
     * "/card/save" Endpoint - Save card
     */
    public function save_action() : void 
    {
        $this->require_request_method("GET");

        $params = $this->get_query_string_params();

        try {
            $model = new Card();
            $result = $model->save($params);
            $this->output_ok($result);
        } catch (Error $e) {
            $this->output_error_500(Result::from_error($e));
        }
    }
}