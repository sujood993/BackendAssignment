<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Http\Resources\ItemResource;
use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
        $items = ItemResource::collection(Item::all());
        return response()->json(['data' => $items], 200);
    }

    public function store(ItemRequest $request)
    {
        $item = Item::create($request->validated());
        return response()->json(['data' => ItemResource::make($item)], 201);
    }

    public function show($id)
    {

        $item = Item::find($id);

        if (!$item) {
            return response()->json(['message' => 'Item Not Found.'], 404);
        }
        return response()->json(['data' => ItemResource::make($item)], 200);
    }

    public function update(ItemRequest $request, $id)
    {
        $item = Item::find($id);
        if (!$item) {
            return response()->json(['message' => 'Item Not Found.'], 404);
        }
        $item->fill($request->validated())->save();
        return response()->json(['data' => ItemResource::make($item)], 200);
    }

}