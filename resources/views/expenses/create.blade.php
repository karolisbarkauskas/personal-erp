@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

        <h4 id="section3" class="mg-b-10">
            New expense
        </h4>

        <div data-label="Example" class="df-example demo-forms">

            <form method="post" action="{{ route('expenses.store') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Expense name *</label>
                        <input type="text" class="form-control" id="name" name="name"
                               value="{{ old('name') }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="size">Size (Without VAT) *</label>
                        <input type="text" class="form-control" id="size" name="size"
                               value="{{ old('size') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="category">Expenses category *</label>
                        <select name="category" id="category" class="form-control">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        @if(old('category') == $category->id) selected="selected" @endif>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="vat_size">VAT size *</label>
                        <input type="text" class="form-control" id="vat_size" name="vat_size"
                               value="{{ old('vat_size', env('VAT_SIZE')) }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="expense_date">Payment date *</label>
                        <input type="date" class="form-control" id="expense_date" name="expense_date"
                               value="{{ old('expense_date') }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="description">Description *</label>
                        <textarea name="description" id="description" class="form-control" cols="30" rows="10">{{ old('description') }}</textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>
@endsection
