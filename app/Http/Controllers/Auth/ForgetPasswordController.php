<?php
 
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

use Illuminate\View\View;

use App\Models\User;

class ForgetPasswordController extends Controller{

    public function forgetPassword(){
        return view('auth.forgetPassword');
    }

    public function forgetPasswordPost(Request $request){
        $request->validate([
            'email' => 'required|email|max:250|exists:users,email',
        ]);
        $token = Str::random(64);
    
        
        $existingToken = DB::table('password_reset_tokens')->where('email', $request->email)->first();
    
        if ($existingToken) {
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->update([
                    'token' => $token,
                    'created_at' => Carbon::now()
                ]);
        } else {
            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => Carbon::now(),
            ]);
        }
    
        Mail::send("auth.forgetPasswordEmail", ['token' => $token], function($message) use ($request){
            $message->to($request->email);
            $message->subject("Reset Password");
        });
    
        return redirect()->to(route('forget.password'))
            ->with("success", "We have sent you an e-mail to reset your password.");
    }

    public function resetPassword($token){
        return view('auth.newPassword', compact('token'));

    }

    public function resetPasswordPost(Request $request){
        $request->validate([
            "email" => "required|email|exists:users",
            "password" => "required|string|min:6",
        ]);

        $updatePassword = DB::table('password_reset_tokens')
            ->where([
                "email" => $request->email,
                "token" => $request->token
            ])->first();

        if(!$updatePassword){
            return redirect()->route('reset.password', ['token' => $request->token])
                ->with("error", "Invalid");
        }

        User::where("email", $request->email)
            ->update(["password" => Hash::make($request->password)]);

        DB::table("password_reset_tokens")->where(["email" => $request->email])->delete();

        return redirect()->to(route("login"))->with("success", "Password reset success");

    }

}