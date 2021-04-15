<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $patients = User::patients()->with('notes')->get();

        return UserResource::collection($patients);
    }

    public function show(Request $request, User $patient)
    {
        return new UserResource($patient);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users',
            'gender' => 'required',
        ]);

        $patient = User::create($request->all());

        return new UserResource($patient);
    }

    public function update(Request $request, User $patient)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|unique:users',
            'gender' => 'required',
        ]);

        $patient = User::where('id', $patient->id)->update($request->all());

        return new UserResource($patient);
    }

    public function destroy(Request $request, User $patient)
    {
        $patient->delete();

        return response()->noContent();
    }
}
