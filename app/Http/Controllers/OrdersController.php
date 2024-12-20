<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Customer;
use App\Models\OrderHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class OrdersController extends Controller
{
    public function index()
    {
        $title = "Orders";
        $orders = Orders::where('status', '!=', 'paid')
            ->whereNull('deleted_at')
            ->get();
        
        // Get new customers (who have never had any orders or history)
        $newCustomers = Customer::whereNotExists(function($q) {
            $q->select('customer_id')
                ->from('orders')
                ->whereColumn('customers.id', 'orders.customer_id');
        })->whereNotExists(function($q) {
            $q->select('customer_id')
                ->from('order_histories')
                ->whereColumn('customers.id', 'order_histories.customer_id');
        })->get();

        // Get retention customers (who have completed orders but no active orders)
        $retentionCustomers = Customer::whereExists(function($q) {
            // Has orders in history OR has paid orders
            $q->select('customer_id')
                ->from('order_histories')
                ->whereColumn('customers.id', 'order_histories.customer_id')
                ->orWhereExists(function($sq) {
                    $sq->select('customer_id')
                        ->from('orders')
                        ->whereColumn('customers.id', 'orders.customer_id')
                        ->where('status', '=', 'paid');
                });
        })->whereNotExists(function($q) {
            // Exclude customers with active orders
            $q->select('customer_id')
                ->from('orders')
                ->whereColumn('customers.id', 'orders.customer_id')
                ->whereNull('deleted_at')
                ->where('status', '!=', 'paid');
        })->get();

        return view('orders', compact('title', 'orders', 'newCustomers', 'retentionCustomers'));
    }

    public function store(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'customer' => 'required|exists:customers,id',
                'description' => 'required',
                'received_on' => 'required|date',
                'amount_charged' => 'required|numeric',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);

            \Log::info('Attempting to create order with data:', $request->all());

            // Start transaction
            return DB::transaction(function() use ($request) {
                // Handle image upload
                $imagePath = null;
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $imagePath = $image->store('order-images', 'public');
                }

                // First, check if customer has any previous orders (including deleted ones)
                $previousOrder = Orders::withTrashed()
                    ->where('customer_id', $request->customer)
                    ->whereNotNull('access_token')
                    ->latest()
                    ->first();

                // If previous order exists, use its token, otherwise generate new one
                $token = $previousOrder ? $previousOrder->access_token : (string) Str::uuid();

                // Before creating new order, nullify access_token in any existing orders
                Orders::withTrashed()
                    ->where('customer_id', $request->customer)
                    ->whereNotNull('access_token')
                    ->update(['access_token' => null]);

                // Create new order
                $order = Orders::create([
                    'customer_id' => $request->customer,
                    'description' => $request->description,
                    'received_on' => $request->received_on,
                    'amount_charged' => $request->amount_charged,
                    'access_token' => $token,
                    'status' => 'to_collect',
                    'link_status' => 'active',
                    'link_activated_at' => now(),
                    'is_ready_to_collect' => false,
                    'image_path' => $imagePath
                ]);

                \Log::info('Order created successfully:', ['order_id' => $order->id]);

                return redirect()->back()->with([
                    'message' => 'Customer order has been added',
                    'alert-type' => 'success'
                ]);
            });

        } catch (\Exception $e) {
            \Log::error('Failed to create order:', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all()
            ]);

            return redirect()->back()->with([
                'message' => 'Failed to add order: ' . $e->getMessage(),
                'alert-type' => 'error'
            ])->withInput();
        }
    }

    public function edit($id)
    {
        $order = Orders::with('customer')->findOrFail($id);
        return response()->json($order);
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|exists:orders,id',
            'customer' => 'required',
            'description' => 'max:200',
            'received_on' => 'required|date',
            'amount_charged' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $order = Orders::findOrFail($request->id);
        
        // Handle image upload
        $imagePath = $order->image_path;
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($order->image_path && Storage::disk('public')->exists($order->image_path)) {
                Storage::disk('public')->delete($order->image_path);
            }
            $imagePath = $request->file('image')->store('order-images', 'public');
        }

        $order->update([
            'customer_id' => $request->customer,
            'description' => $request->description,
            'received_on' => $request->received_on,
            'amount_charged' => $request->amount_charged,
            'image_path' => $imagePath
        ]);

        return back()->with([
            'message' => "Customer order has been updated",
            'alert-type' => "success"
        ]);
    }

    public function destroy(Request $request)
    {
        $order = Orders::find($request->id);
        $order->update(['access_token' => null]);
        $order->delete();
        
        $notification = array(
            'message'=>"Customer order deleted successfully!!",
            'alert-type'=>'success'
        );
        return back()->with($notification);
    }

    public function view($token)
    {
        $title = "Order Details";
        $order = Orders::where('access_token', $token)
            ->where('link_status', 'active')
            ->firstOrFail();

        // If order is paid, show thank you page
        if ($order->status === 'paid') {
            return view('orders.thank-you', compact('order', 'title'));
        }

        return view('public.order-details', compact('order', 'title'));
    }

    public function updateStatus(Request $request, Orders $order)
    {
        try {
            $status = $request->input('status');
            
            if ($status === 'paid') {
                DB::transaction(function() use ($order) {
                    // Create history record
                    OrderHistory::create([
                        'customer_id' => $order->customer_id,
                        'description' => $order->description,
                        'received_on' => $order->received_on,
                        'amount_charged' => $order->amount_charged,
                    ]);

                    // Update the order
                    $order->status = 'paid';
                    $order->paid_at = now();
                    $order->save();
                });
            } else {
                // Update to_collect status
                $order->status = 'to_collect';
                $order->is_ready_to_collect = true;
                $order->save();
            }

            return response()->json([
                'success' => true,
                'message' => $status === 'paid' ? 'Order marked as paid' : 'Order ready to collect'
            ]);

        } catch (\Exception $e) {
            \Log::error('Order status update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status'
            ], 500);
        }
    }

    public function retention()
    {
        $title = "Retention Orders";
        
        // Get latest paid orders for each customer
        $orders = Orders::join('customers', 'orders.customer_id', '=', 'customers.id')
            ->whereExists(function($q) {
                // Has orders in history
                $q->select('customer_id')
                    ->from('order_histories')
                    ->whereColumn('orders.customer_id', 'order_histories.customer_id');
            })
            ->whereNotExists(function($q) {
                // Does NOT have any active orders
                $q->select('customer_id')
                    ->from('orders as o')
                    ->whereColumn('orders.customer_id', 'o.customer_id')
                    ->whereNull('o.deleted_at')
                    ->where('o.status', '!=', 'paid');
            })
            ->where('orders.link_status', 'active')
            ->whereNull('orders.deleted_at')
            ->where('orders.status', 'paid')
            ->whereIn('orders.id', function($query) {
                // Subquery to get only the latest order ID for each customer
                $query->select(DB::raw('MAX(id)'))
                    ->from('orders')
                    ->where('status', 'paid')
                    ->whereNull('deleted_at')
                    ->groupBy('customer_id');
            })
            ->select('orders.*')
            ->get();
        
        return view('retention', compact('title', 'orders'));
    }

    public function history()
    {
        $title = "Order History";
        $orders = OrderHistory::with('customer')
            ->orderBy('received_on', 'desc')
            ->get();
        return view('order_history', compact('title', 'orders'));
    }
}
