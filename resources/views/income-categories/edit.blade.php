@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">
        <div class="df-example demo-forms">
            <div class="row">
                <div class="col-md-12 mg-t-10">
                    <div class="card">
                        <div class="card-header pd-b-0 pd-x-20 bd-b-0">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h6 class="mg-b-0"> Income category - {{ $income_category->name }}</h6>
                            </div>
                        </div><!-- card-header -->
                        <div class="card-body pd-20">

                            <form method="post" action="{{ route('income-categories.update', $income_category) }}">
                                @csrf
                                @method('PATCH')
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="name">Category name *</label>
                                        <input type="text" class="form-control" id="name" name="name"
                                               value="{{ old('name', $income_category->name) }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="domain">DOMAIN name( without any https or www) *</label>
                                        <input type="text" class="form-control" id="domain" name="domain"
                                               value="{{ old('domain', $income_category->domain) }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="brand_link">FULL www address *</label>
                                        <input type="text" class="form-control" id="brand_link" name="brand_link"
                                               value="{{ old('brand_link', $income_category->brand_link) }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="logo_link">LOGO link *</label>
                                        <input type="text" class="form-control" id="logo_link" name="logo_link"
                                               value="{{ old('logo_link', $income_category->logo_link) }}">
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mg-t-10">
                    <div class="card">
                        <div class="card-header pd-b-0 pd-x-20 bd-b-0">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h6 class="mg-b-0">Employee costs</h6>
                            </div>
                        </div><!-- card-header -->
                        <div class="card-body pd-20">
                            <form method="post" action="{{ route('incomecost.store', $income_category) }}">
                                @csrf
                                <input type="hidden" name="income_category" value="{{ $income_category->id }}">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="name">Employee</label>
                                        <select name="user_id" id="user_id" class="form-control">
                                            <option value="">-- Select --</option>
                                            @foreach($employees as $employee)
                                                <option value="{{ $employee->id }}"
                                                        @if(old('user_id') == $employee->id) selected="selected" @endif>
                                                    {{ $employee->first_name }} {{ $employee->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="domain">Salary percentage</label>
                                        <input type="number" min="0" max="100" step="1"
                                               class="form-control"
                                               id="percentage"
                                               name="percentage"
                                               value="{{ old('percentage') }}"
                                        >
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="from">From</label>
                                        <input type="date"
                                               class="form-control"
                                               id="from"
                                               name="from"
                                               value="{{ old('from') }}">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </form>
                            <hr>
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th scope="col" style="width: 20%">ID</th>
                                    <th scope="col" style="width: 20%">Employee ID</th>
                                    <th scope="col" style="width: 10%">percentage</th>
                                    <th scope="col" style="width: 10%">Amount</th>
                                    <th scope="col" style="width: 20%">From</th>
                                    <th scope="col" style="width: 20%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($incomeCosts as $incomeCost)
                                    @if ($incomeCost->expense_type == \App\IncomeCost::EMPLOYEE_COST)
                                    <tr>
                                        <td>{{ $incomeCost->id }}</td>
                                        <td>{{ $incomeCost->employee->getFullName() }}</td>
                                        <td>{{ $incomeCost->percentage }}%</td>
                                        <td>{{  \App\Label::formatPrice($incomeCost->employee->cost * ($incomeCost->percentage / 100)) }}</td>
                                        <td>{{ $incomeCost->from }}</td>
                                        <td>
                                            @if (!$incomeCost->to)
                                                <form action="{{ route('incomecost.destroy', $incomeCost->id) }}" class="aligned-right"
                                                      method="post">
                                                    @method('DELETE')
                                                    @csrf

                                                    <button class="btn btn-danger btn-xs" value="1">Delete</button>
                                                </form>
                                            @else
                                                To: {{ $incomeCost->to }}
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 mg-t-10">
                    <div class="card">
                        <div class="card-header pd-b-0 pd-x-20 bd-b-0">
                            <div class="d-sm-flex align-items-center justify-content-between">
                                <h6 class="mg-b-0">Expenses costs</h6>
                            </div>
                        </div><!-- card-header -->
                        <div class="card-body pd-20">
                            <form method="post" action="{{ route('incomecost.store') }}">
                                @csrf
                                <input type="hidden" name="income_category" value="{{ $income_category->id }}">
                                <div class="form-row">
                                    <div class="form-group col-md-4">
                                        <label for="name">Expense category</label>
                                        <select name="category" id="category" class="form-control">
                                            <option value="">-- Select --</option>
                                            @foreach($expenseCategories as $expenseCategory)
                                                <option value="{{ $expenseCategory->id }}"
                                                        @if(old('category') == $expenseCategory->id) selected="selected" @endif>
                                                    {{ $expenseCategory->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="cat_percentage">Expenses porsion</label>
                                        <input type="number" min="0" max="100" step="1"
                                               class="form-control"
                                               id="cat_percentage"
                                               name="cat_percentage"
                                               value="{{ old('cat_percentage') }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="from">From</label>
                                        <input type="date"
                                               class="form-control"
                                               id="from"
                                               name="from"
                                               value="{{ old('from') }}">
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Add</button>
                            </form>
                            <hr>
                            <table class="table table-striped table-hover">
                                <thead>
                                <tr>
                                    <th scope="col" style="width: 20%">ID</th>
                                    <th scope="col" style="width: 20%">Expense category ID</th>
                                    <th scope="col" style="width: 20%">percentage</th>
                                    <th scope="col" style="width: 20%">From</th>
                                    <th scope="col" style="width: 20%">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($incomeCosts as $incomeCost)
                                    @if ($incomeCost->expense_type == \App\IncomeCost::EXPENSES_COST)
                                        <tr>
                                            <td>{{ $incomeCost->id }}</td>
                                            <td>{{ $incomeCost->expenseCategory->name }}</td>
                                            <td>{{ $incomeCost->percentage }}%</td>
                                            <td>{{ $incomeCost->from }}</td>
                                            <td>
                                                @if (!$incomeCost->to)
                                                <form action="{{ route('incomecost.destroy', $incomeCost->id) }}" class="aligned-right"
                                                       method="post">
                                                    @method('DELETE')
                                                    @csrf

                                                    <button class="btn btn-danger btn-xs" value="1">Delete</button>
                                                </form>
                                                @else
                                                   To: {{ $incomeCost->to }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
