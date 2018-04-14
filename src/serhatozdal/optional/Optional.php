<?php
namespace serhatozdal\optional;
/**
 * @author serhatozdal
 */
final class Optional
{
    private $value;

    private function __construct($value)
    {
        $this->value = $value;
    }

    public static function ofEmpty()
    {
        return new self(null);
    }

    public static function of($value)
    {
        return new self(self::requireNonNull($value));
    }

    public static function ofNullable($value)
    {
        return $value === null ? self::ofEmpty() : self::of($value);
    }

    public function get()
    {
        return self::requireNonNull($this->value);
    }

    public function isPresent()
    {
        return $this->value !== null;
    }

    public function ifPresent(callable $consumer)
    {
        if ($this->isPresent())
            $consumer($this->value);
    }

    public function filter(callable $predicate)
    {
        if (!$this->isPresent())
            return $this;
        return $predicate($this->value) ? $this : self::ofEmpty();
    }

    public function map(callable $mapper)
    {
        if (!$this->isPresent())
            return self::ofEmpty();
        return self::ofNullable($mapper($this->value));
    }

    public function flatMap(callable $mapper)
    {
        if (!$this->isPresent())
            return self::ofEmpty();
        return self::requireNonNull($mapper($this->value));
    }

    public function orElse($other)
    {
        return $this->isPresent() ? $this->value : $other;
    }

    public function orElseGet(callable $other)
    {
        return $this->isPresent() ? $this->value : $other();
    }

    public function orElseThrow(callable $exceptionSupplier)
    {
        return $this->isPresent() ? $this->value : $exceptionSupplier();
    }

    private static function requireNonNull($obj)
    {
        if ($obj === null)
            throw new \InvalidArgumentException("variable can not be null!!");
        return $obj;
    }

    public function __toString()
    {
        return $this->isPresent() ? sprintf("Optional[%s]", $this->value) : "Optional.ofEmpty";
    }
}
