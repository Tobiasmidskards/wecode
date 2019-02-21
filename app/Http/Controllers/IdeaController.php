<?php

//https://www.cloudways.com/blog/laravel-vue-single-page-app/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Idea;

class IdeaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $ideas = Idea::orderBy('created_at', 'desc')->get();

        // Adgang til navnet på forfatteren.
        foreach ($ideas as $idea){
            $idea->author = $idea->user->name;
        }

        return response($ideas->jsonSerialize(), Response::HTTP_OK);
    }

    public function dashboard()
    {
        $ideas = Idea::where('user_id', auth()->user()->id)->get();

        return response($ideas->jsonSerialize(), Response::HTTP_OK);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);

        // Create Idea
        $idea = new Idea;
        $idea->title = $request->input('title');
        $idea->body = $request->input('body');
        $idea->user_id = auth()->user()->id;

        $idea->save();

        $idea = $idea->jsonSerialize();

        return response()->json([
            'idea' => $idea,
            'message' => 'Successfully uploaded to database'
        ], 200);
    }

    public function update(Request $request, $id){
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);

        $idea = Idea::find($id);
        $idea->title = $request->input('title');
        $idea->body = $request->input('body');

        $idea->save();

        return response()->json([
            'message' => 'Successfully updated the database'
        ], 200);        
    }

    public function destroy($id) {
        $idea = Idea::find($id);

        if (auth()->user()->id !== $idea->user_id) {
            return response()->json([
                'message' => 'Unauthorized delete from database'
            ], 200);
        }

        $idea->delete();

        return response()->json([
            'message' => 'Successfully delete of record'
        ], 200);

    }
}