@extends('layouts.app')

@section('title', 'Марафон по CISCO')

@section('content')
    <div class="flex flex-col gap-4">


        <marathon
            :marathon='@json($marathon)'
        ></marathon>
    </div>
@endsection
