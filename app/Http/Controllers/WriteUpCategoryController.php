<?php

namespace App\Http\Controllers;

use App\Models\WriteUpCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;


class WriteUpCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = WriteUpCategory::all();
        return $categories;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, WriteUpCategory $writeUpCategory)
    {
        $validated = $request->validate([
            'name.*' => 'required|string|max:255',
        ]);
        
        foreach ($validated['name'] as $name) {
            $category = new WriteUpCategory();
            $category->name = $name;
            $category->save();
        }
    
        // Return a response or redirect as needed
        return response()->json(['message' => 'Categories created successfully'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(WriteUpCategory $writeUpCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WriteUpCategory $writeUpCategory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WriteUpCategory $writeUpCategory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WriteUpCategory $writeUpCategory)
    {
        //
    }
}
