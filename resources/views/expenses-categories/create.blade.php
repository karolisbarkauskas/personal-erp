@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

        <h4 id="section3" class="mg-b-10">
            New expenses category
        </h4>

        <div data-label="Example" class="df-example demo-forms">

            <form method="post" action="{{ route('expenses-categories.store') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Category name *</label>
                        <input type="text" class="form-control" id="name" name="name"
                               value="{{ old('name') }}">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="parent_id">PARENT category </label>
                        <select name="parent_id" id="parent_id" class="form-control">
                            <option value="">-- Select --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        @if(old('parent_id') == $category->id) selected="selected" @endif>
                                    {{ $category->name }}
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
