<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SimpleMail;

class MailController extends Controller
{
    public function sendMail()
    {
        $details = [
            'title' => 'Mail from Laravel',
            'body' => 'This is a test email using MailHog.'
        ];

        Mail::to('test@example.com')->send(new SimpleMail($details));

        return 'Email sent successfully';
    }

    public function sendMailView()
    {
        return view('emails.simpleMailButton');
    }
}
