<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\TemporaryPicture;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $albums = auth()->user()->albums()->paginate(15);

        return view('albums.index', compact('albums'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

        return view('albums.create')->with('Album Created Successfully!');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        auth()->user()->albums()->create($validated);

        return redirect()->route('albums.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function show(Album $album)
    {
        //
        $pictures = $album->getMedia('pictures');

        return view('albums.show', compact('album', 'pictures'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function edit(Album $album)
    {
        //

        return view('albums.edit', compact('album'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Album $album)
    {
        //

        $validated = $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $album->update($validated);

        return redirect()->route('albums.index')->with('Album Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Album  $album
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Album $album)
    {
        //
        if ($request->ajax()) {
            $pictures = $album->getMedia('pictures');

            if ($pictures->isEmpty()) {
                $album->delete();
                return 1;
            } else {
                return 0;
            }
        }
    }

    public function upload(Request $request, Album $album)
    {
        $request->validate([
            'picture' => 'required|string|max:255'
        ]);

        $temporaryPicture = TemporaryPicture::where('folder', $request->picture)->first();

        if ($temporaryPicture) {
            $album
                ->addMedia(storage_path('app/public/pictures/tmp/' . $request->picture . '/' . $temporaryPicture->pictureName))
                ->toMediaCollection('pictures');

            rmdir(storage_path('app/public/pictures/tmp/' . $request->picture));

            $temporaryPicture->delete();
        }

        return redirect()->route('albums.show', $album->id);
    }

    public function deleteAll(Request $request, Album $album)
    {

        if ($request->ajax()) {
            $album->delete();
        }
    }

    public function movePictures(Request $request, Album $album)
    {
        if($request->ajax()) {
            $intendedAlbum = Album::where('name', $request->albumName)->first();
            if($intendedAlbum) {
                $mediaItems = $album->getMedia('pictures');
                foreach($mediaItems as $mediaItem) {
                    $mediaItem->move($intendedAlbum, 'pictures');
                }

                $album->delete();

                return 1;
            } else {
                return response()->json(['error' => 'No album found with this name']);;
            }
        }
    }

}
