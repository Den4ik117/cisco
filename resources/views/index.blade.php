@extends('layouts.app')

@section('title', 'Билеты по CISCO')

@section('head')
    <meta name="description" content="Это сайт, позволяющий выучить билеты, которые будут на экзамене по CISCO">
    <meta property="og:title" content="Билеты по CISCO">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:image" content="{{ Vite::asset('resources/images/Promo.jpg') }}">
    <meta property="og:description" content="Это сайт, позволяющий выучить билеты, которые будут на экзамене по CISCO">
    <meta property="og:locale" content="ru_RU">
    <meta property="og:site_name" content="Билеты по CISCO">
@endsection

@section('content')
    <div class="flex flex-col gap-4">
        <div class="flex justify-center">
            <a class="flex items-center gap-2" href="https://deniszagvozdin.ru/" target="_blank">
                <img src="{{ Vite::asset('resources/images/DenisZagvozdinLogo.svg') }}" alt="Логотип Дениса Загвоздина" width="24" height="24">
                <span class="text-sm font-bold">Denis Zagvozdin</span>
            </a>
        </div>

        <a class="bg-slate-800 hover:bg-slate-700 rounded px-4 py-2" href="{{ route('tests.index') }}">
            <img class="block mx-auto" src="{{ Vite::asset('resources/images/Education.svg') }}" alt="Фото процесса обучения" width="240" height="240">
        </a>

        <div class="grid grid-cols-2 gap-4">
{{--            <form action="{{ route('marathons.store') }}" method="POST">--}}
{{--                @csrf--}}

                <a class="block w-full bg-blue-500 hover:bg-blue-600 rounded px-4 py-2 font-medium text-center" href="{{ route('marathons.create') }}">Марафон</a>
{{--            </form>--}}

            <form action="{{ route('exams.store') }}" method="POST">
                @csrf

                <button class="block w-full bg-red-500 hover:bg-red-600 rounded px-4 py-2 font-medium text-center" type="submit">Экзамен</button>
            </form>

            <a class="col-span-full bg-gray-500 hover:bg-gray-600 rounded px-4 py-2 font-medium text-sm text-center" href="{{ route('marathons.index') }}">Мои марафоны</a>
            {{--        <a class="bg-red-500 hover:bg-red-600 rounded px-4 py-2 font-medium text-center" href="{{ route('marathons.index') }}">Экзамен</a>--}}
        </div>
    </div>
@endsection
