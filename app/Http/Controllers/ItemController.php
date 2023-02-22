<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemRequest;
use App\Http\Resources\ItemResource;
use App\Models\Item;

class ItemController extends Controller
{
    public function index()
    {
        return ItemResource::collection(Item::all());
    }

    public function store(ItemRequest $request)
    {
        return ItemResource::make(Item::create($request->validated()));
    }

    public function show(Item $item)
    {
        return ItemResource::make($item);
    }

    public function update(ItemRequest $request, Item $item)
    {
        $item->fill($request->validated())->save();

        return ItemResource::make($item);
    }

}