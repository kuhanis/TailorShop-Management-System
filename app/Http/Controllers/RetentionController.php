<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Orders;
use App\Models\BodyMeasurement;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RetentionController extends Controller
{
    public function updateRetentionSettings(Request $request)
    {
        $request->validate([
            'retention_period' => 'required|numeric|min:1',
            'retention_unit' => 'required|in:minutes,hours,days'
        ]);

        try {
            DB::beginTransaction();
            
            // Update .env file
            $this->updateEnvFile([
                'RETENTION_PERIOD' => $request->retention_period,
                'RETENTION_UNIT' => $request->retention_unit
            ]);

            // Clear config cache to reflect changes
            Artisan::call('config:clear');
            Artisan::call('cache:clear');
            
            DB::commit();
            return response()->json(['message' => 'Retention settings updated successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating retention settings: ' . $e->getMessage());
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

        // Get expired orders
        $expiredOrders = Orders::with('customer')
                              ->whereNotNull('paid_at')
                              ->where('paid_at', '<=', $expiryDate)
                              ->where('link_status', '!=', 'revoked')  // Only process non-revoked links
                              ->get();

        foreach($expiredOrders as $order) {
            try {
                DB::beginTransaction();

                // First update the order status
                $order->update([
                    'access_token' => null,
                    'link_status' => 'revoked'
                ]);

                // Use the same deletion logic as the delete button
                try {
                    DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Temporarily disable foreign key checks
                    
                    // Delete from body_measurements first
                    BodyMeasurement::where('customer_id', $order->customer_id)->delete();
                    
                    // Then delete from customers
                    Customer::where('id', $order->customer_id)->delete();
                    
                    DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Re-enable foreign key checks
                } catch (\Exception $e) {
                    Log::error('Error during customer deletion: ' . $e->getMessage());
                    throw $e;
                }

                DB::commit();
                Log::info('Successfully processed order #' . $order->id);
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error in cleanup for order #' . $order->id . ': ' . $e->getMessage());
            }
        }

        return response()->json(['message' => 'Cleanup completed']);
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

    public function deleteCustomer(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'customer_id' => 'required|exists:customers,id'
        ]);

        try {
            DB::beginTransaction();

            // Get the order
            $order = Orders::findOrFail($request->order_id);

            // Update order status
            $order->update([
                'access_token' => null,
                'link_status' => 'revoked'
            ]);

            // First delete from body_measurements due to foreign key constraint
            try {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Temporarily disable foreign key checks
                
                // Delete from body_measurements first
                BodyMeasurement::where('customer_id', $request->customer_id)->delete();
                
                // Then delete from customers
                Customer::where('id', $request->customer_id)->delete();
                
                DB::statement('SET FOREIGN_KEY_CHECKS=1;'); // Re-enable foreign key checks
            } catch (\Exception $e) {
                Log::error('Error during customer deletion: ' . $e->getMessage());
                throw $e;
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Customer data deleted successfully'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting customer data: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete customer data'
            ], 500);
        }
    }
} 