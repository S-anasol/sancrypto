<?php

require_once __DIR__ . '/CommandInterface.php';

/**
 * Subtraction command
 *
 * @author Vaidas Lažauskas <vaidas@notrix.lt>
 */
class BcMath_Command_Subtraction implements BcMatc_Command_CommandInterface
{
    /**
     * bcmath command
     */
    const COMMAND = 'bcsub';

    /**
     * Process subtraction command
     *
     * @param BcMath_Number         $left
     * @param BcMath_Number         $right
     * @param BcMath_PositiveNumber $scale
     *
     * @return BcMath_Number
     */
    public static function process(BcMath_Number $left, BcMath_Number $right, BcMath_PositiveNumber $scale)
    {
        $command = self::COMMAND;
        return BcMath_Number::create(
            $command($left->getValue(), $right->getValue(), $scale->getValue())
        );
    }

}
