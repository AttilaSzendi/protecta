<?php

namespace task2;

class FileComparisonHelper
{
    public function compare($filename_A, $filename_B): bool
    {
        $file_A = fopen($filename_A, 'rb');

        if (!$file_A) {
            return false;
        }

        $file_B = fopen($filename_B, 'rb');

        if (!$file_B) {
            fclose($file_A);
            return false;
        }

        $checksum_A = 0;

        while (!feof($file_A)) {
            $buffer = fread($file_A, 1024);
            $checksum_A = $this->myCheckSum($buffer, strlen($buffer), $checksum_A);
        }

        $checksum_B = 0;

        $bytesToCheck = filesize($filename_A);

        $bytesChecked = 0;

        while (!feof($file_B) && $bytesChecked < $bytesToCheck) {
            $buffer = fread($file_B, 1024);
            $bytesChecked += strlen($buffer);
            $checksum_B = $this->myCheckSum($buffer, strlen($buffer), $checksum_B);
        }

        fclose($file_A);
        fclose($file_B);

        return $checksum_A === $checksum_B;
    }

    protected function myCheckSum($buff, $len, $prevchk)
    {
        if ($len > 1024)
            return -1; // error (len > 1024

        $checksum = $prevchk;
        for ($i = 0; $i < $len; $i++)
        {
            $checksum += ord($buff[$i]);
        }

        return $checksum;
    }
}