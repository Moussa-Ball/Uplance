<?php

namespace App\Http\Controllers;

use Auth;
use App\Offer;
use App\Invoice;
use App\Contract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Notifications\FreelancerHired;
use App\Notifications\ContractActivated;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $contracts = Contract::where('to_id', Auth::id())
            ->orWhere('from_id', Auth::id())
            ->orderBy('created_at', 'DESC')->paginate(10);
        return view('contracts.index', compact('contracts'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contract  $contract
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Contract $contract)
    {
        $user = $request->user()->hashid;
        $contract = Contract::with([
            'from', 'to', 'proposal', 'invoices'
        ])->where('id', $contract->id)->first();
        return view('contracts.show', compact('user', 'contract'));
    }

    /**
     * Generate a unique order for invoices.
     *
     * @param  int  $index
     * @return string the order.
     */
    private function generateOrder($index = 0)
    {
        $today = date("Ymd");
        $rand = strtoupper(substr(uniqid(sha1(time())), 0, 4));
        return $unique = $today . $rand . $index;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Offer $offer)
    {
        $this->authorize('not-accepted', $offer);

        if ($offer->offer_type === 'Hourly Rate') {
            $contract = Contract::firstOrCreate([
                'title' => $offer->contract_title,
                'type' => 'Hourly Rate',
                'rate' => $offer->hourly_rate,
                'to_id' => $offer->to_id,
                'from_id' => $offer->from_id,
            ]);

            Invoice::firstOrCreate([
                'order' => $this->generateOrder(),
                'issued' => \Carbon\Carbon::now(),
                'description' => $offer->contract_title,
                'type' => 'Hourly Rate',
                'rate' => $offer->hourly_rate,
                'contract_id' => $contract->id,
                'to_id' => $offer->to_id,
                'from_id' => $offer->from_id,
            ]);
        }

        if ($offer->offer_type === 'Fixed Price') {
            if ($offer->due_date && !$offer->milestones[0]['description']) {
                $contract = Contract::firstOrCreate([
                    'title' => $offer->contract_title,
                    'type' => 'Fixed Price',
                    'amount' => $offer->total_amount,
                    'to_id' => $offer->to_id,
                    'from_id' => $offer->from_id,
                    'due_date' => Carbon::createFromDate($offer->due_date)->format('Y-m-d'),
                ]);

                Invoice::firstOrCreate([
                    'order' => $this->generateOrder(),
                    'issued' => Carbon::now(),
                    'description' => $offer->contract_title,
                    'type' => 'Fixed Price',
                    'amount' => $offer->total_amount,
                    'due_date' => Carbon::createFromDate($offer->due_date)->format('Y-m-d'),
                    'contract_id' => $contract->id,
                    'to_id' => $offer->to_id,
                    'from_id' => $offer->from_id,
                ]);
            } else {
                $milestones_amount = 0;

                foreach ($offer->milestones as $milestone) {
                    $milestones_amount += $milestone['amount'];
                }

                $contract = Contract::firstOrCreate([
                    'title' => $offer->contract_title,
                    'type' => 'Fixed Price',
                    'amount' => $milestones_amount,
                    'remaining' => $milestones_amount,
                    'milestones' => (array) $offer->milestones,
                    'to_id' => $offer->to_id,
                    'from_id' => $offer->from_id,
                ]);

                foreach ($offer->milestones as $key => $milestone) {
                    Invoice::firstOrCreate(
                        [
                            'order' => $this->generateOrder($key),
                            'issued' => Carbon::now(),
                            'description' => $milestone['description'],
                            'type' => 'Fixed Price',
                            'amount' => $milestone['amount'],
                            'due_date' => Carbon::createFromDate($milestone['due_date'])->format('Y-m-d'),
                            'contract_id' => $contract->id,
                            'to_id' => $offer->to_id,
                            'from_id' => $offer->from_id,
                        ]
                    );
                }
            }
        }

        // TODO: Notification...
        $contract->to->notify(new FreelancerHired($contract));
        $contract->from->notify(new ContractActivated($contract));

        //Mark offer as accepted.
        $offer->accepted = true;
        $offer->save();

        //Mark proposal as accepted if he exists.
        if ($offer->proposal) {
            $offer->proposal->accepted = true;
            $offer->proposal->save();
        }

        return redirect()->back()
            ->with('success', 'You have accepted the offer, a contract has just been active for the job. Good luck!');
    }
}
