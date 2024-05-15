<?php

namespace task2;

class Device
{
    public static function getExtendedXmlIfHasPermission($devicesList)
    {
        global $configValues;

        $userPermission = User::get_jogosultsag($_SESSION['userID']);

        if (!User::hasRightForOperation($userPermission, $configValues['ExtendExport']['accessPermission'])) {
            return [$devicesList, null];
        }

        $extendedResults = [];
        $errorOrderCodes = [];

        foreach ($devicesList as $device) {
            try {
                $extendedResults[] = self::isTypeOrConfigMissingFromOrderCode($device['OrderCode'], $configValues['CardOrder'])
                    ? self::extendDeviceDataWhenTypeOrConfigMissingFromOrderCode($device)
                    : self::extendDeviceDataWhenTypeAndConfigIsFoundInOrderCode($device);
            } catch (Exception $e) {
                $errorOrderCodes[] = $device['OrderCode'];
            }
        }

        return [$extendedResults, $errorOrderCodes];
    }

    public static function extendCardDataWithXmlData(array $devicesList): array
    {
        global $configValues;

        $userPermission = User::get_jogosultsag($_SESSION['userID']);

        if (!User::hasRightForOperation($userPermission, $configValues['ExtendExport']['accessPermission'])) {
            return [$devicesList, null];
        }

        $extendedResults = [];
        $errorOrderCodes = [];

        foreach ($devicesList as $device) {
            if (self::isTypeOrConfigMissingFromOrderCode($device['OrderCode'], $configValues['CardOrder'])) {
                $errorOrderCodes[] = $device['OrderCode'];
            } else {
                try {
                    $xml = self::getXmlFromApi($device['OrderCode']);
                    foreach ($xml->kartya as $card) {
                        $device['Kártya fül'] = $card->Title->__toString();
                        $device['Slot Geo'] = $card->Slot->__toString();
                        $device['Kártya Gyáriszám'] = '';
                        $extendedResults[] = $device;
                    }
                } catch (Exception $exception) {
                    $errorOrderCodes[] = $device['OrderCode'];
                }
            }
        }

        return [$extendedResults, $errorOrderCodes];
    }

    public static function isTypeOrConfigMissingFromOrderCode($orderCode, $cardOrder): bool
    {
        return strpos($orderCode, $cardOrder['Type']) !== false
            || strpos($orderCode, $cardOrder['Config']) !== false;
    }

    public static function extendDeviceDataWhenTypeOrConfigMissingFromOrderCode(mixed $device): array
    {
        $device['Típus'] = 'KR';
        $device['Méret'] = '';
        $device['Lemez'] = '';
        $device['Felszerelési mód'] = '';

        return $device;
    }

    public static function extendDeviceDataWhenTypeAndConfigIsFoundInOrderCode(mixed $device): array
    {
        $xml = self::getXmlFromApi($device['OrderCode']);

        $device['Típus'] = self::getTypeFromOrderCodeToExport($device['OrderCode']);
        $device['Méret'] = $xml->meret->__ToString();
        $device['Lemez'] = $xml->fedel->__ToString();
        $device['Felszerelési mód'] = $xml->felszerelesi_mod->__ToString();

        return $device;
    }
}