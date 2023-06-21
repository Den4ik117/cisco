@extends('layouts.app')

@section('title', 'Билеты по CISCO')

@section('content')
    <div class="grid grid-cols-2 gap-4">
        <form action="{{ route('marathons.store') }}" method="POST">
            @csrf

            <button class="bg-blue-500 hover:bg-blue-600 rounded px-4 py-2 font-medium text-center" type="submit">Марафон</button>
        </form>
{{--        <a class="bg-red-500 hover:bg-red-600 rounded px-4 py-2 font-medium text-center" href="{{ route('marathons.index') }}">Экзамен</a>--}}
    </div>
@endsection
