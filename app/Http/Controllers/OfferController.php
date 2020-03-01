<?php

namespace App\Http\Controllers;

use App\Job;
use App\User;
use App\Offer;
use App\Proposal;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOMeta;

class OfferController extends Controller
{
    /**
     * Form to hire freelancer without a job proposal.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexWithout(Request $request, User $user)
    {
        $this->authorize('client', $request->user());
        SEOMeta::setTitle('Hire freelancer');
        return view('offers.create');
    }

    /**
     * Form to hire freelancer with a job proposal.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexWith(Request $request, Job $job, Proposal $proposal)
    {
        dd($job, $proposal);
        $this->authorize('client', $request->user());
        SEOMeta::setTitle('Hire freelancer');
        return view('offers.create');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function show(Offer $offer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function edit(Offer $offer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Offer $offer)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Offer  $offer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Offer $offer)
    {
        //
    }
}
