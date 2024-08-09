@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card card-body">
                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                    Enter task ID
                </h6>
                <div class="d-flex d-lg-block d-xl-flex align-items-end">
                    <form action="{{ route('tasks.open') }}" method="get">
                        <input type="text" class="form-control" id="search" name="search" value="{{ $request->get('search') }}"/>
                        <button class="btn btn-warning">
                            Filter
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-sm-12">
            <div class="products-container">
                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                    This year (all weeks)
                </h6>
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th scope="col" style="width: 20%">ID</th>
                        <th scope="col" style="width: 20%">Task name</th>
                        <th scope="col" style="width: 20%">Task assigned to lists</th>
                        <th scope="col" style="width: 20%">Task worth (Total: {{ \App\Label::formatPrice($totalWorth) }})</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($tasks as $task)
                        <tr>
                            <td>
                                <a href="https://invoyer.youtrack.cloud/issue/{{ $task->first()->task->code }}"
                                   target="_blank">
                                    {{ $task->first()->task->code }}
                                </a>
                            </td>
                            <td>{{ $task->first()->name }}</td>
                            <td>
                                @foreach($task as $weekTask)
                                    <a href="{{ route('week.edit', $weekTask->task_list_id) }}"
                                       class="badge badge-warning" target="_blank">
                                        {{ $weekTask->list->year }}/{{ $weekTask->list->week }}
                                    </a>
                                @endforeach
                            </td>
                            <td>
                                {{ \App\Label::formatPrice($task->first()->task->getPrice()) }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
