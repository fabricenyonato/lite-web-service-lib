<?php
interface iType {
    public function __construct($value = null);
    public static function fromInstance($instance);
    public function getValue();
    public function setValue($value);
    public function isValid();
}

interface iNumber extends iType {
    public function __construct($value = null, $min = null, $max = null);
    public function getMin();
    public function getMax();
    public function setMin($value);
    public function setMax($value);
}

abstract class Type implements iType {
    protected $_value = null;
    protected $_valid = true;
    
    /**
     * @return mixed
     */
    public function getValue() {
        return $this->_value;
    }
    
    public function setValue($value) {
        $this->_value = $value;
    }

    /**
     * @return bool
     */
    public function isValid() {
        return $this->_valid;
    }
}

class String_ extends Type {
    public function __construct($value = null) {
        $this->_value = (string) $value;
    }
    
    public static function fromInstance($instance) {
        return new String_($instance->getValue());
    }
    
    public function __toString() {
        return get_class($this);
    }
}

class Integer extends Type implements iNumber {
    protected $_min;
    protected $_max;
    /**
     * @param int|string $value
     * @param int $min
     * @param int $max
     */
    public function __construct($value = null, $min = null, $max = null) {
        $this->setMin($min);
        $this->setMax($max);
        $this->setValue($value);
    }
    
    public static function fromInstance($instance) {
        $class = new ReflectionClass(self::class);
        return $class->newInstance($instance->getValue(), $instance->getMin(), $instance->getMax());
    }
    
    public function setValue($value) {
        if (!is_null($value)) {
            $this->_valid = false;
            
            if (is_numeric($value)) {
                $value = (int) $value;
                
                if ($this->_range($value, $this->getMin(), $this->getMax())) {
                    $this->_value = $value;
                    $this->_valid = true;
                }
            }
        }
    }

    /**
     * @param number $value
     * @return bool
     */
    protected function _range($value) {
        if (!is_null($this->getMin()) && is_null($this->getMax())) {
            return $this->getMin() < $value;
        }

        if (is_null($this->getMin()) && !is_null($this->getMax())) {
            return $value < $this->getMax();
        }

        return !is_null($this->getMin()) && !is_null($this->getMax()) ? ($this->getMin() < $value) && ($value < $this->getMax()) : true;
    }
    
    public function getMin() {
        return $this->_min;
    }
    
    public function getMax() {
        return $this->_max;
    }
    
    public function setMin($value) {
        if (!is_null($value)) {
            if (is_null($this->getMax())) {
            $this->_min = $value;
            }
            else if ($value < $this->getMax()) {
                $this->_min = $value;
            }
        }
        
    }
    
    public function setMax($value) {
        if (!is_null($value)) {
            if (is_null($this->getMin())) {
                $this->_max = $value;
            }
            else if ($value > $this->getMin()) {
                $this->_max = $value;
            }
        }
    }
    
    public function __toString() {
        return get_class($this).'[min='.$this->getMin().', max='.$this->getMax().']';
    }
}

class Decimal extends Integer {
    /**
     * @param decimal|string $value
     * @param decimal $min
     * @param decimal $max
     */
    public function __construct($value = null, $min = null, $max = null) {
        $this->setMin($min);
        $this->setMax($max);
        $this->setValue($value);
    }
    
    public function setValue($value) {
        if (!is_null($value)) {
            $this->_valid = false;
            
            if (is_numeric($value)) {
                $value = (float) $value;
                
                if ($this->_range($value, $this->getMin(), $this->getMax())) {
                    $this->_value = $value;
                    $this->_valid = true;
                }
            }
            else {
                parent::__construct($value, $this->getMin(), $this->getMax());
            }
        }
    }
}
