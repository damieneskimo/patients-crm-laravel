<?php

namespace App\Http\Controllers;

use App\Http\Resources\NoteResource;
use App\Models\Note;
use App\Models\User;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, User $patient)
    {
        return response()->json([
            'data' => NoteResource::collection($patient->notes)->toArray($request)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $patient)
    {
        $this->validate($request, [
            'content' => 'required'
        ]);

        $input = $request->all();
        $input['user_id'] = $patient->id;

        $note = Note::create($input);

        return new NoteResource($note);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Note $note
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $patient, Note $note)
    {
        $this->validate($request, [
            'content' => 'required'
        ]);

        $input = $request->all();

        $note = Note::where('id', $note->id)->update($input);

        return new NoteResource($note);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
