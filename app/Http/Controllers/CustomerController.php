<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\BodyMeasurement;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index()
    {
        $title = "Customers";
        $customers = Customer::get();
        return view('customers', compact('title', 'customers'));
    }

    public function store(Request $request)
    {
        // Check if customer exists by fullname
        $existingCustomer = Customer::where('fullname', $request->fullname)->first();
        
        if ($existingCustomer) {
            return response()->json([
                'status' => 'warning',
                'message' => 'Customer already exists. Please use the existing data.',
                'customer' => $existingCustomer
            ], 422);
        }

        // Your existing validation rules, but remove unique from phone
        $request->validate([
            'fullname' => 'required|string|max:255',
            'phone' => 'required|string|max:20',  // removed unique:customers
            'email' => 'required|email|unique:customers',
            'address' => 'required|string'
        ]);

        $customer = Customer::create($request->all());
        
        return response()->json([
            'message' => "Customer has been added successfully!!",
            'status' => 'success',
            'customer_id' => $customer->id  // Make sure this is included
        ]);
    }

    public function storeBodyMeasurement(Request $request)
    {
        $this->validate($request, [
            'customer_id' => 'required|exists:customers,id',
            'body_name' => 'required|string',
            'shoulder' => 'required|numeric',
            'chest' => 'required|numeric',
            'waist' => 'required|numeric',
            'hips' => 'required|numeric',
            'dress_length' => 'required|numeric',
            'wrist' => 'required|numeric',
            'skirt_length' => 'required|numeric',
            'armpit' => 'required|numeric',
        ]);

        BodyMeasurement::create($request->all());

        return response()->json([
            'message' => 'Body measurements added successfully!',
            'status' => 'success'
        ]);
    }


    public function update(Request $request)
    {
        $customer = Customer::findOrFail($request->id);

        $request->validate([
            'fullname' => 'required|max:100',
            'phone' => 'required|max:15',  // Removed unique validation since it's an update
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('customers')->ignore($customer->id)
            ],
            'address' => 'required|max:200'
        ]);

        $customer->update([
            'fullname' => $request->fullname,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address
        ]);

        return response()->json([
            'message' => 'Customer updated successfully!',
            'status' => 'success'
        ]);
    }

    public function addBodyMeasurement(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'body_name' => 'required|string',
            'shoulder' => 'required|numeric',
            'chest' => 'required|numeric',
            'waist' => 'required|numeric',
            'hips' => 'required|numeric',
            'dress_length' => 'required|numeric',
            'wrist' => 'required|numeric',
            'skirt_length' => 'required|numeric',
            'armpit' => 'required|numeric',
        ]);

        BodyMeasurement::create($request->all());

        return response()->json([
            'message' => 'Body measurements added successfully!',
            'status' => 'success'
        ]);
    }

    public function destroy(Request $request)
    {
        $customer = Customer::find($request->id);
        $customer->bodyMeasurements()->delete();
        $customer->delete();
        return response()->json(['success' => true]);
    }
}
