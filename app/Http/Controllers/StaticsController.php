<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class StaticsController extends Controller
{

    public function about()
    {
        return view('pages.about');
    }

    public function faq()
    {
        return view('pages.faq');
    }

    public function contacts()
    {
        return view('pages.contacts');
    }

    public function sendEmail(Request $request)
    {
        try {
            $fromEmail = $request->input('email');
            $toEmail = "up202005334@up.pt";
            $subject = $request->input('title');
            $content = $request->input('message');

            Mail::html($content, function ($mail) use ($fromEmail, $toEmail, $subject) {
                $mail->from($fromEmail)
                     ->to($toEmail)
                     ->subject($subject);
            });

            return response()->json(['message' => 'Email sent successfully! We will return to you shortly!']);
        } catch (\Exception $e) {

            \Log::error('Email sending failed: ' . $e->getMessage());

            return response()->json(['error' => 'There was an error sending your email. Please try again later.'], 500);
        }
    }


}
