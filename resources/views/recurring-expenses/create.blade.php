@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

        <h4 id="section3" class="mg-b-10">
            New expenses category
        </h4>

        <div data-label="Example" class="df-example demo-forms">

            <form method="post" action="{{ route('recurring-expenses.store') }}">
                @csrf
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Expense name *</label>
                        <input type="text" class="form-control" id="name" name="name" autocomplete="off"
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
                        <label for="installment">
                            Payment installment sizes (will mark as dept / loan and will deduce from size field)
                        </label>
                        <input type="text" class="form-control" id="installment" name="installment"
                               value="{{ old('installment') }}" />
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="priority">Priority *</label>
                        <select name="priority" id="priority" class="form-control">
                            <option value="1"
                                    @if(old('priority') == 1) selected="selected" @endif>
                                Essential (Critical for operations)
                            </option>
                            <option value="2"
                                    @if(old('priority') == 2) selected="selected" @endif>
                                Secondary (High priority, but ok if paid late)
                            </option>
                            <option value="3"
                                    @if(old('priority') == 3) selected="selected" @endif>
                                Non-essential (can be late payment)
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input"
                               id="vatable" name="vatable" value="1">
                        <label class="custom-control-label" for="vatable">
                            Vatable?
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Create</button>
            </form>
        </div>
    </div>
@endsection
