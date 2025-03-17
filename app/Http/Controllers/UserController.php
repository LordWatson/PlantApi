<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Create the Controller.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            UserResource::collection(User::all()), 200
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return response()->json(
            UserResource::make($user), 200
        );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        /*
         * email should be unique
         * if a user PUTs name through request with the existing name
         * so we tell the validation to ignore the unique rule when this happens
         * */
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'string',
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user),
            ],
        ]);

        // create array of values to update
        $updateArray = $validated;
        $updateArray['updated_at'] = date('Y-m-d H:i:s');

        // persist
        $user->update($updateArray);

        // build return array to show new resource and the fields that were changed in the update
        $returnArray = [
            'user' => UserResource::make($user),
            'updated' => $user->getChanges(),
        ];

        return response()->json(
            $returnArray, 200
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
