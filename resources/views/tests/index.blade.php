@extends('layouts.app')

@section('title', 'Марафон по CISCO')

@section('content')
    <div class="flex flex-col gap-4">
        <page-header href="{{ config('app.url') }}" text="Все тесты"></page-header>

        <ul class="flex flex-col">
            <li class="border-b border-gray-500 text-gray-500 text-xs font-medium">
                <div class="grid grid-cols-[1fr_40px_48px] p-2">
                    <small>Название</small>
                    <small class="text-right">Решено</small>
                    <small class="text-right">Всего</small>
                </div>
            </li>
            @foreach($marathons as $marathon)
                <li class="text-gray-200 text-xs">
                    <a class="grid grid-cols-[1fr_40px_48px] p-2 gap-y-2 hover:bg-slate-800" href="{{ route('marathons.show', $marathon->uuid) }}">
                        <span>Марафон {{ $marathon->created_at->format('d.m.Y') }}</span>
                        <span class="flex gap-0.5 justify-end">
                            <span class="text-green-500">{{ $marathon->success_tasks_count }}</span>
                            <span>/</span>
                            <span class="text-red-500">{{ $marathon->error_tasks_count }}</span>
                        </span>
                        <span class="text-right text-gray-500">{{ $marathon->tasks_count }}</span>
                        <span class="h-1 col-span-full bg-gray-700 relative">
                            <span class="absolute block h-1 bg-blue-500" style="width: {{ (($marathon->success_tasks_count + $marathon->error_tasks_count) / $marathon->tasks_count) * 100 }}%"></span>
                        </span>
                    </a>
                </li>
            @endforeach

            @foreach($tests as $test)
                <li class="text-gray-200 text-xs">
                    <a class="grid grid-cols-[1fr_40px_48px] p-2 gap-y-2 hover:bg-slate-800" href="{{ route('exams.show', $test->uuid) }}">
                        <span>{{ $test->type->label() }} {{ $test->created_at->format('d.m.Y') }}</span>
                        <span class="flex gap-0.5 justify-end">
                            <span class="text-green-500">{{ $test->success_exercises_count }}</span>
                            <span>/</span>
                            <span class="text-red-500">{{ $test->error_exercises_count }}</span>
                        </span>
                        <span class="text-right text-gray-500">{{ $test->exercises_count }}</span>
                        <span class="h-1 col-span-full bg-gray-700 relative">
                            <span class="absolute block h-1 bg-blue-500" style="width: {{ (($test->success_exercises_count + $test->error_exercises_count) / $test->exercises_count) * 100 }}%"></span>
                        </span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
