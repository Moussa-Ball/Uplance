<?php

namespace App\Http\Controllers;

use App\Http\Requests\OfferRequest;
use Auth;
use App\Job;
use App\Notifications\DeclineOffer;
use App\Notifications\OfferSend;
use App\User;
use App\Offer;
use App\Proposal;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;

class OfferController extends Controller
{
    public function __construct()
    {
        $this->middleware('complete.profile');
    }

    /**
     * Show the list of offer for an user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('freelancer');
        $offers = Offer::where('to_id', Auth::id())->orderBy('created_at', 'DESC')->paginate(10);
        SEOTools::setTitle('Contract Offers');
        return view('offers.index', compact('offers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, User $user)
    {
        $this->authorize('client');
        $this->authorize('client');
        $proposal = $request->get('proposal');
        if ($proposal) {
            $proposal = Hashids::connection(Proposal::class)->decode($proposal);
            if (!empty($proposal))
                $proposal = Proposal::where('id', $proposal)->first();
        } else {
            $proposal = [];
        }
        SEOMeta::setTitle('Hire freelancer');
        return view('offers.create', compact('user', 'proposal'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OfferRequest $request, User $user)
    {
        $this->authorize('client');
        $this->authorize('client');
        $proposal = $request->get('proposal');
        if ($proposal) {
            $proposal = Hashids::connection(Proposal::class)->decode($proposal);
            if (!empty($proposal))
                $proposal = Proposal::where('id', $proposal)->first();
        }

        $offer = Offer::create([
            'contract_title' => $request->input('contract_title'),
            'hourly_rate' => $request->input('hourly_rate'),
            'milestones' => $request->input('milestones'),
            'total_amount' => $request->input('total_amount'),
            'offer_type' => $request->input('offer_type'),
            'description' => $request->input('description'),
            'proposal_id' => ($proposal) ? $proposal->id : null,
            'to_id' => $user->id,
            'from_id' => Auth::id(),
            'due_date' => $request->input('due_date'),
        ]);

        // Notify the freelancer.
        $offer->to->notify(new OfferSend($offer));

        return response()->json([
            'attachable_id' => $offer->id,
            'attachable_type' => Offer::class,
            'message' => 'Your offer has been sent to the freelancer.'
        ]);
    }

    /**
     * Decline offer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Offer $offer)
    {
        $data = $offer;
        $offer->delete();
        $data->from->notify(new DeclineOffer($data));
        return redirect()->back();
    }
}
