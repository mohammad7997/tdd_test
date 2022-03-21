@extends('layout')

@section('content')
    <h1> {{ $post->title }} </h1>
    @forelse ($comments as $comment)
        <p>{{ $comment->content }}</p>
        @empty
    @endforelse


    @auth
        <form method="POST" action="{{ route('single.save_comment',$post->id) }}">
            @csrf
            <textarea name="content"></textarea>
        </form>
    @endauth
@endsection
