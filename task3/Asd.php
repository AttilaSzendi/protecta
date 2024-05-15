<?php

namespace task3;

class Asd
{
    public function iDontKnowWhatItDoes(array $params, array $namePlateElements, string $type)
    {
        foreach ($params as $param)
        {
            $namePlateElementsType = $namePlateElements[$type] ?? [];

            $paramName = $namePlateElementsType[$param['Param_nev']] ?? [];

            if (!in_array($param['Param_ertek'], $paramName) )
                $namePlateElementsType[$param['Param_nev']][] = $param['Param_ertek'];
        }
    }
}