<?php

/**
 * BcMath positive number
 *
 * @author Vaidas Lažauskas <vaidas@notrix.lt>
 */
class BcMath_PositiveNumber extends BcMath_Number
{
    /**
     * Checks if number is positive
     *
     * @param string $number
     *
     * @return bool
     */
    public function isValid($number)
    {
        return (boolean) preg_match('/^\d+$/', $number);
    }

    /**
     * Created positive number
     *
     * @param BcMath_NumberInterface|string $value
     *
     * @return BcMath_PositiveNumber
     */
    public static function create($value = null)
    {
        if ($value instanceof BcMath_NumberInterface) {
            $value = $value->getValue();
        }
        return new self($value);
    }
}
