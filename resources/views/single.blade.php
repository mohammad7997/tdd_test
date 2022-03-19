@extends('layout')

@section('content')
    <h1> {{ $post->title }} </h1>
    @foreach ($comments as $comment)
        <p>{{ $comment->text }}</p>
    @endforeach
@endsection
