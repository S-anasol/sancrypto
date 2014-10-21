<?php

require_once __DIR__ . '/NumberInterface.php';
require_once __DIR__ . '/Exception/NumberException.php';

/**
 * BcMath number
 *
 * @author Vaidas Lažauskas <vaidas@notrix.lt>
 */
class BcMath_Number implements BcMath_NumberInterface
{
    /**
     * @var string
     */
    protected $value;

    /**
     * Number constructor
     *
     * @param string $value
     */
    public function __construct($value = null)
    {
        $this->setValue($value);
    }

    /**
     * Setter of Value
     *
     * @param string $value
     *
     * @return BcMath_Number
     *
     * @throws BcMath_Exception_NumberException
     */
    public function setValue($value)
    {
        if (!$this->isValid($value)) {
			//var_dump($value);
			$value = 0;
            //throw new BcMath_Exception_NumberException('Wrong number format: '.$value);
        }
        $this->value = $value;
        return $this;
    }

    /**
     * Getter of Value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Checks if number is valid for bcmath operations
     *
     * @param string $number
     *
     * @return bool
     */
    public function isValid($number)
    {
        return (boolean) preg_match('/^\-?\d+(\.\d+)?$/', $number);
    }

    /**
     * Created bcmath number
     *
     * @param BcMath_NumberInterface|string $value
     *
     * @return BcMath_Number
     */
    public static function create($value = null)
    {
        if ($value instanceof BcMath_NumberInterface) {
            $value = $value->getValue();
        }
        return new self($value);
    }
}
