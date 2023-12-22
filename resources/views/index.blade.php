@extends('layouts.app')

@section('title', 'База баз')

@section('head')
    <meta name="description" content="Это сайт ― база баз, которая позволяет выучить билеты, которые будут на экзамене по тому или иному предмету">
    <meta property="og:title" content="База баз">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ config('app.url') }}">
    <meta property="og:image" content="{{ Vite::asset('resources/images/Promo.jpg') }}">
    <meta property="og:description" content="Это сайт ― база баз, которая позволяет выучить билеты, которые будут на экзамене по тому или иному предмету">
    <meta property="og:locale" content="ru_RU">
    <meta property="og:site_name" content="База баз">
@endsection

@section('content')
    <div class="flex flex-col gap-4">
        <div class="flex justify-center">
            <a class="flex items-center gap-2" href="https://deniszagvozdin.ru/" target="_blank">
                <img src="{{ Vite::asset('resources/images/DenisZagvozdinLogo.svg') }}" alt="Логотип Дениса Загвоздина" width="24" height="24">
                <span class="text-sm font-bold">Denis Zagvozdin</span>
            </a>
        </div>

        <a class="bg-slate-800 group hover:bg-slate-700 rounded px-4 pt-2 pb-4" href="{{ route('tests.index') }}">
            <img class="block mx-auto" src="{{ Vite::asset('resources/images/Education.svg') }}" alt="Фото процесса обучения" width="240" height="240">

            <span class="grid grid-cols-[min-content_min-content_min-content] justify-center gap-10 text-xs px-10">
                <span class="flex flex-col gap-1 text-center">
                    <span class="flex gap-0.5 justify-center">
                        <span class="text-gray-300">{{ $stats['marathon_resolved_tasks_count'] }}</span>
                        <span class="text-gray-500">/</span>
                        <span class="text-gray-500">{{ $stats['marathon_tasks_count'] }}</span>
                    </span>
                    <span class="h-1 bg-gray-700 group-hover:bg-gray-600 relative w-16">
                        <span class="absolute block h-1 bg-blue-500" style="width: {{ $stats['marathon_tasks_count'] === 0 ? 0 : ($stats['marathon_resolved_tasks_count'] / $stats['marathon_tasks_count'] * 100) }}%;"></span>
                    </span>
                    <span class="text-gray-300">Марафон</span>
                </span>
                <span class="flex flex-col gap-1 text-center">
                    <span class="flex gap-0.5 justify-center">
                        <span class="text-gray-300">{{ $stats['exam_resolved_exercises_count'] }}</span>
                        <span class="text-gray-500">/</span>
                        <span class="text-gray-500">{{ $stats['exam_exercises_count'] }}</span>
                    </span>
                    <span class="h-1 bg-gray-700 group-hover:bg-gray-600 relative w-16">
                        <span class="absolute block h-1 bg-blue-500" style="width: {{ $stats['exam_exercises_count'] === 0 ? 0 : ($stats['exam_resolved_exercises_count'] / $stats['exam_exercises_count'] * 100) }}%;"></span>
                    </span>
                    <span class="text-gray-300">Экзамен</span>
                </span>
                <span class="flex flex-col gap-1 text-center">
                    <span class="flex gap-0.5 justify-center">
                        <span class="text-gray-300">{{ $stats['mistake_resolved_exercises_count'] }}</span>
                        <span class="text-gray-500">/</span>
                        <span class="text-gray-500">{{ $stats['mistake_exercises_count'] }}</span>
                    </span>
                    <span class="h-1 bg-gray-700 group-hover:bg-gray-600 relative w-16">
                        <span class="absolute block h-1 bg-blue-500" style="width: {{ $stats['mistake_exercises_count'] === 0 ? 0 : ($stats['mistake_resolved_exercises_count'] / $stats['mistake_exercises_count'] * 100) }}%;"></span>
                    </span>
                    <span class="text-gray-300">Ошибки</span>
                </span>
            </span>
        </a>

        <div class="grid grid-cols-2 gap-4">
{{--            <form action="{{ route('marathons.store') }}" method="POST">--}}
{{--                @csrf--}}

                <a class="block w-full bg-blue-500 hover:bg-blue-600 rounded px-4 py-2 font-medium text-center" href="{{ route('marathons.create') }}">Марафон</a>
{{--            </form>--}}

            <form action="{{ route('tests.store') }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="{{ \App\Enums\TestType::Exam->value }}">

                <button class="block w-full bg-red-500 hover:bg-red-600 rounded px-4 py-2 font-medium text-center" type="submit">Экзамен</button>
            </form>

            <a class="col-span-full bg-green-500 hover:bg-green-600 rounded px-4 py-2 font-medium text-sm text-center" href="{{ route('base.index') }}">База</a>

{{--            <a class="col-span-full bg-gray-500 hover:bg-gray-600 rounded px-4 py-2 font-medium text-sm text-center" href="{{ route('marathons.index') }}">Мои марафоны</a>--}}

            <a class="col-span-full bg-gray-500 hover:bg-gray-600 rounded px-4 py-2 font-medium text-sm text-center" href="https://github.com/Den4ik117/cisco" target="_blank">Исходный код</a>

            <a
                class="col-span-full bg-blue-500 hover:bg-blue-600 rounded px-4 py-2 font-medium text-sm text-center"
                href="{{ route('courses.choose') }}"
            >Выбрать другой курс</a>
            {{--        <a class="bg-red-500 hover:bg-red-600 rounded px-4 py-2 font-medium text-center" href="{{ route('marathons.index') }}">Экзамен</a>--}}
        </div>

        <p class="text-gray-400 text-xs text-center">
            Курс <span class="font-medium">«{{ $token->course?->name ?? 'Курс не выбран' }}»</span>
        </p>
    </div>
@endsection
