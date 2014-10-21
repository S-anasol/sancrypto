<?php

/**
 * BcMath number interface
 *
 * @author Vaidas Lažauskas <vaidas@notrix.lt>
 */
interface BcMath_NumberInterface
{
    /**
     * Setter of Value
     *
     * @param string $value
     *
     * @return self
     */
    public function setValue($value);

    /**
     * Getter of Value
     *
     * @return string
     */
    public function getValue();

    /**
     * Checks if number is valid
     *
     * @param string $number
     *
     * @return int
     */
    public function isValid($number);

    /**
     * Created bcmath number
     *
     * @param BcMath_NumberInterface|string $value
     *
     * @return self
     */
    public static function create($value = null);
}
