@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

        <h4 id="section3" class="mg-b-10">
            Project <em>{{ $project->name }}</em>
        </h4>

        <div data-label="Example" class="df-example demo-forms">

            <form method="post" action="{{ route('projects.update', $project) }}">
                @csrf
                @method('PATCH')
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Name *</label>
                        <input type="text" class="form-control" id="name" name="name"
                               value="{{ old('name', $project->name) }}">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="name">Client *</label>
                        <select name="client_id" id="client_id" class="form-control">
                            <option value="">-- Select --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}"
                                        @if(old('client_id', $project->client_id) == $client->id) selected="selected" @endif>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="name">Status *</label>
                        <select name="status" id="status" class="form-control">
                            <option value="">-- Select --</option>
                            <option value="{{ \App\Project::DRAFT }}"
                                    @if(old('status', $project->status) == \App\Project::DRAFT) selected="selected" @endif>
                                DRAFT
                            </option>
                            <option value="{{ \App\Project::CONFIRMED }}"
                                    @if(old('status', $project->status) == \App\Project::CONFIRMED) selected="selected" @endif>
                                CONFIRMED
                            </option>
                            <option value="{{ \App\Project::CLOSED }}"
                                    @if(old('status', $project->status) == \App\Project::CLOSED) selected="selected" @endif>
                                CLOSED
                            </option>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="name">Description *</label>
                        <textarea name="description" class="form-control"
                                  cols="30" rows="10">{{ $project->description }}</textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">UPDATE</button>
            </form>

            <div class="my-2">
                <livewire:project-resources :project="$project">
                <livewire:project-tasks-assign :project="$project">
            </div>

        </div>
    </div>
@endsection
