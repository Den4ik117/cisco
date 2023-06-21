@extends('layouts.app')

@section('title', 'Марафон по CISCO')

@section('content')
    <marathon
        :marathon='@json($marathon)'
    ></marathon>
@endsection
