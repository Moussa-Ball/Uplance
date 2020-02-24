<?php

namespace App\Http\Controllers;

use App\Job;
use Vinkla\Hashids\Facades\Hashids;
use App\Http\Requests\BookmarkRequest;

class BookmarkController extends Controller
{
    public function index($bookmark_id, $bookmark_type)
    {
        $class = 'App\\' . $bookmark_type;
        $model = new $class;

        $model = $model::where('id', Hashids::connection($class)->decode($bookmark_id))->first();
        if (!$model) return abort(404);

        $bookmark = $model->bookmarks()->where([
            'bookmark_id' => $model->id,
            'bookmark_type' => 'App\\' . $bookmark_type,
        ])->first();

        if ($bookmark) {
            return response()->json(true);
        } else {
            return response()->json(false);
        }
    }

    public function toggle(BookmarkRequest $request)
    {
        $class = 'App\\' . $request->input('bookmark_type');
        $model = new $class;

        $model = $model::where('id', Hashids::connection($class)->decode($request->input('bookmark_id')))->first();
        if (!$model) return abort(404);

        $bookmark = $model->bookmarks()->where([
            'bookmark_id' => $model->id,
            'bookmark_type' => 'App\\' . $request->input('bookmark_type')
        ])->first();

        if ($bookmark == null) {
            $model->bookmarks()->create([
                'bookmark_id' => $model->id,
                'bookmark_type' => 'App\\' . $request->input('bookmark_type')
            ]);
            return response()->json(true);
        } else {
            $bookmark->delete();
            return response()->json(false);
        }
    }
}
