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
                    "public/images/{$user_id}/{$writeup->id}", // Specify the directory structure
                    $hashedName // Use the original filename
                );
                // Create an image record and associate it with the writeup through its images() relationship defined on the model
                foreach($imagePaths as $imagePath)
                {
                    /*replace the public part in the url with storage so laravel can make use of it using 'php artisan storage:link':
                    The php artisan storage:link command in Laravel creates a symbolic link from the public/storage directory to the
                    storage/app/public directory. This makes it convenient to access files stored in the storage/app/public directory
                    from the web server, as they can be served directly through the public directory.When you upload files using Laravel's
                    file storage system and store them in the storage/app/public directory, they're not directly accessible via the web server. 
                    By creating a symbolic link using php artisan storage:link, Laravel makes it possible for the web server to serve these
                    files through the public directory, providing direct access to them.
                    */
                    $writeup->images()->create([
                        'image_path' => str_replace("public", "storage", $imagePath)
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
    public function destroy($id): Response
    {
        $writeUp = WriteUp::where('id', $id)->first();
        $writeUp->delete();

        // Return a response with the updated list of writeups
        return Inertia::render('WriteUps/Index', [
            'writeups' => WriteUp::with('user:id,name')->with('images')->latest()->get()
        ]);
    }
}
