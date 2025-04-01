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

        $validated = request()->validate([
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'sort_by' => 'sometimes|string|in:id,name,description,created_at,updated_at',
            'sort_direction' => 'sometimes|string|in:asc,desc',
            'search' => 'sometimes|string|max:255' // Validasi sebagai string
        ]);

        $query = Item::query();

        // Apply search dengan sanitasi tambahan
        if (!empty($validated['search'])) {
            $search = htmlspecialchars($validated['search'], ENT_QUOTES, 'UTF-8');
            $search = filter_var($validated['search'], FILTER_SANITIZE_STRING);
            $search = '%' . $search . '%';

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', $search)
                    ->orWhere('description', 'like', $search);
            });
        }

        // Apply sorting
        if (!empty($validated['sort_by'])) {
            $query->orderBy($validated['sort_by'], $validated['sort_direction'] ?? 'asc');
        } else {
            $query->latest();
        }

        return ItemResource::collection(
            $query->paginate($validated['per_page'] ?? 10)
        );
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
