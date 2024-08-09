@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

        <h4 id="section3" class="mg-b-10">
            Map <strong>{{ $client->name }}</strong> collabs with UT and inner collab
        </h4>

        <div data-label="Example" class="df-example demo-forms">

            <form method="post" action="{{ route('save-map', $client) }}">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">ONESOFT collab</label> <br>
                        <select name="onesoft_collab" id="onesoft_collab" style="width: 100%">
                            <option value="">-- SELECT --</option>
                            @foreach($onesoftProjects as $project)
                                <option value="{{ $project->id }}"
                                        @if(old('onesoft_collab') == $project->id) selected @endif>{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">PrestaPro collab</label> <br>
                        <select name="prestapro_collab" id="prestapro_collab" style="width: 100%">
                            <option value="">-- SELECT --</option>
                            @foreach($prestaProCollabs as $project)
                                <option value="{{ $project->id }}"
                                        @if(old('prestapro_collab') == $project->id) selected @endif>{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Inner active collab</label> <br>
                        <select name="inner_collab" id="inner_collab" style="width: 100%">
                            <option value="">-- SELECT --</option>
                            @foreach($innerCollabProjects as $project)
                                <option value="{{ $project->id }}"
                                        @if(old('inner_collab') == $project->id) selected @endif>{{ $project->name }}</option>
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
                    <th class="text-center">ONESOFT collab</th>
                    <th class="text-center">PrestaPro collab</th>
                    <th class="text-center">Inner collab</th>
                    <th class="text-center">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($client->collabs as $collab)
                    <tr>
                        <td class="tx-medium text-center">{{ optional($collab->onesoftCollab)->name }}</td>
                        <td class="tx-medium text-center">{{ optional($collab->prestaProCollab)->name }}</td>
                        <td class="tx-medium text-center">{{ optional($collab->innerCollab)->name }}</td>
                        <td class="tx-medium text-center">
                            <a href="{{ route('map-lists', $collab->id) }}">Map tasks lists</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
