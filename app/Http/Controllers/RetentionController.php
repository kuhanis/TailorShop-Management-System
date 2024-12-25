<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Orders;
use App\Models\BodyMeasurement;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RetentionController extends Controller
{
    public function updateRetentionSettings(Request $request)
    {
        $request->validate([
            'retention_period' => 'required|numeric|min:1',
            'retention_unit' => 'required|in:minutes,hours,days'
        ]);

        try {
            // Update .env file
            $this->updateEnvFile([
                'RETENTION_PERIOD' => $request->retention_period,
                'RETENTION_UNIT' => $request->retention_unit
            ]);

            // Clear config cache to reflect changes
            \Artisan::call('config:clear');
            \Artisan::call('cache:clear');
            
            return response()->json(['message' => 'Retention settings updated successfully']);
        } catch (\Exception $e) {
            \Log::error('Error updating retention settings: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to update settings'], 500);
        }
    }

    public function cleanupExpiredData()
    {
        $period = config('retention.period', 400);
        $unit = config('retention.unit', 'days');
        
        $expiryDate = Carbon::now();
        
        switch($unit) {
            case 'minutes':
                $expiryDate = $expiryDate->subMinutes($period);
                break;
            case 'hours':
                $expiryDate = $expiryDate->subHours($period);
                break;
            case 'days':
                $expiryDate = $expiryDate->subDays($period);
                break;
        }

        // Get expired orders based on paid_at timestamp
        $expiredOrders = Orders::whereNotNull('paid_at')
                              ->where('paid_at', '<=', $expiryDate)
                              ->get();

        foreach($expiredOrders as $order) {
            try {
                \DB::beginTransaction();
                
                // First revoke the link
                $order->update([
                    'access_token' => null,
                    'link_status' => 'revoked'
                ]);

                // Delete customer measurements
                BodyMeasurement::where('customer_id', $order->customer_id)->delete();
                
                // Delete customer (basic info)
                Customer::where('id', $order->customer_id)->delete();
                
                // Delete the order itself
                $order->delete();

                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollBack();
                \Log::error('Error in cleanup: ' . $e->getMessage());
            }
        }
    }

    private function updateEnvFile($data)
    {
        $envFile = base_path('.env');
        $envContents = file_get_contents($envFile);

        if ($envContents === false) {
            throw new \Exception('Could not read .env file');
        }

        foreach ($data as $key => $value) {
            // Check if the key exists
            if (preg_match("/^{$key}=.*/m", $envContents)) {
                // Update existing key
                $envContents = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}={$value}",
                    $envContents
                );
            } else {
                // Add new key
                $envContents .= "\n{$key}={$value}";
            }
        }

        if (file_put_contents($envFile, $envContents) === false) {
            throw new \Exception('Could not write to .env file');
        }
    }
} 