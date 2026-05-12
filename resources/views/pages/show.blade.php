@extends('layouts.app')

@section('title', $title . ' - Beam Gifts')

@section('content')
<div class="bg-light py-5 mb-5">
    <div class="container">
        <h1 class="display-4 fw-bold text-primary">{{ $title }}</h1>
    </div>
</div>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card shadow-sm border-0 rounded-4 p-4 p-md-5">
                <div class="card-body content-area">
                    {!! nl2br(e($content)) !!}
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .content-area {
        line-height: 1.8;
        color: #4a4a4a;
        font-size: 1.1rem;
    }
</style>
@endsection
