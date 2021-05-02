<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $keywords = $request->keywords;

        $patients = User::patients()
            ->when($keywords, function ($query, $keywords) {
                $query->where('name', 'like', "%$keywords%")
                    ->orWhere('email', 'like', "%$keywords%");
            })
            ->paginate();

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

    public function update(Request $request, $patient)
    {
        $patient = User::find($patient);
        $rules = ['name' => 'sometimes|required'];
        if ($request->has('email') && $request->email != $patient->email) {
            $rules['email'] = 'sometimes|required|email|unique:users';
        }

        $this->validate($request, $rules);

        $patient->update($request->all());

        return new UserResource($patient);
    }

    public function destroy(Request $request, User $patient)
    {
        $patient->delete();

        return response()->noContent();
    }
}
