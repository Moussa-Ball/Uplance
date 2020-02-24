<?php

namespace App\Http\Controllers;

use App\Job;
use App\Proposal;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Cmgmyr\Messenger\Models\Thread;
use Cmgmyr\Messenger\Models\Message;
use Artesaos\SEOTools\Facades\SEOMeta;
use Cmgmyr\Messenger\Models\Participant;

class MessengerController extends Controller
{
    public function index()
    {
        SEOMeta::setTitle('Messages');
        return view('messenger.index');
    }

    public function createConversation(Request $request, $job_id, $proposal_id)
    {
        $this->authorize('client', $request->user());

        $job_id = Hashids::connection(Job::class)->decode($job_id);
        if (!$job_id) return abort(404);

        $proposal_id = Hashids::connection(Proposal::class)->decode($proposal_id);
        if (!$proposal_id) return abort(404);

        $job = Job::where('id', $job_id)->first();
        if (!$job) return abort(404);

        $this->authorize('owner', $job->user_id);

        $proposal = Proposal::where(['id' => $proposal_id, 'job_id' => $job->id])->first();

        /*$conversation = Chat::conversations()->between($request->user(), $proposal->user);

        if ($conversation == null) {
            $conversation = Chat::createConversation([$proposal->user])->makePrivate();
        }*/

        //return redirect()->route('messages.index');
    }

    public function getParticipants(Request $request)
    {
        $thread = Thread::create([
            'subject' => 'proposal'
        ]);

        // Message
        $message = Message::create([
            'thread_id' => $thread->id,
            'user_id' => $request->user()->id,
            'body' => 'Hello Guy\'s',
        ]);

        // Sender
        $participant = Participant::create([
            'thread_id' => $thread->id,
            'user_id' => $request->user()->id,
            'last_read' => new \Carbon\Carbon,
        ]);

        dd($participant);

        $threads = Thread::getAllLatest()->get();
        dd($threads);
        /*$conversation = Chat::conversations()->setPaginationParams(['sorting' => 'desc'])
            ->setParticipant($request->user())->get()->toJson();
        return $conversation;*/
    }
}
