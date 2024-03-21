<?php

namespace App\Http\Controllers;

use App\Models\WriteUp;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class WriteUpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : Response
    {
        return Inertia::render('WriteUps/Index',[
            'writeups' => WriteUp::with('user:id,name')->latest()->get()
        ]);
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
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|max:255'
        ]);
        $request->user()->writeups()->create($validated);

        return redirect(route('writeups.index'))->with('test');
    }

    /**
     * Display the specified resource.
     */
    public function show(WriteUp $writeUp)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(WriteUp $writeUp)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, WriteUp $writeUp)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(WriteUp $writeUp)
    {
        //
    }
}
