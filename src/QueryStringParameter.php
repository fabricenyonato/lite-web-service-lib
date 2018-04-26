<?php
namespace Lib;

require_once __DIR__.'/HTTPStatusCode.php';
require_once __DIR__.'/validators.php';

use Lib\HTTPStatusCode;


/* class QueryStringParameter {
    private $_key;
    private $_value;
    private $_required;

    public function __construct($key, $required = false, Type $value = null) {
        $this->setKey($key);
        $this->setRequired($required);
        $this->setValue($value);
    }

    public function validate() {
        if ($this->isRequired()) {
            if (!isset($_GET[$this->getKey()])) {
                http_response_code(HTTPStatusCode::BAD_REQUEST);
                echo json_encode(['error' => QueryStringParameter::class.'['.$this->getKey().'] is required']);
                exit;
            }
        }
        
        if (isset($_GET[$this->getKey()])) {
            $this->_value->setValue($_GET[$this->getKey()]);
        }
        
        if (!$this->_value->isValid()) {
            http_response_code(HTTPStatusCode::BAD_REQUEST);
            echo json_encode(['error' => QueryStringParameter::class.'['.$this->getKey().'] are not a '.$this->_value.' valid']);
            exit;
        }
        
        $_GET[$this->getKey()] = $this->getValue();
    }
    
    public function getValue() {
        return $this->_value->getValue();
    }

    public function getKey() {
        return $this->_key;
    }

    public function isRequired() {
        return $this->_required;
    }
    
    public function setKey($value) {
        $this->_key = $value;
    }
    
    public function setValue(Type $value) {
        $this->_value = $value;
    }
    
    public function setRequired($value) {
        $this->_required = $value;
    }
}*/

class QueryStringParameter {
    private $_key;
    private $_required = false;
    private $_validators = [];

    public function __construct($key, $required = false, $validators = null) {
        $this->key = $key;
        $this->required = $required;
        
        if (!is_null($validators)) {
            $this->_validators = is_array($validators) ? $validators : [$validators];
        }
    }

    public function validate() {
        if ($this->required && !isset($_GET[$this->key])) {
            http_response_code(HTTPStatusCode::BAD_REQUEST);
            echo json_encode(['error' => self::class.'['.$this->key.'] is required']);
            exit;
        }

        foreach ($this->_validators as $validator) {
            if (function_exists($validator)) {
                if (!$validator($_GET[$this->key])) {
                    http_response_code(HTTPStatusCode::BAD_REQUEST);
                    echo json_encode(['error' => self::class.'['.$this->key.'] has failed at '.$validator]);
                    exit;
                }
            }
            else {
                http_response_code(HTTPStatusCode::INTERNAL_SERVER_ERROR);
                echo json_encode(['error' => 'Function '.$validator.' not found']);
                exit;
            }
        }
    }

    public function __get($name) {
        if ($name === 'key') {
            return $this->_key;
        }

        if ($name === 'required') {
            return $this->_required;
        }
    }

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
