@extends('layouts.app')

@section('title', 'База по курсу')

@section('content')
    <div class="flex flex-col gap-4">
        <page-header href="{{ config('app.url') }}" text="База по курсу"></page-header>

        <answer-finder :tasks='@json($tasks)'></answer-finder>
    </div>
@endsection
