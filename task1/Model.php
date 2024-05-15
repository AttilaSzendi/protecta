<?php

namespace task1;

class Model
{
    protected string $primaryKey = 'TypeId';

    public function __construct(protected readonly Database $database)
    {
    }

    /** I did not understand the $doesNotForceUpdate parameter, so I just removed it. */
    public function update(array $query, array $dataToStore, array $doNotUpdateFields)
    {
        $fieldValuePairsAsString = $this->getFieldValuePairsAsString($dataToStore['AllFields'], $query[0], $doNotUpdateFields);

        if ($fieldValuePairsAsString) {
            $sql = "UPDATE `Records` SET $fieldValuePairsAsString WHERE `TypeId` =" . $dataToStore['AllFields']['TypeId'];

            $this->database->sqlQuery($sql);
        }
    }
    public function getFieldValuePairsAsString(array $allFields, array $actualRecord, array $doNotUpdateFields): string
    {
        $fieldValuePairsAsString = "";

        foreach ($allFields as $fieldName => $value) {
            if ($fieldName === $this->primaryKey) {
                continue;
            }

            if ($actualRecord[$fieldName] === $value) {
                continue;
            }

            if (!in_array($fieldName, $doNotUpdateFields)) {
                $fieldValuePairsAsString .= ($fieldValuePairsAsString != "" ? "," : "") . "`" . $fieldName . "` = '" . $value . "'";
            }
        }

        return $fieldValuePairsAsString;
    }
}