<?php

namespace App\Http\Controllers;

use App\Models\Home;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allHomes = Home::all();
        return view('backend.all_homes', compact('allHomes'));
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
        if ($request->file('image')) {
            $image = $request->file('image');
            $photoName = date("Y-m-d") . '.' . rand() . '.' . time() . '.' . $image->getClientOriginalExtension();
            $directory = 'upload/home/';
            $image->move($directory, $photoName);
        }

        // Store brand data in the database
        if ($request->file('image')) {
            $home = Home::create([
                'name' => $request->name,
                'email' => $request->email,
                'image' => $directory . $photoName,
                'status' => 'active',
            ]);
        } else {
            $home = Home::create([
                'name' => $request->name,
                'email' => $request->email,
            ]);
        }

        // Flash message for success
        $notification = array(
            'message' => 'Home Data inserted', // The message you want to display
            'alert-type' => 'success' // Success notification type
        );

        return response()->json(['success' => true, 'message' => 'Home content added successfully']);
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
        $home = Home::find($id);

        if ($home) {
            return response()->json([
                'id' => $home->id ?? null,
                'name' => $home->name ?? null,
                'email' => $home->email ?? null,
                'image' => $home->image ?? null,
            ]);
        } else {
            return response()->json(['error' => true, 'message' => 'Home not found']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $home = Home::find($id);

        if ($request->file('edit_image')) {
            // Delete old image if it exists
            if (!empty($home->image) && file_exists(public_path($home->image))) {
                unlink(public_path($home->image));
            }

            // Save new image
            $image = $request->file('edit_image');
            $photoName = date("Y-m-d") . '_' . time() . '.' . $image->getClientOriginalExtension();
            $directory = 'upload/home/';
            $image->move(public_path($directory), $photoName); // move to public path
            $home->image = $directory . $photoName;
        }

        // Update all fields (including image if changed)
        $home->name = $request->edit_name;
        $home->email = $request->edit_email;

        $home->save(); // Save all changes at once

        return response()->json(['success' => true, 'message' => 'Home updated successfully']);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $home = Home::find($id);

        if ($home) {
            // Check if logo exists and delete the file
            if (file_exists(public_path($home->image)) && !empty($home->image)) {
                unlink(public_path($home->image)); // Delete logo file
            }

            // Delete the brand record
            $home->delete();

            return response()->json([
                'success' => true,
                'message' => 'Home deleted successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Home not found.'
        ]);
    }
}
