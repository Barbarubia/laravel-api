@extends('layouts.backoffice')

@section('pageTitle', $post->title)

@section('content')
    <div class="container">
        @if (session('created'))
            <div class="alert alert-success">{{ session('created') }}</div>
        @endif
        @if (session('modified'))
            <div class="alert alert-success">{{ session('modified') }}</div>
        @endif
        <div class="row">
            <div class="col">
                <h1 class="text-capitalize">{{ $post->title }}</h1>
            </div>
        </div>
        <div class="row d-flex justify-content-between mb-3">
            <div class="col-3 p-3">
                <h6>Info post:</h6>
                <small>Category: {{ $post->category->category }}</small><br>
                @if ($post->tags->all())
                    <small>Tags:
                        @foreach ($post->tags as $tag)
                            <span class="bg-primary rounded text-white px-2 py-1">{{ $tag->name }}</span>
                        @endforeach
                    </small><br>
                @endif
                <small>Created: {{ $post->created_at->format('d-m-Y H:i') }}</small>
                @if ($post['updated_at'] != $post['created_at'])
                    <br>
                    <small>Last update: {{ $post->updated_at->format('d-m-Y H:i') }}</small>
                @endif
            </div>
            <div class="col-3 bg-white border border-info border-5 p-3">
                <h6>About the author:</h6>
                <div class="d-flex justify-content-between">
                    <div>
                        <small>Name: {{ $post->user->name }}</small><br>
                        @if ($post->user->userInfo->city)
                            <small>From: {{ $post->user->userInfo->city }}</small><br>
                        @endif
                        @if ($post->user->userInfo->birthday)
                            <small>Age: {{ $post->user->userInfo->age() }}</small>
                        @endif
                    </div>
                    @if ($post->user->userInfo->avatar)
                        <img class="w-25 h-25 rounded-circle" src="{{ $post->user->userInfo->avatar }}" alt="{{ $post->user->name }}'s Avatar">
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                @if ($post['post_image'] != null)
                    <img class="img-fluid mb-3" src=" {{ asset('storage/' . $post->post_image) }}" alt="{{ $post->title }}">
                @endif
                @if ($post['image'] != null)
                    <img class="mb-3" src="{{ $post->image }}" alt="{{ $post->title }}">
                @endif

                <p>{{ $post->content }}</p>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col">
                <a href="{{ route('admin.posts.index') }}" class="btn btn-primary fw-bold">Go to index</a>
            </div>
        </div>
    </div>
@endsection
