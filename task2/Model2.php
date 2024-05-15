<?php

namespace task2;

class Model2
{
    public function __construct(protected readonly Database $database)
    {
    }

    public function updateOrCreate(string $sql, array $request)
    {
        $model = $this->database->sqlQuery($sql);

        if (!$model) {
            $sql = "
            INSERT INTO
                `FulKartyaCim`
            SET" . $this->buildFieldValuePairsAsQueryString(['Ful', 'KartyaCim', 'oregszik', 'Mask_HW'], $request['FulkartyaCim']);

            return $this->database->sqlQuery($sql);
        }

        if ($this->getDirtyFields($model[0], $request['FulkartyaCim'], ['KartyaCim', 'oregszik', 'Mask_HW'])) {
            $sql = "
                UPDATE
					`FulKartyaCim`
				SET " . $this->buildFieldValuePairsAsQueryString(['KartyaCim', 'oregszik', 'Mask_HW'], $request['FulkartyaCim']) . "
				WHERE
					`Ful` = '" . $request['FulkartyaCim']['Ful'] . "'
            ";

            return $this->database->sqlQuery($sql);
        }

        return null;
    }

    protected function buildFieldValuePairsAsQueryString(array $fields, array $input): string
    {
        $fieldValuePairsAsQueryString = '';

        foreach ($fields as $field) {
            $fieldValuePairsAsQueryString .= "`{$field}` = '" . $input[$field] . "', ";
        }

        return $fieldValuePairsAsQueryString;
    }

    public function getDirtyFields(array $model, array $fulkartyaCim, array $fieldsToCheck): array
    {
        $dirtyFields = [];

        foreach ($fieldsToCheck as $field) {
            if($model[$field] != $fulkartyaCim[$field]){
                $dirtyFields[] = $field;
            }
        }

        return $dirtyFields;
    }
}