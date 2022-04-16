<?php

namespace Oh\Model;

class IntezmenySzak
{
    private string $_egyetem;
    private string $_kar;
    private string $_szak;

    function __construct(string $egyetem, string $kar, string $szak)
    {
        $this->_egyetem = $egyetem;
        $this->_kar = $kar;
        $this->_szak = $szak;
    }

    public function getEgyetem(): string
    {
        return $this->_egyetem;
    }
    
    public function getKar(): string
    {
        return $this->_kar;
    }

    public function getSzak(): string
    {
        return $this->_szak;
    }

    public function isEqual(IntezmenySzak $details): bool
    {
        $reflection = new \ReflectionClass(self::class);

        /** @var \ReflectionProperty $property */
        foreach ($reflection->getProperties() as $property) {
            $name = $property->getName();
            if ($this->$name !== $details->$name) {
                return false;
            }
        }

        return true;
    }
}
