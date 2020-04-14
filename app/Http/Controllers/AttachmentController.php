<?php

namespace App\Http\Controllers;

use App\Attachment;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AttachmentRequest;

class AttachmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('complete.profile');
    }

    public function attachments(AttachmentRequest $request)
    {
        $type = $request->get('attachable_type');
        $id = $request->get('attachable_id');

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $dir = 'attachments/jobs/' . date('Y/m/d');
                $file_uploaded = $file->store($dir, 's3');
                Attachment::create([
                    'name' => $file->getClientOriginalName(),
                    'file' =>  Storage::url($file_uploaded),
                    'ext' => $file->getClientOriginalExtension(),
                    'attachable_type' => $type,
                    'attachable_id' => $id,
                ]);
            }
            return response('The files have been sent!');
        }
    }
}
