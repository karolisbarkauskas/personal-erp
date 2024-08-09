@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

        <h3 id="section3" class="mg-b-10">
            {{ $client->name }} <br>
            <small>
                Map tasks lists with inner collab tasks lists. <br>
                You will be notified in UT when new task is created in that task-list <strong>if you lead</strong> that
                project.
            </small>

            <form action="{{ route('destroy-map', $map) }}" class="aligned-right"
                  method="post">
                @method('DELETE')
                @csrf
                <button class="btn btn-danger btn-xs" value="1">Delete</button>
            </form>
        </h3>

        <div data-label="Example" class="df-example demo-forms">
            <form method="post" action="{{ route('save-map-lists', $map) }}">
                @csrf
                @if($oneSoftTasksLists)
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">ONESOFT collab task-lists</label> <br>
                            <select name="onesoft_task_list[]" multiple id="onesoft_task_list" style="width: 100%">
                                <option value="">-- SELECT --</option>
                                @foreach($oneSoftTasksLists as $list)
                                    <option value="{{ $list->id }}"
                                            @if(old('onesoft_task_list') == $list->id) selected @endif>{{ $list->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
                @if($prestaProTaskLists)
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name">PrestaPro collab task-lists</label> <br>
                            <select name="prestapro_collab[]" multiple id="prestapro_collab" style="width: 100%">
                                <option value="">-- SELECT --</option>
                                @foreach($prestaProTaskLists as $list)
                                    <option value="{{ $list->id }}"
                                            @if(old('prestapro_collab') == $list->id) selected @endif>{{ $list->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Inner active collab</label> <br>
                        <select name="inner_collab" id="inner_collab" style="width: 100%">
                            <option value="">-- SELECT --</option>
                            @foreach($innerCollabTaskLists as $list)
                                <option value="{{ $list->id }}"
                                        @if(old('inner_collab') == $list->id) selected @endif>{{ $list->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Map</button>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-dashboard mg-b-0 mg-t-20">
                <thead>
                <tr>
                    <th class="text-center">ONESOFT task-list</th>
                    <th class="text-center">PrestaPro task-list</th>
                    <th class="text-center">Inner task-list</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($map->taskListsMap as $mapped)
                    <tr>
                        <td class="tx-medium text-center">{{ optional($mapped->onesoftList)->name }}</td>
                        <td class="tx-medium text-center">{{ optional($mapped->prestaProList)->name }}</td>
                        <td class="tx-medium text-center">{{ optional($mapped->innerList)->name }}</td>
                        <td class="tx-medium text-center">
                            <form action="{{ route('destroy-map-lists', $mapped->id) }}" class="aligned-right"
                                  method="post">
                                @method('DELETE')
                                @csrf
                                <button class="btn btn-danger btn-xs" value="1">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
