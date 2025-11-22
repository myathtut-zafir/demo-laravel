<?php

namespace App\Http\Controllers;

use App\Models\ObjectStore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ObjectStoreController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        ObjectStore::create([
            'key' => $request->input('key'),
            'value' => $request->input('value'),
            'created_at_timestamp' => now()->timestamp,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Resource created successfully',
            'data' => [],
        ], 201);
    }


}
