<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function show($token)
    {
        $order = Order::with('customer')
            ->where('access_token', $token)
            ->firstOrFail();
        
        return view('public.order-details', compact('order'));
    }
} 