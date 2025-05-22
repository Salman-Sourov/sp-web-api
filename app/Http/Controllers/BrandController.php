<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $allBrands = Brand::all();
        return view('backend.all_brands', compact('allBrands'));
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
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->file('image')) {
            $image = $request->file('image');
            $photoName = date("Y-m-d") . '.' . rand() . '.' . time() . '.' . $image->getClientOriginalExtension();
            $directory = 'upload/brand/';
            $image->move($directory, $photoName);
        }

        // Store brand data in the database
        if ($request->file('image')) {
            $brand = Brand::create([
                'name' => $request->name,
                'image' => $directory . $photoName,
            ]);
        } else {
            $home = Brand::create([
                'name' => $request->name,
            ]);
        }

        // Flash message for success
        $notification = array(
            'message' => 'Brand inserted', // The message you want to display
            'alert-type' => 'success' // Success notification type
        );

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
        $brand = Brand::find($id);

        if ($brand) {
            return response()->json([
                'id' => $brand->id ?? null,
                'name' => $brand->name ?? null,
                'image' => $brand->image ?? null,
            ]);
        } else {
            return response()->json(['error' => true, 'message' => 'Brand not found']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $request->validate([
            'edit_name' => 'required|string|max:255',
            'edit_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $brand->name = $request->edit_name;

        if ($request->hasFile('edit_image')) {
            // Delete old image
            if ($brand->image && file_exists(public_path($brand->image))) {
                unlink(public_path($brand->image));
            }

            $image = $request->file('edit_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('upload/brand/'), $imageName);
            $brand->image = 'upload/brand/' . $imageName;
        }

        $brand->save();

        return response()->json([
            'success' => true,
            'message' => 'Brand updated successfully.'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $brand = Brand::find($id);

        if ($brand) {
            // Check if logo exists and delete the file
            if (file_exists(public_path($brand->image)) && !empty($brand->image)) {
                unlink(public_path($brand->image)); // Delete logo file
            }

            // Delete the brand record
            $brand->delete();

            return response()->json([
                'success' => true,
                'message' => 'Brand deleted successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Brand not found.'
        ]);
    }
}
