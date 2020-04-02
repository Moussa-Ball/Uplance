<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ContactRequest;

class ContactController extends Controller
{
    public function index()
    {
        return view('contacts.contact');
    }

    public function send(ContactRequest $request)
    {
        Mail::send(new ContactMail($request));
        return redirect()->back()->with('success', 'Your message has been sent.');
    }
}
