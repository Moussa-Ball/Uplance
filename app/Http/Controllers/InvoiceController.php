<?php

namespace App\Http\Controllers;

use Auth;
use App\Invoice;

class InvoiceController extends Controller
{
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
        return view('invoices.show', compact('invoice'));
    }
}
