@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

        <h4 id="section3" class="mg-b-10">
            Expense - {{ $expense->name }}
        </h4>

        <div data-label="Example" class="df-example demo-forms">

            <form method="post" action="{{ route('expenses.update', $expense) }}" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="name">Expense name *</label>
                        <input type="text" class="form-control" id="name" name="name"
                               value="{{ old('name', $expense->name) }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="size">Size (Without VAT) *</label>
                        <input type="text" class="form-control" id="size" name="size"
                               value="{{ old('size', $expense->size) }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="category">Expenses category *</label>
                        <select name="category" id="category" class="form-control">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        @if(old('category', $expense->category) == $category->id) selected="selected" @endif>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="vat_size">VAT size *</label>
                        <input type="text" class="form-control" id="vat_size" name="vat_size"
                               value="{{ old('vat_size', $expense->vat_size) }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="expense_date">Payment date *</label>
                        <input type="date" class="form-control" id="expense_date" name="expense_date"
                               value="{{ old('expense_date', $expense->expense_date) }}">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-12">
                        <label for="heading">INVOICE FILE</label> <br>
                        <input type="file" autocomplete="off" name="file"><br>

                        @if($expense->getFirstMediaUrl('file'))
                            <a href="{{ $expense->getFirstMediaUrl('file') }}" class="btn btn-primary mt-3" target="_blank">
                                DOWNLOAD FILE
                            </a>
                        @endif
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="description">Description *</label>
                        <textarea name="description" id="description" class="form-control" cols="30" rows="10">{{ $expense->description }}</textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    Update
                </button>
            </form>
        </div>
        <div class="row mg-t-10">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header pd-b-0 pd-x-20 bd-b-0">
                        <div class="d-sm-flex align-items-center justify-content-between">
                            <h6 class="mg-b-0">Recent Activities</h6>
                        </div>
                    </div><!-- card-header -->
                    <div class="card-body pd-20">
                        <ul class="activity tx-13">
                            @foreach($activity as $act)
                                <li class="activity-item">
                                    <div class="activity-icon bg-primary-light tx-primary">
                                        <i data-feather="clock"></i>
                                    </div>
                                    <div class="activity-body">
                                        <p class="mg-b-2">
                                            <strong>{{ $act->causer->name }}</strong> {{ $act->description }}
                                        </p>
                                        @foreach($act->properties as $key => $prop)
                                            <a href="" class="link-02">
                                                {{ $key }} - <strong>{{ $prop }}</strong> <br>
                                            </a>
                                        @endforeach
                                        <small class="tx-color-03">{{ $act->created_at->diffForHumans() }}</small>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
