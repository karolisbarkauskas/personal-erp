@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

        <h4 id="section3" class="mg-b-10">
            New agreements group
        </h4>

        <div data-label="Example" class="df-example demo-forms">

            <form method="post" action="{{ route('save-agreement-group') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="name">Name*</label>
                        <input type="text" class="form-control" id="name" name="name"
                               value="{{ old('name') }}">
                    </div>
                    <div class="col-md-9">
                        <div class="row">
                            <div class="form-group col-md-3">
                                <label for="sale_id">Sale *</label>
                                <select name="sale_id" id="sale_id" class="form-control">
                                    <option value="">--- Select ---</option>
                                    @foreach($sales as $sale)
                                        <option value="{{ $sale->id }}"
                                                @if(old('sale_id', $selectedSale->id) == $sale->id) selected="selected" @endif>
                                            {{ $sale->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-3">
                                <label for="start">Start</label>
                                <input type="date" class="form-control"
                                       id="start"
                                       name="start"
                                       value="{{ old('start') }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="deadline">Deadline</label>
                                <input type="date" class="form-control" id="deadline" name="deadline" value="{{ old('deadline') }}">
                            </div>
                            <div class="form-group col-md-3">
                                <label for="budget">Budget</label>
                                <input type="text" class="form-control" id="budget" name="budget"
                                       value="{{ old('budget') }}">
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="description">Description</label>
                        <textarea name="description" id="description" cols="30" class="form-control"
                                  rows="10">{{ old('description') }}</textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>
@endsection

@push('javascript')
    <script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>

    <script>
        CKEDITOR.replace('description');
    </script>
@endpush
