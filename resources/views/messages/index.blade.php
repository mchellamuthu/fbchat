@extends('layouts.app')

@section('content')
  <audio id="chataudio" src="{{ asset('sound/chat.mp3') }}">

  </audio>
  <router-view></router-view>
@endsection
