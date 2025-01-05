<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){
        $title = "users";
        $staff = Staff::get();
        return view('staff',compact('title','staff'));
    }

    public function store(Request $request){
        $this->validate($request,[
            'username'=>'required|unique:users,username',
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|max:200|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
            'avatar' =>'file|image|mimes:jpg,jpeg,png,gif',
        ]);

        $plainPassword = $request->password;
    
        $user = User::create([
            'name'=>$request->name,
            'username'=>$request->username,
            'email'=>$request->email,
            'password'=>Hash::make($plainPassword),
        ]);

        // Automatically create staff entry
        Staff::create([
            'user_id' => $user->id,
            'address' => '', // You might want to add these fields in the user creation form
            'phone' => '',
            'salary' => 0
        ]);

        return response()->json([
            'success' => true,
            'message' => 'New user added successfully!',
            'username' => $user->username,
            'password' => $plainPassword,
            'user_id' => $user->id // Add this to help with staff creation in frontend
        ]);
    }

    public function profile(){
        $title = "user profile";
        return view('user-profile',compact('title'));
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validate the image
        ]);

        $user = auth()->user();

        if ($request->hasFile('avatar')) {
            // Store the new avatar
            $path = $request->file('avatar')->store('avatars', 'public');

            // Update the user's avatar path in the database
            $user->avatar = $path;
            $user->save();
        }

        return redirect()->back()->with('success', 'Profile photo updated successfully.');
    }

    public function updatePassword(Request $request){
        $this->validate($request,[
            'old_password'=>'required',
            'password'=>'required|max:200|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
        ]);
        
        if (password_verify($request->old_password,auth()->user()->password)){
            auth()->user()->update(['password'=>Hash::make($request->password)]);
            $notification = array(
                'message'=>"User password updated successfully!!!",
                'alert-type'=>'success'
            );
        }else{
            $notification = array(
                'message'=>"Old Password do not match!!!",
                'alert-type'=>'error'
            );
        }
        return back()->with($notification);
    }

    public function destroy(Request $request){
        $user = User::find($request->id);
        if ($user != auth()->user()){
            $user->delete();
            $notification = array(
                'message'=>"User deleted successfully!!",
                'alert-type'=>'success'
            );
        }else{
            $notification = array(
                'message'=>"Cannot delete current authenticated user!!!",
                'alert-type'=>'error'
            );
        }
        
        return back()->with($notification);
    }

    public function firstTimePasswordForm()
    {
        if (!auth()->user()->first_login) {
            return redirect()->route('dashboard');
        }
        $title = "Change Password";
        return view('auth.first-time-password', compact('title'));
    }

    public function firstTimePasswordChange(Request $request)
    {
        $this->validate($request, [
            'password' => 'required|max:200|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/'
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password),
            'first_login' => false  
        ]);

        return redirect()->route('dashboard')->with([
            'message' => 'Password changed successfully!',
            'alert-type' => 'success'
        ]);
    }

}
