<?php

namespace App\Http\Controllers;

use App\Models\WriteUp;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class WriteUpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : Response
    {
        //dd(WriteUp::with('user:id,name')->with('images')->latest()->get());
        return Inertia::render('WriteUps/Index',[
            'writeups' => WriteUp::with('user:id,name')->with('images')->latest()->get()
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
            'content' => 'required|string|max:255',
            //validate each file in the files array
            'files.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        //store current user id for later reference
        $user_id = $request->user()->id;
        //create the writeup first to be able to use its id later
        $writeup = $request->user()->writeups()->create([
            'title' => $validated['title'],
            'content' => $validated['content'],
        ]);

        $imagePaths = [];

        // Check if files were uploaded and store the images on disk
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $image) {
                $hashedName = $image->hashName(); 
                $imagePaths[] = $image->storeAs(
                    "images/{$user_id}/{$writeup->id}", // Specify the directory structure
                    $hashedName // Use the original filename
                );
                // Create an image record and associate it with the writeup
                foreach($imagePaths as $imagePath)
                {
                    $writeup->images()->create([
                        'image_path' => $imagePath,
                    ]);
                }
                
            }
        }

        
        return redirect(route('writeups.index'))->with('success', 'Writeup created successfully.');
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
