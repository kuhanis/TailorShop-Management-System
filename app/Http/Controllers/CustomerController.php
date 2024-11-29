<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
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
        $this->validate($request, [
            'fullname' => 'required|max:100',
            'address' => 'required|max:200',
            'phone' => 'required|max:15|unique:customers,phone',
            'email' => 'required|email|max:100'
        ]);

        Customer::create($request->all());
        
        $notification = [
            'message' => "Customer has been added successfully!!",
            'alert-type' => 'success'
        ];
        
        return back()->with($notification);
    }

    public function update(Request $request)
    {
        $customer = Customer::findOrFail($request->id);

        $this->validate($request, [
            'fullname' => 'required|max:100',
            'address' => 'required|max:200',
            'phone' => [
                'required', 
                'max:15', 
                Rule::unique('customers')->ignore($customer->id)
            ],
            'email' => 'required|email|max:100'
        ]);

        $customer->update($request->all());
        
        $notification = [
            'message' => "Customer updated successfully!!",
            'alert-type' => 'success'
        ];
        
        return back()->with($notification);
    }

    public function destroy(Request $request)
    {
        $customer = Customer::find($request->id);
        $customer->delete();
        
        $notification = [
            'message' => "Customer deleted successfully!!!",
            'alert-type' => 'success',
        ];
        
        return back()->with($notification);
    }
}
