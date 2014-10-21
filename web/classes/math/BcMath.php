<?php

require_once __DIR__ . '/BcMath/Number.php';
require_once __DIR__ . '/BcMath/PositiveNumber.php';
require_once __DIR__ . '/BcMath/Command/Addition.php';
require_once __DIR__ . '/BcMath/Command/Subtraction.php';
require_once __DIR__ . '/BcMath/Command/Multiplication.php';
require_once __DIR__ . '/BcMath/Command/Division.php';
require_once __DIR__ . '/BcMath/Command/Power.php';
require_once __DIR__ . '/BcMath/Command/Comparison.php';
require_once __DIR__ . '/BcMath/Command/Rounding.php';
require_once __DIR__ . '/BcMath/Command/Flooring.php';
require_once __DIR__ . '/BcMath/Command/Ceiling.php';

/**
 * bcmath functions wrapper
 *
 * @author Vaidas Lažauskas <vaidas@notrix.lt>
 */
class BcMath
{
    /**
     * @var BcMath_PositiveNumber
     */
    protected $scale;

    /**
     * Class constructor
     *
     * @param BcMath_PositiveNumber|int $scale
     */
    public function __construct($scale = null)
    {
        $this->setScale($scale);
    }

    /**
     * Setter of Scale
     *
     * @param BcMath_PositiveNumber|int $scale
     *
     * @return \BcMath
     *
     * @throws BcMath_Exception_NumberException
     */
    public function setScale($scale)
    {
        if (!$scale instanceof BcMath_PositiveNumber) {
            $scale = BcMath_PositiveNumber::create($scale);
        }
        $this->scale = $scale;
        return $this;
    }

    /**
     * Getter of Scale
     *
     * @return BcMath_PositiveNumber
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * Add two arbitrary precision numbers
     *
     * @param BcMath_Number|string $leftOperand
     * @param BcMath_Number|string $rightOperand
     *
     * @return BcMath_Number The sum of the two operands, as a string.
     */
    public function addition($leftOperand, $rightOperand)
    {
        if (!$leftOperand instanceof BcMath_NumberInterface) {
            $leftOperand = BcMath_Number::create($leftOperand);
        }
        if (!$rightOperand instanceof BcMath_NumberInterface) {
            $rightOperand = BcMath_Number::create($rightOperand);
        }
        return BcMath_Command_Addition::process($leftOperand, $rightOperand, $this->getScale())->getValue();
    }

    /**
     * Subtract one arbitrary precision number from another
     *
     * @param BcMath_Number|string $leftOperand
     * @param BcMath_Number|string $rightOperand
     *
     * @return BcMath_Number The result of the subtraction, as a string.
     */
    public function subtraction($leftOperand, $rightOperand)
    {
        if (!$leftOperand instanceof BcMath_NumberInterface) {
            $leftOperand = BcMath_Number::create($leftOperand);
        }
        if (!$rightOperand instanceof BcMath_NumberInterface) {
            $rightOperand = BcMath_Number::create($rightOperand);
        }
        return BcMath_Command_Subtraction::process($leftOperand, $rightOperand, $this->getScale())->getValue();
    }

    /**
     * Get modulus of an arbitrary precision number
     *
     * @param BcMath_Number|string $leftOperand
     * @param BcMath_Number|string $rightOperand
     *
     * @return string the modulus as a string, or null if modulus is 0.
     */
    public function division($leftOperand, $rightOperand)
    {
        if (!$leftOperand instanceof BcMath_NumberInterface) {
            $leftOperand = BcMath_Number::create($leftOperand);
        }
        if (!$rightOperand instanceof BcMath_NumberInterface) {
            $rightOperand = BcMath_Number::create($rightOperand);
        }
        return BcMath_Command_Division::process($leftOperand, $rightOperand, $this->getScale())->getValue();
    }

    /**
     * Multiply two arbitrary precision number
     *
     * @param BcMath_Number|string $leftOperand
     * @param BcMath_Number|string $rightOperand
     *
     * @return string the result as a string.
     */
    public function multiplication($leftOperand, $rightOperand)
    {
        if (!$leftOperand instanceof BcMath_NumberInterface) {
            $leftOperand = BcMath_Number::create($leftOperand);
        }
        if (!$rightOperand instanceof BcMath_NumberInterface) {
            $rightOperand = BcMath_Number::create($rightOperand);
        }
        return BcMath_Command_Multiplication::process($leftOperand, $rightOperand, $this->getScale())->getValue();
    }

    /**
     * Power two arbitrary precision number
     *
     * @param BcMath_Number|string $leftOperand
     * @param BcMath_Number|string $rightOperand
     *
     * @return string the result as a string.
     */
    public function power($leftOperand, $rightOperand)
    {
        if (!$leftOperand instanceof BcMath_NumberInterface) {
            $leftOperand = BcMath_Number::create($leftOperand);
        }
        if (!$rightOperand instanceof BcMath_NumberInterface) {
            $rightOperand = BcMath_Number::create($rightOperand);
        }
        return BcMath_Command_Power::process($leftOperand, $rightOperand, $this->getScale())->getValue();
    }

    /**
     * Compare two arbitrary precision numbers
     *
     * @param BcMath_Number|string $leftOperand
     * @param BcMath_Number|string $rightOperand
     *
     * @return int
     *      0 if the two operands are equal,
     *      1 if the leftOperand is larger than the rightOperand,
     *     -1 otherwise.
     */
    public function comparison($leftOperand, $rightOperand)
    {
        if (!$leftOperand instanceof BcMath_NumberInterface) {
            $leftOperand = BcMath_Number::create($leftOperand);
        }
        if (!$rightOperand instanceof BcMath_NumberInterface) {
            $rightOperand = BcMath_Number::create($rightOperand);
        }
        return BcMath_Command_Comparison::process($leftOperand, $rightOperand, $this->getScale())->getValue();
    }

    /**
     * Round arbitrary precision number
     *
     * @param BcMath_Number|string         $operand
     * @param BcMath_PositiveNumber|int|string $precision
     *
     * @return string the result as a string.
     */
    public function rounding($operand, $precision = 0)
    {
        if (!$operand instanceof BcMath_NumberInterface) {
            $operand = BcMath_Number::create($operand);
        }
        if (!$precision instanceof BcMath_NumberInterface) {
            $precision = BcMath_PositiveNumber::create($precision);
        }
        return BcMath_Command_Rounding::process($operand, $precision)->getValue();
    }

    /**
     * Ceil arbitrary precision number
     *
     * @param string $operand
     * @param int    $precision
     *
     * @return string the result as a string.
     */
    public function ceiling($operand, $precision = 0)
    {
        if (!$operand instanceof BcMath_NumberInterface) {
            $operand = BcMath_Number::create($operand);
        }
        if (!$precision instanceof BcMath_NumberInterface) {
            $precision = BcMath_PositiveNumber::create($precision);
        }
        return BcMath_Command_Ceiling::process($operand, $precision, $this->getScale())->getValue();
    }

    /**
     * Floor arbitrary precision number
     *
     * @param string $operand
     * @param int    $precision
     *
     * @return string the result as a string.
     */
    public function flooring($operand, $precision = 0)
    {
        if (!$operand instanceof BcMath_NumberInterface) {
            $operand = BcMath_Number::create($operand);
        }
        if (!$precision instanceof BcMath_NumberInterface) {
            $precision = BcMath_PositiveNumber::create($precision);
        }
        return BcMath_Command_Flooring::process($operand, $precision)->getValue();
    }
}
