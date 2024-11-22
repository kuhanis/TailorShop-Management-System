<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $title = "staff";
        $staff = Staff::with('user')->get();
        return view('staff', compact('title', 'staff'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'user_id' => 'required|exists:users,id',
            'address' => 'nullable',
            'phone' => 'nullable',
            'salary' => 'nullable|numeric'
        ]);   
    
        // Check if staff entry already exists for this user
        $existingStaff = Staff::where('user_id', $request->user_id)->first();
        
        if ($existingStaff) {
            // Update existing staff entry
            $staff = $existingStaff->update([
                'address' => $request->address ?? $existingStaff->address,
                'phone' => $request->phone ?? $existingStaff->phone,
                'salary' => $request->salary ?? $existingStaff->salary
            ]);
        } else {
            // Create new staff entry
            $staff = Staff::create([
                'user_id' => $request->user_id,
                'address' => $request->address,
                'phone' => $request->phone,
                'salary' => $request->salary
            ]);
        }
    
        // Return a redirect with a success message
        return redirect()->route('staff')->with([
            'message' => 'Staff added successfully!',
            'alert-type' => 'success'
        ]);
    }

    public function edit($id)
    {
        $staff = Staff::find($id);
        return response()->json($staff);
    }

    public function update(Request $request, $id)
    {
        $staff = Staff::find($id);
        $staff->update($request->all());
        
        return response()->json(['success' => true]);
    }

    public function destroy(Request $request)
    {
        $staff = Staff::find($request->id);
        $staff->delete();
        $notification=array(
            'message'=>"Staff has been deleted!!!",
            'alert-type'=>'success',
        );
        return back()->with($notification);
    }
}

