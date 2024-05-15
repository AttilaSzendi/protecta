<?php

namespace task4;

class Foo
{
    public function bar(array $namePlateElements)
    {
        foreach ($namePlateElements as $type => $parameters) {
            switch ($type) {
                case "InUn":
                    $this->handleNamePlateElementType($parameters, $namePlateElements, $type);
                    break;
            }
        }
    }

    public function handleNamePlateElementType(array $parameters, array $namePlateElements, string $type): void
    {
        foreach ($parameters as $paramType => $values){
            $this->handleNamePlateElementTypeParameter($values, $paramType, $namePlateElements, $type);
        }
    }

    public function handleNamePlateElementTypeParameter(mixed $values, string $paramType, array $namePlateElements, string $type): void
    {
        foreach ($values as $key => $value) {
            $namePlateElements[$type][$paramType][$key] = self::addUnit($value, ($paramType == "Un" ? "V" : "A"));
        }
    }
}