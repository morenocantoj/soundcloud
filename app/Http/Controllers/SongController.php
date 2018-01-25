<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Song;

use Illuminate\Support\Facades\Auth;
use App\User;

class SongController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function getUserSongs($id, Request $request)
  {
    if(Auth::user()->id != $id) {
      return view('home');
    } else {
      // get all the songs
      $songs = Song::where('user_id', '=', $id)->get();

      $user = User::find($id);

      // load the view and pass the songs
      return view('songs.index', ['songs' => $songs, 'user' => $user]);
    }
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function getUserSongsUpload()
  {
      // load the create form (app/views/songs/create.blade.php)
      return view('songs.upload');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request) {
    // store
    $song = new Song;
    $song->name         = $request->input('name');
    $song->description  = $request->input('description');
    $song->image        = $request->input('image');
    $song->audio        = $request->input('audio');

    // FIXME: set Date.now() value
    $song->released_at  = null;
    // default values on create song
    $song->plays       = 0;

    // associate with user
    $userId = Auth::user()->id;
    $song->user()->associate($userId);

    // save song
    $song->save();
    return redirect()->action('SongController@getUserSongs', ['id' => $userId]);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id) {
      $song = Song::find($id);
      if($song == null) {
        return redirect()->action('SongController@getUserSongs');
      }
      $user = User::where('id', '=', $song->user_id)->get();
      return view('songs.show', ['song' => $song, 'user' => $user]);
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id) {
      // get the song
      $song = Song::find( $id );
      if($song == null) {
        return redirect()->action('SongController@getUserSongs');
      }
      // show the edit form and pass the song
      return view('songs.edit', ['song' => $song]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $userId
   * @param  int  $songId
   * @return Response
   */
  public function updateUserSong(Request $request, $userId, $songId)
  {
      $user = User::find($userId);
      $song = Song::find($songId);

      if (!$user) return abort(404);
      if (!$song) return abort(404);
      if ($song->user->id != $userId || $song->user->id != Auth::user()->id) return abort(404);

      // validate
      $request->validate([
        'song_photo' => 'required'
      ]);

      // store
      $song->name         = $request->input('name');
      $song->description  = $request->input('description');
      $song->private      = ( $request->input('privacy') == "private" ) ? true : false;
      $song->public_link  = $request->input('public_link');

      if ($request->file('song_photo') && $song->image) {
        // Delete old song image
        Storage::delete($song->image);
      }

      if ($request->file('song_photo')) {
          $song->image = $request->file('song_photo')->store('public');
      }

      // associate with user
      $userId = Auth::user()->id;
      $song->user()->associate($userId);

      // save song
      $song->save();
      return redirect()->action('SongController@getUserSongs', ['id' => $userId]);
  }

  /**
   * Update the song photo
   *
   * @param  int  $userId
   * @param  int  $songId
   * @return Response
   */
  public function putEditPicSong(Request $request, $userId, $songId)
  {
    $user = User::find($userId);
    $song = Song::find($songId);

    if (!$user) return abort(404);
    if (!$song) return abort(404);
    if ($song->user->id != $userId || $song->user->id != Auth::user()->id) return abort(404);

    $request->validate([
      'song_photo' => 'required'
    ]);

    // Delete old song image
    if ($song->image) {
      Storage::delete($song->image);
    }

    // store
    $song->image = $request->file('song_photo')->store('public');
    //$song->save()

    // associate with user
    $userId = Auth::user()->id;
    $song->user()->associate($userId);

    // save song
    $song->save();
    return redirect()->back();
    //return redirect()->action('SongController@getUserSongs', ['id' => $userId]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $userId
   * @param  int  $songId
   * @return Response
   */
  public function deleteUserSong($userId, $songId)
  {
      $song = Song::find($songId);
      if($song != null && $song->user->id == $userId && $song->user->id == Auth::user()->id) {
        $song->delete();
      }
      return redirect()->action('SongController@getUserSongs', ['id' => $userId]);
  }
}
