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

        // Redirect back to the list page with the notification
        // return redirect()->back()->with($notification);
        // Return success response
        return response()->json(['success' => true, 'message' => 'Brand added successfully']);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
