@extends('layouts.app')

@section('title', 'Марафон по CISCO')

@section('content')
    <div class="flex flex-col gap-4">
        <page-header href="{{ config('app.url')  }}" text="База"></page-header>

        <answer-finder :tasks='@json($tasks)'></answer-finder>
    </div>
@endsection
