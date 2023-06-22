@extends('layouts.app')

@section('title', 'Билеты по CISCO')

@section('content')
    <div class="flex flex-col gap-4">
        <div class="flex justify-center">
            <div class="flex items-center gap-2">
                <img src="{{ Vite::asset('resources/images/DenisZagvozdinLogo.svg') }}" alt="Логотип Дениса Загвоздина" width="24" height="24">
                <span class="text-sm font-bold">Denis Zagvozdin</span>
            </div>
        </div>

        <div class="bg-slate-800 rounded px-4 py-2">
            <img class="block mx-auto" src="{{ Vite::asset('resources/images/Education.svg') }}" alt="Фото процесса обучения" width="240" height="240">
        </div>

        <div class="grid grid-cols-1 gap-4">
            <form action="{{ route('marathons.store') }}" method="POST">
                @csrf

                <button class="block w-full bg-blue-500 hover:bg-blue-600 rounded px-4 py-2 font-medium text-center" type="submit">Марафон</button>
            </form>

            <a class="block w-full bg-gray-500 hover:bg-gray-600 rounded px-4 py-2 font-medium text-sm text-center" href="{{ route('marathons.index') }}">Мои марафоны</a>
            {{--        <a class="bg-red-500 hover:bg-red-600 rounded px-4 py-2 font-medium text-center" href="{{ route('marathons.index') }}">Экзамен</a>--}}
        </div>
    </div>
@endsection
