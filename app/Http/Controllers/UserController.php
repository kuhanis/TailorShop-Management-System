<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        $title = "users";
        $users = User::get();
        return view('users',compact('title','users'));
    }

    public function store(Request $request){
        $this->validate($request,[
            'username'=>'required',
            'name' => 'required',
            'email' => 'required',
            'password' => 'required|max:200|confirmed',
            'avatar' =>'file|image|mimes:jpg,jpeg,png,gif',
        ]);

        $plainPassword = $request->password;
    
        $user = User::create([
            'name'=>$request->name,
            'username'=>$request->username,
            'email'=>$request->email,
            'password'=>Hash::make($plainPassword),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'New user added successfully!',
            'username' => $user->username,
            'password' => $plainPassword
        ]);
    }

    public function profile(){
        $title = "user profile";
        return view('user-profile',compact('title'));
    }

    public function updateProfile(Request $request){
        $this->validate($request,[
            'username'=>'required',
            'name' => 'required',
            'email' => 'required',
            'avatar' =>'file|image|mimes:jpg,jpeg,png,gif',
        ]);
        $imageName = null;
        if($request->avatar != null){
            $imageName = time().'.'.$request->avatar->extension();
            $request->avatar->move(public_path('storage/avatars'), $imageName);
        }
        auth()->user()->update([
            'username'=>$request->username,
            'name'=>$request->name,
            'email'=>$request->email,
            'avatar'=>$imageName,
        ]);
        $notification = array(
            'message'=>"User profile updated successfully!!",
            'alert-type'=>'success'
        );
        return back()->with($notification);
    }

    public function updatePassword(Request $request){
        $this->validate($request,[
            'old_password'=>'required',
            'password'=>'required|max:200|confirmed',
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
            'password' => 'required|max:200'
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
