@extends('layouts.app')

@section('title', 'Выберите курс')

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

{{--        <page-header href="{{ route('index') }}" text="Перед началом работы выберите курс"></page-header>--}}

        <div class="px-4 py-2" href="{{ route('tests.index') }}">
            <img class="block mx-auto" src="{{ Vite::asset('resources/images/Choose.svg') }}" alt="Фото марафона" width="220" height="220">
        </div>

{{--        <h2 class="text-xl font-medium text-center">Все {{ trans_choice(':value вопрос|:value вопроса|:value вопросов', $tasks_count, ['value' => $tasks_count]) }}</h2>--}}

        <p class="text-sm text-center">Перед началом работы выберите курс</p>

        <form class="px-4" action="{{ route('courses.choose') }}" method="POST">
            @csrf

            <ul class="flex flex-col rounded overflow-hidden bg-slate-800">
                @foreach($courses as $course)
                    <li>
                        <button
                            class="w-full grid grid-cols-[min-content_1fr_min-content] items-center gap-2 p-2 hover:bg-slate-700"
                            name="course_id"
                            value="{{ $course->id }}"
                            type="submit"
                        >
                            <span class="bg-orange-500 rounded-md flex items-center justify-center w-8 h-8">
                                <i class="bi bi-plus-circle-fill text-xl"></i>
                            </span>
                            <span class="text-xs text-left">{{ $course->name }}</span>
                            <i class="bi bi-chevron-right text-xs"></i>
                        </button>
                    </li>

                    @if(!$loop->last)
                        <li>
                            <span class="block ml-auto h-px w-[calc(100%-48px)] bg-slate-700"></span>
                        </li>
                    @endif
                @endforeach
            </ul>
        </form>

        <p class="text-gray-400 text-sm text-center">Вы всегда можете поменять курс<br>на этой странице</p>
    </div>
@endsection
