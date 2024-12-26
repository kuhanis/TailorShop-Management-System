<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
require base_path('PHPMailer/src/PHPMailer.php');
require base_path('PHPMailer/src/SMTP.php');
require base_path('PHPMailer/src/Exception.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    public function sendResetLink(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users'
            ]);

            $token = Str::random(64);
            
            DB::table('password_resets')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => now()
            ]);

            // Create PHPMailer instance
            $mail = new PHPMailer(true);

            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Enable verbose debug output
            $mail->isSMTP();
            $mail->Debugoutput = 'error_log'; // Log to PHP error log
            $mail->Host = env('MAIL_HOST');
            $mail->SMTPAuth = true;
            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->SMTPSecure = 'tls';
            $mail->Port = env('MAIL_PORT');

            // Recipients
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->addAddress($request->email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Reset Password Notification';
            $mail->Body = view('emails.reset-password', [
                'token' => $token,
                'email' => $request->email
            ])->render();

            $mail->send();

            return back()->with('status', 'Password reset link sent to your email!');
        } catch (\Exception $e) {
            \Log::error('Password Reset Error: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            if ($e instanceof \PHPMailer\PHPMailer\Exception) {
                return back()->with('error', 'Email Error: ' . $mail->ErrorInfo);
            }
            return back()->with('error', 'Error sending reset link. Please try again later.');
        }
    }

    public function showResetForm(Request $request, $token)
    {
        // Check if token exists and is not expired
        $passwordReset = DB::table('password_resets')
            ->where('token', $token)
            ->first();

        if (!$passwordReset || 
            Carbon::parse($passwordReset->created_at)->addMinutes(5)->isPast()) {
            abort(404, 'Password reset link has expired or is invalid.');
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|max:200|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
        ]);

        $tokenData = DB::table('password_resets')
            ->where('token', $request->token)
            ->first();

        if (!$tokenData) {
            return back()->with('error', 'Invalid token!');
        }

        $user = DB::table('users')
            ->where('email', $tokenData->email)
            ->first();

        if (!$user) {
            return back()->with('error', 'User not found!');
        }

        DB::table('users')
            ->where('email', $tokenData->email)
            ->update(['password' => bcrypt($request->password)]);

        DB::table('password_resets')
            ->where('email', $tokenData->email)
            ->delete();

        // Set a session status message
        return redirect()->route('login')->with('status', 'Your password has been reset successfully!');
    }
} 