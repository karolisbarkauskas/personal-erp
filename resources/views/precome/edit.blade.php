@extends('layouts.app')

@section('content')
    @livewire('precome', [
        'precome' => $precome
    ])

    @foreach($precome->tasks as $task)
        @livewire('precome-task', [
            'task' => $task
        ])
    @endforeach
@endsection
