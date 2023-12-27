<?php
class BaseController
{
    /**
     * __call magic method.
     */
    public function __call($name, $arguments)
    {
        $this->send_output('', array('HTTP/1.1 404 Not Found'));
    }

    /**
     * Get querystring params.
     */
    protected final function get_query_string_params() : array
    {
        parse_str($_SERVER['QUERY_STRING'], $query);
        return $query;
    }
    
    #region Request validation
    
    /**
     * Output an error if any of the required params are unset
     */
    protected function require_params(string ...$required_params) : void 
    {
        $query_params = $this->get_query_string_params();
        $missing_params = [];
        foreach($query_params as $key => $value) {
            if(array_search($key, $required_params) === false) {
                array_push($missing_params, $key);
            }
        }
        if (count($missing_params) > 0) {
            $result = new Result();
            $result->info = 'Missing required params: ' . implode(", ", $missing_params);
            $this->output_error_500($result);
        }
    }

    /**
     * Output an error if request method is not supported
     */
    protected function require_request_method(string $supported_method) : void
    {
        $req_method = strtoupper($_SERVER["REQUEST_METHOD"]);
        if ($req_method != strtoupper($supported_method)) {
            $result = new Result();
            $result->info = "Method not supported";
            $this->output_error_422($result);
        }
    }

    #endregion
    #region Output methods

    /**
     * Send API output.
     * (EXITS SCRIPT)
     */
    private function send_output($data, array $httpHeaders = []) : void
    {
        header_remove('Set-Cookie'); // ?
        if (is_array($httpHeaders) && count($httpHeaders)) {
            foreach ($httpHeaders as $httpHeader) {
                header($httpHeader);
            }
        }
        echo $data;
        exit;
    }

    /**
     * Encode result object and output it
     */
    protected final function output_ok(Result $result) : void {
        $this->send_output(json_encode($result), [CONTENT_TYPE_JSON, HEADER_OK]);
    }

    private function output_error(Result $result, string $header_error) : void {
        $this->send_output(json_encode($result), [CONTENT_TYPE_JSON, $header_error]);
    }

    /** Send API error 500 (WILL EXIT SCRIPT) */
    protected final function output_error_500(Result $result) : void { 
        $this->output_error($result, HEADER_ERROR_500); 
    }
    /** Send API error 422 (WILL EXIT SCRIPT) */
    protected final function output_error_422(Result $result) : void { 
        $this->output_error($result, HEADER_ERROR_422); 
    }

    #endregion
}