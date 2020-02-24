<?php

namespace App\Http\Controllers;

use App\Skill;
use App\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Requests\AvatarRequest;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\AccountRequest;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use Artesaos\SEOTools\Facades\SEOMeta;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\SkillsCollection;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Job;
use Illuminate\Validation\ValidationException;

/**
 * Allows you to manage everything related to the user settings.
 *
 * @author Moussa Ball
 */
class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        SEOMeta::setTitle('Profile Settings');
        return view('profiles.settings');
    }

    /**
     * Change the profile picture.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateAvatar(AvatarRequest $request)
    {
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar')->store('avatars', 's3');
            $user = $request->user();
            $user->avatar = Storage::url($avatar);
            $user->save();
            return response()->json('Your profile picture has been updated!');
        }
    }

    /**
     * Update the current profile account information to new information.
     *
     * @return \Illuminate\Http\Response
     */
    public function updateAccount(AccountRequest $request)
    {
        if (!$request->user()->current_account) {
            $request->user()->update([
                'current_account' => $request->input('account_type'),
                'account_type' => $request->input('account_type'),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'name' => $request->input('first_name') . ' ' . $request->input('last_name'),
                'email' => $request->input('email'),
            ]);
        } else {
            $request->user()->update([
                'account_type' => $request->input('account_type'),
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'name' => $request->input('first_name') . ' ' . $request->input('last_name'),
                'email' => $request->input('email'),
            ]);
        }
        return response()->json('Your account has been updated!');
    }

    /**
     * Update the current profile information to new information.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(ProfileRequest $request)
    {
        $skills = $request->input('skills');
        $skills = explode(',', $skills);

        if ($skills && is_array($skills)) {
            foreach ($skills as $skill) {
                $skill_exist = Skill::where('name', $skill)->first();
                if (!$skill_exist) {
                    Skill::create(['name' => $skill, 'slug' => Str::slug($skill)]);
                }
            }
            $request->user()->retag($skills);
        } elseif ($skills && !is_array($skills)) {
            $request->user()->retag([$skills]);
        }

        $category = Category::where('name', $request->category)->first();
        $request->user()->categories()->sync($category);
        $request->user()->update($request->all());
        $request->user()->update(['category_id' => $category->id]);
        return response()->json('Your profile has been updated!');
    }

    /**
     * Update the current password to new password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(PasswordRequest $request)
    {
        $user = $request->user();
        $current = $request->input('current_password');
        if ($user->password && Hash::check($current, $user->password)) {
            $user->password = Hash::make($request->password);
            $user->save();
        } elseif ($current && $user->password && !Hash::check($current, $user->password)) {
            $validator = Validator::make([], []); // Empty data and rules fields
            $validator->errors()->add('current_password', 'The current password field is invalid.');
            throw new ValidationException($validator);
        } elseif (!$user->password) {
            $user->password = Hash::make($request->password);
            $user->save();
        }
        return response()->json('Your password has been updated!');
    }

    /**
     * Allows to get all categories for api.
     *
     * @return \Illuminate\Http\JsonResource
     */
    public function categories()
    {
        return new CategoryCollection(Category::select('name')->get());
    }

    /**
     * Allows to get all skills for api.
     *
     * @return \Illuminate\Http\JsonResource
     */
    public function skills()
    {
        return new SkillsCollection(Skill::select('name')->get());
    }

    /**
     * Allows to get the current category of user for.
     *
     * @return \Illuminate\Http\JsonResource
     */
    public function category(Request $request)
    {
        $tag = $request->user()->categories()->first();
        return new CategoryResource($tag);
    }

    /**
     * Allows to remove an user account.
     *
     * @return \Illuminate\Http\JsonResource
     */
    public function removeAccount(Request $request)
    {
        $request->user()->delete();
        return response()->json('Your account is now inactive, it will be deleted later. 
        If however you plan to return, please contact customer service.');
    }
}
