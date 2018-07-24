<?php

namespace app\op;

/**
 * @param mixed  $value A checked value.
 * @param string $class Expected class.
 *
 * @return boolean
 */
// @codingStandardsIgnoreStart
function isInstanceOf($value, $class)
{
    return $value instanceof $class;
}

// @codingStandardsIgnoreStart
const isInstanceOf = 'app\op\isInstanceOf';
// @codingStandardsIgnoreEnd
