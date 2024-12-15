<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use App\Models\Customer;
use App\Models\Retention;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $this->validate($request,[
            'customer'=>'required',
            'description'=>'max:200',
            'received_on'=>'required|date',
            'amount_charged'=>'required|numeric|min:0',
        ]);

        $order = Orders::create([
            'customer_id'=>$request->customer,
            'description'=>$request->description,
            'received_on'=>$request->received_on,
            'amount_charged'=>$request->amount_charged,
            'access_token'=>Str::random(32),
        ]);

        $notification = array(
            'message'=>"Customer order has been added",
            'alert-type'=>"success"
        );
        return back()->with($notification);
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
        $order = Orders::where('access_token', $token)->firstOrFail();
        return view('public.order-details', compact('order', 'title'));
    }

    public function updateStatus(Request $request, Orders $order)
    {
        $validated = $request->validate([
            'status' => 'required|in:paid,to_collect',
        ]);

        $order->update([
            'status' => $validated['status'],
            'received_on' => $validated['status'] === 'paid' ? now() : $order->received_on
        ]);

        // Create retention record when order is marked as paid
        if ($validated['status'] === 'paid') {
            Retention::create([
                'order_id' => $order->id,
                'link_expire' => now()->addDays(30)
            ]);
        }

        return response()->json(['success' => true]);
    }

    public function retention()
    {
        $title = "Retention Orders";
        $retentions = Retention::with('order.customer')->get();
        return view('orders.retention', compact('title', 'retentions'));
    }
}
