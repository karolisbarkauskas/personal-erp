@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

        <h4 id="section3" class="mg-b-10">
            Create new invoyer project / sale
        </h4>

        <div data-label="Example" class="df-example demo-forms">

            <form method="post" action="{{ route('projects.store') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Name *</label>
                        <input type="text" class="form-control" id="name" name="name"
                               value="{{ old('name') }}">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="name">Client *</label>
                        <select name="client_id" id="client_id" class="form-control">
                            <option value="">-- Select --</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}"
                                        @if(old('client') == $client->id) selected="selected" @endif>
                                    {{ $client->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>
@endsection
