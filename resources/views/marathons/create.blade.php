@extends('layouts.app')

@section('title', 'Марафон по курсу')

@section('content')
    <div class="flex flex-col gap-4">
        <page-header href="{{ route('index') }}" text="Марафон по курсу"></page-header>

        <div class="px-4 py-2" href="{{ route('tests.index') }}">
            <img class="block mx-auto" src="{{ Vite::asset('resources/images/Stack.svg') }}" alt="Фото марафона" width="240" height="240">
        </div>

        <h2 class="text-xl font-medium text-center">Все {{ trans_choice(':value вопрос|:value вопроса|:value вопросов', $tasks_count, ['value' => $tasks_count]) }}</h2>

        <p class="text-sm text-center">Отличное испытание перед<br>настоящим экзаменом!</p>

        <form class="mt-10 px-4" action="{{ route('tests.store') }}" method="POST">
            @csrf
            <input type="hidden" name="type" value="{{ \App\Enums\TestType::Marathon->value }}">

            <button class="block w-full bg-blue-500 hover:bg-blue-600 rounded px-4 py-2 font-medium text-center" type="submit">Начать марафон</button>
        </form>

        <form class="px-4" action="{{ route('tests.store') }}" method="POST">
            @csrf
            <input type="hidden" name="shuffle" value="true">
            <input type="hidden" name="type" value="{{ \App\Enums\TestType::Marathon->value }}">

            <button class="block w-full bg-gray-500 hover:bg-gray-600 rounded px-4 py-2 font-medium text-center text-xs" type="submit">Начать марафон с перемешанными заданиями</button>
        </form>

        <p class="text-gray-400 text-sm text-center">Вы всегда можете остановиться и<br>продолжить позже</p>
    </div>
@endsection
