<?php

require_once __DIR__ . '/CommandInterface.php';

/**
 * Rounding command
 *
 * @author Vaidas Lažauskas <vaidas@notrix.lt>
 */
class BcMath_Command_Rounding implements BcMatc_Command_CommandInterface
{
    /**
     * Process rounding command
     *
     * @param BcMath_Number         $number
     * @param BcMath_PositiveNumber $scale
     *
     * @return BcMath_Number
     */
    public static function process(BcMath_Number $number, BcMath_PositiveNumber $scale)
    {
        $newScale = BcMath_Command_Addition::process($scale, BcMath_Number::create(1), BcMath_PositiveNumber::create(0));
        return (BcMath_Command_Comparison::process($number, BcMath_Number::create(0), BcMath_PositiveNumber::create($newScale))->getValue() >= 0) ?
            BcMath_Command_Addition::process($number, self::getSmallNumber(BcMath_PositiveNumber::create($scale)), $scale) :
            BcMath_Command_Subtraction::process($number, self::getSmallNumber(BcMath_PositiveNumber::create($scale)), $scale);
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
        $newScale = BcMath_Command_Addition::process($scale, BcMath_Number::create(1), BcMath_PositiveNumber::create(0));
        return BcMath_Command_Division::process(
            BcMath_Number::create(5),
            BcMath_Command_Power::process(BcMath_Number::create(10), $newScale, BcMath_PositiveNumber::create(0)),
            BcMath_PositiveNumber::create($newScale)
        );
    }
}
