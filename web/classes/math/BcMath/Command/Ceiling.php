<?php

require_once __DIR__ . '/CommandInterface.php';

/**
 * Ceiling command
 *
 * @author Vaidas Lažauskas <vaidas@notrix.lt>
 */
class BcMath_Command_Ceiling implements BcMatc_Command_CommandInterface
{
    /**
     * Process ceiling command
     *
     * @param BcMath_Number         $number
     * @param BcMath_PositiveNumber $precision
     * @param BcMath_PositiveNumber $scale
     *
     * @return BcMath_Number
     */
    public static function process(BcMath_Number $number, BcMath_PositiveNumber $precision, BcMath_PositiveNumber $scale)
    {
        if (BcMath_Command_Comparison::process($number, BcMath_Number::create(0), BcMath_PositiveNumber::create($scale))->getValue() == 1) {
            $floored = BcMath_Command_Flooring::process($number, $precision);
            $add = BcMath_PositiveNumber::create(0);

            if (BcMath_Command_Comparison::process($number, $floored, BcMath_PositiveNumber::create($scale))->getValue() == 1) {
                $add = (BcMath_Command_Comparison::process($precision, BcMath_Number::create(0), BcMath_PositiveNumber::create($scale))->getValue() == 1) ?
                    self::getSmallNumber($precision) :
                    BcMath_PositiveNumber::create(1);
            }
            return BcMath_Command_Addition::process($number, $add, $precision);
        }
        return BcMath_Command_Subtraction::process($number, BcMath_Number::create(0), $precision);
    }

    /**
     * Gets number smaller than scale fits
     *
     * @param BcMath_PositiveNumber $scale
     *
     * @return BcMath_Number
     */
    protected static function getSmallNumber(BcMath_PositiveNumber $scale)
    {
        return BcMath_Command_Division::process(
            BcMath_Number::create(1),
            BcMath_Command_Power::process(BcMath_Number::create(10), $scale, BcMath_PositiveNumber::create(0)),
            BcMath_PositiveNumber::create($scale)
        );
    }
}
