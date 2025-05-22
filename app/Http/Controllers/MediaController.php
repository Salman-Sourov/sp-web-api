<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Media;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_media = Media::where('status', 'active')->get();
        return view('backend.all_media', compact('all_media'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $inactive_media = Media::where('status', 'inactive')->get();
        return view('backend.all_inactive_media', compact('inactive_media'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $Media = Media::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'status' =>  'active',
        ]);

        return response()->json(['success' => true, 'message' => 'Media added successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $Media = Media::find($id);

        if ($Media) {
            return response()->json([
                'set_id' => $Media->id ?? null,
                'title' => $Media->title ?? null,
            ]);
        } else {
            return response()->json(['error' => true, 'message' => 'Media not found']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $Media = Media::find($id);

        $request->validate([
            'edit_title' => 'required|string|max:255',
        ]);

        $Media->update([
            'title' => $request->edit_title,
            'slug' => Str::slug($request->edit_title),
        ]);
        return response()->json(['success' => true, 'message' => 'Media updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Media = Media::find($id);
        $Media->status = 'inactive'; // Mark as inactive or deleted

        $Media->save();

        return response()->json([
            'success' => true,
            'message' => 'Media Inacctive successfully',
        ]);
    }

    public function changeStatus(Request $request)
    {

        $media = Media::findOrFail($request->media_id);

        if ($media->status == 'inactive') {
            $media->status = 'active';
            $media->save();
        }

        // Return updated status
        return response()->json(['success' => 'Status changed successfully']);
        // dd($category);

    }

    public function mediaDelete(string $id)
    {
        $Media = Media::find($id);

        if ($Media) {
            $Media->delete();
            return response()->json([
                'success' => true,
                'message' => 'Media deleted successfully.'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Media not found.'
        ]);
    }
}
