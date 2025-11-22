<?php

namespace App\Http\Controllers;

use App\Models\ObjectStore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ObjectStoreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        ObjectStore::create([
            'key'                           => $request->input('key'),
            'value'=> $request->input('value'),
            'created_at_timestamp' => now()->timestamp,
        ]);

        return response()->json([
            'success'               => true,
            'message' => 'Resource created successfully',
            'data' => [],
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        return response()->json([
            'success'                   => true,
            'data' => [],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Resource updated successfully',
            'data' => [],
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Resource deleted successfully',
        ]);
    }
}
