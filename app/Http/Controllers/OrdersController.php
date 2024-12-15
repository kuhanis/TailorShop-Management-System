<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Customer;
use App\Models\OrderHistory;
use App\Models\Retention;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Carbon\Carbon;

class OrdersController extends Controller
{
    public function index()
    {
        $title = "Orders";
        $orders = Orders::where('status', '!=', 'paid')->orWhereNull('status')->get();
        $customers = Customer::get();
        return view('orders',compact('title','customers','orders'));
    }

    public function store(Request $request)
    {
        try {
            return \DB::transaction(function() use ($request) {
                // Get existing order with token
                $existingOrder = Orders::withTrashed()
                    ->where('customer_id', $request->customer)
                    ->whereNotNull('access_token')
                    ->first();

                // Generate token
                $token = $existingOrder ? $existingOrder->access_token : (string) Str::uuid();

                // Create new order
                $order = Orders::create([
                    'customer_id' => $request->customer,
                    'description' => $request->description,
                    'received_on' => $request->received_on,
                    'amount_charged' => $request->amount_charged,
                    'access_token' => $token,
                    'status' => 'to_collect',
                    'link_status' => 'active',
                    'link_activated_at' => now()
                ]);

                return back()->with([
                    'message' => "Customer order has been added",
                    'alert-type' => "success"
                ]);
            });
        } catch (\Exception $e) {
            \Log::error('Order creation failed: ' . $e->getMessage());
            
            return back()->with([
                'message' => "Failed to add order. Please try again.",
                'alert-type' => "error"
            ])->withInput();
        }
    }

    public function edit($id)
    {
        $order = Orders::findOrFail($id);
        return response()->json($order);
    }

    public function update(Request $request)
    {
        $this->validate($request,[
            'id'=>'required|exists:orders,id',
            'customer'=>'required',
            'description'=>'max:200',
            'recieved_on'=>'required|date',
            'amount_charged'=>'required|numeric|min:0',
        ]);

        $order = Orders::findOrFail($request->id);
        $order->update([
            'customer_id'=>$request->customer,
            'description'=>$request->description,
            'recieved_on'=>$request->recieved_on,
            'amount_charged'=>$request->amount_charged,
        ]);

        $notification = array(
            'message'=>"Customer order has been updated",
            'alert-type'=>"success"
        );
        return back()->with($notification);
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
        return view('public.order-details', compact('order', 'title'));
    }

    public function updateStatus(Request $request, Orders $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:paid,to_collect',
        ]);

        $order->update(['status' => $validated['status']]);

        return response()->json(['success' => true]);
    }

    public function retention()
    {
        $retentions = Retention::with(['order.customer'])->get();
        return view('orders.retention', [
            'title' => 'Order Retention',
            'retentions' => $retentions
        ]);
    }
}
