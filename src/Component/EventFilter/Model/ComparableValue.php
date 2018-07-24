<?php

namespace Component\EventFilter\Model;

/**
 * Class ComparableValue
 *
 * @package Component\EventFilter\Model
 */
class ComparableValue
{

    const OPERATOR_EQ = '=';
    const OPERATOR_NEQ = '!=';
    const OPERATOR_GT = '>';
    const OPERATOR_GTE = '>=';
    const OPERATOR_LT = '<';
    const OPERATOR_LTE = '<=';

    /**
     * @var array
     */
    const AVAILABLE_OPERATORS = [
        self::OPERATOR_EQ,
        self::OPERATOR_NEQ,
        self::OPERATOR_GT,
        self::OPERATOR_GTE,
        self::OPERATOR_LT,
        self::OPERATOR_LTE,
    ];

    /**
     * @var integer
     */
    private $value;

    /**
     * @var string
     */
    private $operator;

    /**
     * ComparableValue constructor.
     *
     * @param integer $value    Compared value.
     * @param string  $operator Comparision operator.
     */
    public function __construct(int $value, string $operator)
    {
        $this->value = $value;

        if (! in_array($operator, self::AVAILABLE_OPERATORS, true)) {
            throw new \InvalidArgumentException(sprintf(
                'Unknown operator %s, expects one of %s',
                $operator,
                implode(', ', self::AVAILABLE_OPERATORS)
            ));
        }
        $this->operator = $operator;
    }

    /**
     * @return integer
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getOperator()
    {
        return $this->operator;
    }

    /**
     * @param string $operator Expected operator.
     *
     * @return boolean
     */
    public function isOperator(string $operator)
    {
        if (! in_array($operator, self::AVAILABLE_OPERATORS, true)) {
            throw new \InvalidArgumentException(sprintf(
                'Unknown operator %s, expects one of %s',
                $operator,
                implode(', ', self::AVAILABLE_OPERATORS)
            ));
        }

        return $this->operator === $operator;
    }
}
