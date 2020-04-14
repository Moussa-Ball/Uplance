<?php

namespace App\Http\Controllers;

use App\ActivateDiscussion;
use App\Events\NewMessage;
use App\Http\Requests\StoreMessageRequest;
use Auth;
use App\Job;
use App\Notifications\MessageReceived;
use App\Offer;
use App\Proposal;
use Illuminate\Http\Request;
use Cmgmyr\Messenger\Models\Thread;
use Cmgmyr\Messenger\Models\Message;
use Artesaos\SEOTools\Facades\SEOMeta;
use Cmgmyr\Messenger\Models\Participant;

class MessengerController extends Controller
{
    public function __construct()
    {
        $this->middleware('complete.profile');
    }

    public function index()
    {
        $active = ActivateDiscussion::where('user_id', Auth::id())->first();
        if ($active) {
            return redirect()->route('messages.thread', $active->active_thread_id);
        }
        SEOMeta::setTitle('Messages');
        return view('messenger.index');
    }

    public function show()
    {
        SEOMeta::setTitle('Messages');
        return view('messenger.index');
    }

    public function createConversationForOffer(Request $request, Offer $offer)
    {
        $this->authorize('freelancer');
        $this->authorize('owner', $offer->to->id);

        $participants = participant::where('user_id', $request->user()->id)->get();
        foreach ($participants as $participant) {
            $prtcps = participant::where('thread_id', $participant->thread_id)->get();
            foreach ($prtcps as $prtcp) {
                if ($prtcp->user_id == $offer->from->id) {
                    return redirect()->route('messages.index');
                }
            }
        }

        // create thread || Discussion.
        $thread = Thread::create([
            'subject' => "{$offer->contract_title}",
        ]);

        // Sender
        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => $offer->to->id,
            'last_read' => new \Carbon\Carbon(),
        ]);

        // Recipient
        $thread->addParticipant($offer->from->id);
        return redirect()->route('messages.index');
    }

    public function createConversation(Request $request, Job $job, Proposal $proposal)
    {
        $this->authorize('client');
        $this->authorize('owner', $job->user_id);

        $participants = participant::where('user_id', $request->user()->id)->get();
        foreach ($participants as $participant) {
            $prtcps = participant::where('thread_id', $participant->thread_id)->get();
            foreach ($prtcps as $prtcp) {
                if ($prtcp->user_id == $proposal->user->id) {
                    return redirect()->route('messages.index');
                }
            }
        }

        // create thread || Discussion.
        $thread = Thread::create([
            'subject' => "{$job->project_name}",
        ]);

        // Sender
        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => $job->user_id,
            'last_read' => new \Carbon\Carbon(),
        ]);

        // Recipient
        $thread->addParticipant($proposal->user_id);

        return redirect()->route('messages.index');
    }

    public function conversations(Request $request)
    {
        $threads = Thread::with('users')->forUser(Auth::id())->latest('updated_at')->get();
        $custom_threads = $threads->toArray();

        foreach ($threads as $key => $thread) {
            $custom_threads[$key]['user'] = $thread->participants()->where('user_id', '!=', Auth::id())->first()->user;
            $custom_threads[$key]['latest_message'] = $thread->getLatestMessageAttribute();
            $custom_threads[$key]['unread'] =  (int) $thread->userUnreadMessagesCount(Auth::id());
        }

        return [
            'owner' => Auth::id(),
            'threads' => (empty($custom_threads)) ? null : $custom_threads,
        ];
    }

    public function discussions(Thread $thread)
    {
        return [
            'thread' => $thread,
            'owner' => Auth::id(),
            'user' => $thread->users()->where('user_id', '!=', Auth::id())->first(),
            'messages' => array_reverse($thread->messages()->with('user')->limit(10)->latest('created_at')->get()->toArray()),
        ];
    }

    public function store(StoreMessageRequest $request, Thread $thread)
    {
        $validator = \Validator::make(json_decode($request->content, true), [
            'type' => 'required', //Must be a number and length of value is 8
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Store new message.
        $message = Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::id(),
            'body' => $request->content,
        ]);

        foreach ($message->recipients()->get() as $recipient) {
            if ($recipient->user->presence_status == 'offline')
                $recipient->user->notify(new MessageReceived($message, $recipient->user));
        }

        $message = Message::with('user')->where('id', $message->id)->first();
        broadcast(new NewMessage($message));

        return $message;
    }

    public function active($id)
    {
        $active = ActivateDiscussion::where('user_id', Auth::id())->first();

        if ($active) {
            $active->update([
                'active_thread_id' => $id,
            ]);
        } else {
            $active = ActivateDiscussion::firstOrCreate([
                'user_id' => Auth::id(),
                'active_thread_id' => $id,
            ]);
        }

        return $active;
    }

    public function markAsRead(Thread $thread)
    {
        $unread = (int) $thread->userUnreadMessagesCount(Auth::id());
        $thread->markAsRead(Auth::id());
        return $unread;
    }

    public function previousMessages(Request $request, Thread $thread)
    {
        return array_reverse($thread->messages()->with('user')
            ->where('created_at', '<', $request->before)->limit(10)->latest('created_at')->get()->toArray());
    }
}
