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
        $data = $this->validate($request, [
            'content' => 'required'
        ]);

        $data['user_id'] = $patient->id;

        $note = Note::create($data);
        $resource = new NoteResource($note);

        return response()->json(
            $resource->response()->getData(true),
            201
        );
    }
}
