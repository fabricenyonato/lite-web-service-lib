<?php
namespace LiteWebServiceLib;

require_once __DIR__.'/HTTPStatusCode.php';
require_once __DIR__.'/validators.php';

use LiteWebServiceLib\HTTPStatusCode;


class QueryStringParameter {
    private $_key;
    private $_required = false;
    private $_validators = [];

    public function __construct($key, $required = false, array $validators = []) {
        $this->key = $key;
        $this->required = $required;
        $this->_validators = $validators;
    }

    /**
     * @return void
     */
    public function validate() {
        if ($this->required && !isset($_GET[$this->key])) {
            http_response_code(HTTPStatusCode::BAD_REQUEST);
            echo json_encode(['error' => self::class.'['.$this->key.'] is required']);
            exit;
        }

        if (!isset($_GET[$this->key])) {
            return;
        }

        foreach ($this->_validators as $validator) {
            $args = [&$_GET[$this->key]];

            if (is_array($validator)) {
                $func = $validator[0];
                $args = array_merge($args, array_slice($validator, 1));
            }
            else {
                $func = $validator;
            }

            if (function_exists($func)) {
                if (!call_user_func_array($func, $args)) {
                    http_response_code(HTTPStatusCode::BAD_REQUEST);
                    echo json_encode(['error' => self::class.'['.$this->key.'] has failed at '.$func]);
                    exit;
                }
            }
            else {
                http_response_code(HTTPStatusCode::INTERNAL_SERVER_ERROR);
                echo json_encode(['error' => 'Function '.$func.' not found']);
                exit;
            }
        }
    }

    /**
     * @param mixed $name
     * @return mixed
     */
    public function __get($name) {
        if ($name === 'key') {
            return $this->_key;
        }

        if ($name === 'required') {
            return $this->_required;
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set($name, $value) {
        if ($name === 'key') {
            $this->_key = $value;
            return;
        }

        if ($name === 'required') {
            $this->_required = $value;
            return;
        }
    }
}
