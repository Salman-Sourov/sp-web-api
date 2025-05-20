<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\About;
use Illuminate\Support\Facades\File;

class AboutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $about = About::first(); // Get the first record, not a collection
        return view('backend.all_abouts', compact('about'));
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id = null)
    {
        $request->validate([
            'about' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Get or create About record
        $about = $id ? About::find($id) : About::first();

        if (!$about) {
            $about = new About();
        }

        $about->about = $request->about;

        if ($request->hasFile('image')) {
            if ($about->image && File::exists(public_path($about->image))) {
                File::delete(public_path($about->image));
            }

            $file = $request->file('image');
            $filename = date('YmdHi') . '_' . $file->getClientOriginalName();
            $file->move(public_path('upload/about'), $filename);
            $about->image = 'upload/about/' . $filename;
        }

        $about->save();

        return redirect()->back()->with('success', 'About section updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
