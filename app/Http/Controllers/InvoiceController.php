<?php

namespace App\Http\Controllers;

use Auth;
use App\Invoice;
use Artesaos\SEOTools\Facades\SEOMeta;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('complete.profile');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\view
     */
    public function index()
    {
        $invoices = Invoice::where('to_id', Auth::id())
            ->orWhere('from_id', Auth::id())
            ->orderBy('created_at', 'DESC')->paginate(10);
        SEOMeta::setTitle('My Invoices');
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\view
     */
    public function success(Invoice $invoice)
    {
        SEOMeta::setTitle('Payment Successful');
        return view('invoices.success', compact('invoice'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\view
     */
    public function show(Invoice $invoice)
    {
        SEOMeta::setTitle($invoice->description . '- Invoice');
        return view('invoices.show', compact('invoice'));
    }
}
