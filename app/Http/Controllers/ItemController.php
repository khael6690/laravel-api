<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Resources\ItemResource;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    public function index()
    {
        return ItemResource::collection(Item::latest()->paginate(10));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $item = $request->user()->items()->create($validated);

        return response()->json($item, 201);
    }

    public function show(Item $item)
    {
        return new ItemResource($item);
    }

    public function update(Request $request, Item $item)
    {
        $this->authorize('update', $item);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $item->update($validated);

        return response()->json($item);
    }

    public function destroy(Item $item)
    {
        $this->authorize('delete', $item);

        $item->delete();

        return response()->json(null, 204);
    }
}
