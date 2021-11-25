<?php

namespace App\Http\Controllers;

use App\Link;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;

class LinkController extends Controller
{

    /**
     * Save user link with short link
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function save(Request $request)
    {
        $link = $request->link;
        $userId = $request->user_id;

        //validate link
        $validator = Validator::make($request->all(), [
            'link' => 'regex:/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/'
        ]);

        if($validator->fails()){
            return response()->json(['message'=> 'Link is not valid!']);
        }

        //create short link
        $shortUrl = Str::random(16);

        //find existing user/link
        $link = Link::where('user_id', $userId)->where('link', $link)->first();

        if($link){
            return response()->json(['message'=> 'Link already exists']);
        }

        Link::crate([
            'link' => $link,
            'user_id' => $userId,
            'short_url' => $shortUrl
        ]);

        return response()->json(['success' => true, 'message' => 'Link created!']);

    }

    /**
     * Delete link
     *
     * @param  string $link
     */
    public function deleteLink($link)
    {
        $user_id = $user = Auth::id();

        $link = Link::where('link', $link)->where('user_id', $user_id)->first();

        $link->delete();

        return response()->json(['success' => true, 'message' => 'Link deleted!']);

    }


    /**
     * Count users with the same link
     *
     * @param  string $link
     */
    public function count($link)
    {
        $link = Link::where('link', $link)->groupBy('user_id')->get();

        if($link->isEmpty()){
            return response()->json(['message' => 'Link not found!']);
        }

        $count = $link->count();

        return response()->json(['success' => true, 'Users with same link:' . $count]);

    }


    /**
     * Redirect from shor link
     *
     * @param  string $shortUrl
     */
    public function redirect($shortUrl)
    {

        $link = Link::where('short_link', $shortUrl)->first();

        $link = $link -> link;

        return Redirect::away($link);

    }

}
