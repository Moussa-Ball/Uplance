<?php

namespace App\Http\Controllers;

use App\Mail\ContactMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\ContactRequest;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;

class ContactController extends Controller
{
    public function index()
    {
        SEOTools::setTitle('Contact Us - Uplance');
        SEOMeta::setDescription('Huge community of designers, developers and creatives ready for your project.');
        SEOTools::setCanonical(route('contact'));

        SEOTools::opengraph()->setType('article');
        SEOTools::opengraph()->setTitle('Contact Us - Uplance');
        SEOTools::opengraph()->addImage(asset('images/uplance.png'));
        SEOTools::opengraph()->setSiteName('UplanceHQ');
        SEOTools::opengraph()->setDescription('Huge community of designers, developers and creatives ready for your project.');
        SEOTools::opengraph()->setUrl(route('contact'));

        SEOTools::twitter()->setType('summary');
        SEOTools::twitter()->setTitle('Contact Us - Uplance');
        SEOTools::twitter()->setImages(asset('images/uplance.png'));
        SEOTools::twitter()->setSite('@UplanceHQ');
        SEOTools::twitter()->setDescription('Huge community of designers, developers and creatives ready for your project.');
        SEOTools::twitter()->setUrl(route('contact'));

        return view('contacts.contact');
    }

    public function send(ContactRequest $request)
    {
        Mail::send(new ContactMail($request));
        return redirect()->back()->with('success', 'Your message has been sent.');
    }
}
