@extends('layouts.app')

@section('styles')
    @parent
    <link href="{{ asset('assets/css/views/profile.css') }}" rel="stylesheet">
@stop

@section('content')
<div class="container">
  {{-- Profile header --}}
  <div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12 profile-header">
      <div class="image-banner" @if($user->landscape_photo) "style: background-image: {{URL::asset('images/'.$user->landscape_photo)}}"
      @else id="background-default" @endif >
        <div class="row profile-image-row">
          <div class="col-md-3 profile-image-container">
            <div class="profile-photo">
              <img class="profile-image" @if($user->profile_photo) src="{{asset('images/'.$user->profile_photo)}}"
              @else src="{{URL::asset('images/profile-default.png')}}" @endif>
              <a href="#" class="btn btn-xs btn-default upload-profile-photo-button">
                <i class="fa fa-camera"></i>Actualizar imagen</a>
            </div>
          </div>
          {{--
          <div class="row">
            <div class="col-md-12">
              <h3 class="user-profile-name">{{$user->name}}</h3>
            </div>
          </div>
          <div class="col-md-3">
            <a href="#" class="btn btn-xs btn-default upload-landphoto">
              <i class="fa fa-camera"></i>Subir imagen de cabecera</a>
          </div>
          --}}
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-md-9">
      <ul class="nav nav-tabs" id="tabProfile" role="tablist">
        <li class="nav-item active">
          <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" aria-controls="all" aria-selected="true">
            Todo
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="songs-tab" data-toggle="tab" href="#songs" aria-controls="songs" aria-selected="false">
            Pistas
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="playlists-tab" data-toggle="tab" href="#playlists" aria-controls="playlists" aria-selected="false">
            Listas
          </a>
        </li>
        </li>
      </ul>
    </div>
    <div class="col-md-3">
      @if (Auth::user() && Auth::user()->id == $user->id)
      <button type="button" class="btn btn-sm btn-default pull-right" data-toggle="modal" data-target="#editProfileModal">
        <i class="fa fa-pencil"></i>&nbsp; Editar perfil
      </button>
      <!-- Modal -->
      <div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModal" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          {!! Form::open(['action' => ['UserController@putEditProfile', $user->id], 'method' => 'put']) !!}
          {!! Form::token() !!}
          <div class="modal-content">
            <div class="modal-body">
              <h3>Modifica tu perfil <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              </h3>
              <legend></legend>
              <div class="row">
                <div class="col-md-3">

                </div>
                <div class="col-md-9">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        {{ Form::label('nick', 'Nombre para mostrar *', ['class' => 'form-label']) }}
                        {{ Form::text('nick', $user->nick, ['class' => 'form-control', 'required' => 'true']) }}
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        {{ Form::label('name', 'Nombre para mostrar *', ['class' => 'form-label']) }}
                        {{ Form::text('name', $user->name, ['class' => 'form-control', 'required' => 'true']) }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>

            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
              {{ Form::submit('Guardar cambios',['class' => 'btn btn-primary', 'type' => 'button']) }}
            </div>
          </div>
          {!! Form::close() !!}
        </div>
      </div>
    </div>
    @endif
  </div>
  <div class="row" style="margin-top: 20px">
    <div class="col-md-8 col-sm-12 col-xs-12">
      <div class="tab-content" id="tabProfile">
        <div class="tab-pane fade in active" id="all" role="tabpanel" aria-labelledby="all-tab">
          <div class="row">
            <div class="col-md-12">
              @if (count($user->songs) == 0) <h5>No hay elementos que mostrar</h5> @endif
              @foreach($user->songs as $song)
                <div class="song-container">
                  <h4>{{$song->name}}</h4>
                </div>
              @endforeach
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="songs" role="tabpanel" aria-labelledby="songs-tab">
          <div class="row">
            <div class="col-md-12">
              @if (count($user->songs) == 0) <h5>No hay elementos que mostrar</h5> @endif
              @foreach($user->songs as $song)
                <div class="song-container">
                  <h4>{{$song->name}}</h4>
                </div>
              @endforeach
            </div>
          </div>
        </div>
        <div class="tab-pane fade" id="playlists" role="tabpanel" aria-labelledby="playlists-tab">
          <div class="row">
            <div class="col-md-12">
              @if (count($user->playlists) == 0) <h5>No hay elementos que mostrar</h5> @endif
              @foreach($user->playlists as $playlist)
                <div class="song-container">
                  <h4>{{$playlist->name}}</h4>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop
