<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Idea;

// Denne controller tager hånd om CRUD. 
// Når en VUE component skal trække eller manipulere med databasen, kalder den disse funktioner med axios.
// Funktionerne returnere JSON-packages, som VUE-componenterne bruger til deres views.
// Der bruges Eloquent til at interagere med databasen.

class IdeaController extends Controller
{

    // I denne contructor, aktiverer vi adgangskontrol. Kun brugere som er logged ind, kan skrive til databasen.  
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    // Her hentes alle ideér 
    public function index()
    {
        $ideas = Idea::orderBy('created_at', 'desc')->get();

        // Adgang til navnet på forfatteren.
        foreach ($ideas as $idea){
            $idea->author = $idea->user->name;
        }

        return response($ideas->jsonSerialize(), Response::HTTP_OK);
    }

    // Her hentes ideér hvor kun at det er ideér fra den bruger som er logged ind.
    public function dashboard()
    {
        $ideas = Idea::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();

        return response($ideas->jsonSerialize(), Response::HTTP_OK);
    }


    public function store(Request $request)
    {
        // De input fra formen, som brugeren har indtastet skal valideres. 
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);

        // Der oprettes en ny model, og dets fields bliver opdateret med brugerens input.
        $idea = new Idea;

        $idea->fill($request->all());
        $idea->user_id = auth()->user()->id;
        
        // Committer. 
        $idea->save();

        // Vi sender ideén samt en besked som JSON.
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

        // Finder den eksisterende idé fra databasen, og ændrer dets fields i modellen.
        $idea = Idea::find($id);
        
        // Bruger modellen, med en fillable property.
        $idea->update($request->all());       

        return response()->json([
            'message' => 'Successfully updated the database'
        ], 200);        
    }

    public function destroy($id) {
        $idea = Idea::find($id);

        // Et dobbelt sikkerhedscheck.
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