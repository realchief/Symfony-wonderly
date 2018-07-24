<?php

namespace app\a;

use function \nspl\a\all;
use function \nspl\f\rpartial;

use const \app\op\isInstanceOf;

/**
 * @param array  $sequence A checked sequence.
 * @param string $class    Expected class.
 *
 * @return void
 *
 * @throws \InvalidArgumentException Got unexpected item.
 */
// @codingStandardsIgnoreStart
function validateSequenceIsInstanceOf(array $sequence, $class)
{
// @codingStandardsIgnoreEnd
    if (! all($sequence, rpartial(isInstanceOf, $class))) {
        throw new \InvalidArgumentException("Expects array of {$class} instances.");
    }
}
