<?php

require_once __DIR__ . '/CommandInterface.php';

/**
 * Flooring command
 *
 * @author Vaidas Lažauskas <vaidas@notrix.lt>
 */
class BcMath_Command_Flooring implements BcMatc_Command_CommandInterface
{
    /**
     * Process flooring command
     *
     * @param BcMath_Number         $number
     * @param BcMath_PositiveNumber $scale
     *
     * @return BcMath_Number
     */
    public static function process(BcMath_Number $number, BcMath_PositiveNumber $scale)
    {
        $multiplier = BcMath_Command_Power::process(BcMath_Number::create(10), $scale, BcMath_PositiveNumber::create(0));

        $number = BcMath_Command_Multiplication::process($number, $multiplier, BcMath_PositiveNumber::create(0));
        $operand = (BcMath_Command_Comparison::process($number, BcMath_Number::create(0), BcMath_PositiveNumber::create($scale))->getValue() == 1) ?
            BcMath_Command_Addition::process($number, BcMath_PositiveNumber::create(0), BcMath_PositiveNumber::create(0)) :
            BcMath_Command_Subtraction::process($number, BcMath_PositiveNumber::create(1), BcMath_PositiveNumber::create(0));

        return BcMath_Command_Division::process($operand, $multiplier, $scale);
    }
}
