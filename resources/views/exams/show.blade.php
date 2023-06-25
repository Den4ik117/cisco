@extends('layouts.app')

@section('title', 'Экзамен по CISCO')

@section('content')
    <div class="flex flex-col gap-4">
        <test
            :test='@json($test)'
        ></test>
    </div>
@endsection
