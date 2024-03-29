@section('styles')
    @parent
    <link href="{{ asset('assets/css/views/songs/index.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/views/songs/song-extended-view.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/views/songs/modal-delete-song.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/views/components/modal-nav-tabs.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/views/songs/song-form.css') }}" rel="stylesheet">
@stop
<!-- Modal -->
<div class="modal fade" id="modalPlaylist{{$song->id}}" tabindex="-1" role="dialog" aria-labelledby="modalPlaylist{{$song->id}}" aria-hidden="true">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-body">
        <div id="nav-tabs">
          <ul id="playlistModalTabs" role="tablist" class="nav nav-tabs">
            <li class="nav-item active">
              <a class="nav-link active" id="add-tab" data-toggle="tab" href="#add" aria-controls="add" aria-selected="true">
                <h3>Añadir a una lista</h3>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="create-tab" data-toggle="tab" href="#create" aria-controls="create" aria-selected="true">
                <h3>Crear Lista</h3>
              </a>
            </li>
          </ul>
        </div>
        <div class="tab-content tab-modal" id="playlistModalTabs">
          <div class="tab-pane in active" id="add" role="tabpanel" aria-labelledby="add-tab">
            <div class="row">
              <ul class="playlist-modal-index">
                @foreach ($playlists as $playlist)
                  <li class="add-to-playlist">
                    <div class="row">
                      <div class="col-md-3">
                        <img class="image-modal-playlist" @if($playlist->image) src="{{\Storage::url($playlist->image)}}"
                        @else src="{{URL::asset('images/profile-default.png')}}" @endif >
                      </div>
                      <div class="col-md-5">
                        {{$playlist->name}}
                      </div>
                      <div class="col-md-4">
                        @if (!$playlist->containsSong($song))
                          {!! Form::open(['action' => ['PlaylistController@addSongToPlaylist', $playlist->id, $song->id], 'method' => 'post']) !!}
                          {!! Form::token() !!}

                          {{ Form::submit('Añadir a la lista', ['class' => 'btn btn-default']) }}

                          {!! Form::close() !!}
                        @else
                          {!! Form::open(['action' => ['PlaylistController@deleteSongFromPlaylist', $playlist->id, $song->id], 'method' => 'delete']) !!}
                          {!! Form::token() !!}

                          {{ Form::submit('Añadido', ['class' => 'btn btn-default']) }}

                          {!! Form::close() !!}
                        @endif
                      </div>
                    </div>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>
          <div class="tab-pane in" id="create" role="tabpanel" aria-labelledby="create-tab">
            {!! Form::open(['action' => ['PlaylistController@createPlaylistFromModal', $user_id, $song->id], 'method' => 'post']) !!}
            {!! Form::token() !!}
            <div class="row">
              <div class="col-md-12">
                <div class="form-group modal-label">
                  {{ Form::label('name', 'Título de la lista *', ['class' => 'form-label']) }}
                  {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required']) }}
                </div>
              </div>
              <div class="col-md-12">
                <div class="form-group modal-label">
                    La lista quedará &nbsp;
                    <label class="radio-inline">{{ Form::radio('private', 'true', true) }} privada</label>
                    <label class="radio-inline">{{ Form::radio('private', 'false', false, ['class' => 'radio']) }} pública</label>
                    {{ Form::submit('Guardar', ['class' => 'btn btn-save-modal pull-right']) }}
                </div>
              </div>
            </div>
            {!! Form::close() !!}
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
