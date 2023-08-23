<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OuiData;

class MacLookupController extends Controller
{
    public function lookupSingle(Request $request)
    {
        $macAddress = $this->normalizeMacAddress($request->input('mac_address'));
        $macAddress = $request->mac_address;
        // Exclude known random MAC address patterns
        if (!in_array(substr($macAddress, 1, 1), ['2', '6', 'A', 'E'])) {
            $vendor = OuiData::where('assignment', $macAddress)->first();

            if ($vendor) {
                return response()->json([
                    'mac_address' => $macAddress,
                    'vendor' => $vendor->organisation_name,
                ]);
            }
        }

        return response()->json([
            'mac_address' => $macAddress,
            'vendor' => 'Unknown',
        ]);
    }

    public function lookupMultiple(Request $request)
    {
        // dd("");
        $macAddresses = $request->input('mac_addresses', []);

        $normalizedMacAddresses = array_map([$this, 'normalizeMacAddress'], $macAddresses);
        return $normalizedMacAddresses;
        $vendors = OuiData::whereIn('assignment', $normalizedMacAddresses)->get();

        $result = [];
        foreach ($vendors as $vendor) {
            $macAddress = $vendor->assignment;

            // Exclude known random MAC address patterns
            if (!in_array(substr($macAddress, 1, 1), ['2', '6', 'A', 'E'])) {
                $result[] = [
                    'mac_address' => $macAddress,
                    'vendor' => $vendor->organisation_name,
                ];
            }else{
                $result[] = [
                    'mac_address' => $macAddress,
                    'vendor' => "Unknown"
                ];
            }
        }

        return response()->json($result);
    }



    protected function normalizeMacAddress($macAddress)
    {
        // Normalize the MAC address by removing separators and converting to uppercase
        $macAddress = strtoupper(preg_replace('/[^A-F0-9]/', '', $macAddress));

        // Normalize different formats to a consistent format (e.g., XX:XX:XX:XX:XX:XX)
        $macAddress = implode(':', str_split($macAddress, 2));

        return $macAddress;
    }

}
