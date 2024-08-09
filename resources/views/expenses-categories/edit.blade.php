@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

        <h4 id="section3" class="mg-b-10">
            Expenses category - {{ $expenses_category->name }}
        </h4>

        <div data-label="Example" class="df-example demo-forms">

            <form method="post" action="{{ route('expenses-categories.update', $expenses_category) }}">
                @csrf
                @method('PATCH')
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Category name *</label>
                        <input type="text" class="form-control" id="name" name="name"
                               value="{{ old('name', $expenses_category->name) }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="parent_id">PARENT category </label>
                        <select name="parent_id" id="parent_id" class="form-control">
                            <option value="">-- Select --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        @if(old('parent_id', $expenses_category->parent_id) == $category->id) selected="selected" @endif>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="iban">Expense IBAN</label>
                        <input type="text" class="form-control" id="iban" name="iban"
                               value="{{ old('iban') }}"
                               placeholder="LT00 0000 0000 0000 0000"
                        >
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th scope="col" style="width: 20%">ID</th>
                        <th scope="col" style="width: 80%">IBAN</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($bankAccounts as $bankAccount)
                    <tr>
                        <td>{{ $bankAccount->id }}</td>
                        <td>{{ $bankAccount->iban }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
