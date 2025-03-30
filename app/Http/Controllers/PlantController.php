<?php

namespace App\Http\Controllers;

use App\Services\Api\Exceptions\ApiException;
use App\Services\Api\PerenualApiService;
use Illuminate\Http\Request;

class PlantController extends Controller
{
    public function __construct(protected PerenualApiService $apiService)
    {
        //
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /*
         * @TODO: extract the api call into an action and handle things like error logging there
         * */
        try {
            $response = $this->apiService->getSpecies();

            return response()->json($response->toArray(), 200);
        } catch (ApiException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($speciesId)
    {
        /*
         * @TODO: extract the api call into an action and handle things like error logging there
         * */
        try {
            $response = $this->apiService->getSingleSpecies($speciesId);

            return response()->json($response->toArray(), 200);
        } catch (ApiException $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
