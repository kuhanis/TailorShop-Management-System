<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Designation;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        $title = "staff";
        $designations=Designation::get();
        $staff = Staff::get();
        return view('staff',compact('title','designations','staff'));
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'designation'=>'required',
            'fullname'=>'required',
            'address'=>'required',
            'gender'=>'required',
            'phone'=>'required',
            'salary'=>'required',
            'avatar'=>'file|image|mimes:jpg,jpeg,png,gif',
        ]);   

        $imageName = null;
        if($request->avatar != null){
            $imageName = time().'.'.$request->avatar->extension();
            $request->avatar->move(public_path('storage/avatars'), $imageName);
        }  

        $staff = Staff::create([
            'designation_id'=>$request->designation,
            'fullname'=>$request->fullname,
            'address'=>$request->address,
            'gender'=>$request->gender,
            'phone'=>$request->phone,
            'salary'=>$request->salary,
            'avatar'=>$imageName,
        ]);

        return response()->json($staff->load('designation'));
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

