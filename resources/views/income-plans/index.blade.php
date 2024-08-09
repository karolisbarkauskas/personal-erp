@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-lg-12 mg-t-10">
            <div class="card mg-b-10">
                <div class="card-header pd-t-20 d-sm-flex align-items-start justify-content-between bd-b-0 pd-b-0">
                    <div>
                        <h6 class="mg-b-5">Income plans</h6>
                    </div>
                </div>
            </div>

            <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

                <h4 id="section3" class="mg-b-10">
                    Add new planned income
                </h4>

                <div data-label="Example" class="df-example demo-forms">
                    <form action="{{ route('planned-incomes.store') }}" method="post">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-3">
                                <label for="name">Client *</label>
                                <select name="client_id" id="client_id" class="form-control">
                                    <option value="">-- Select --</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}"
                                                @if(old('client_id') == $client->id) selected="selected" @endif>
                                            {{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group col-md-3">
                                <label for="income_period">Income period (month)</label>

                                <input type="date" class="form-control" id="income_period" name="income_period"
                                       value="{{ old('income_period') }}">
                            </div>

                            <div class="form-group col-md-3">
                                <label for="price">Income size</label>

                                <input type="text" class="form-control" id="price" name="price"
                                       value="{{ old('price') }}">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                        <hr>
                    </form>
                </div>
            </div>


            <div class="table-responsive">
                <table class="table table-dashboard mg-b-0 table-hover">
                    <thead>
                    <tr>
                        <th></th>
                        @for ($i = -3; $i <= 3; $i++)
                            <th class="text-center @if($i < 0) bg-gray-1 @endif  @if($i == 0)  bg-gray-2 @endif">
                                {{ \Carbon\CarbonImmutable::now()->addMonths($i)->format('Y-m') }}
                            </th>
                        @endfor
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($categories as $category)
                        <tr>
                            <td class="text-right">
                                {{ $category->name }}
                            </td>
                            @for ($i = -3; $i <= 3; $i++)
                                <td class="text-center @if($i < 0) bg-gray-1 @endif @if($i == 0) bg-gray-2 @endif">

                                    <table class="table table-dashboard mg-b-0 table-hover">
                                        @foreach($category->getPlannedIncome(\Carbon\CarbonImmutable::now()->addMonths($i)->format('Y-m')) as $income)
                                            <tr>
                                                <td class="wd-100-f text-right">
                                                    {{ $income->client->name }}
                                                </td>
                                                <td class="wd-100-f text-center">
                                                    {{ \App\Label::formatPrice($income->price) }}
                                                </td>
                                                <td class="wd-10">
                                                    <form method="post" action="{{ route('planned-incomes.destroy', $income) }}" >
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="badge badge-danger">X</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td class="wd-100-f text-right">
                                                <strong>Total</strong>
                                            </td>
                                            <td class="wd-100-f text-center text-success">
                                                {{ \App\Label::formatPrice($category->getPlannedIncome(\Carbon\CarbonImmutable::now()->addMonths($i)->format('Y-m'))->sum('price')) }}
                                            </td>
                                        </tr>
                                    </table>

                                </td>
                            @endfor
                        </tr>
                    @endforeach

                    </tbody>
                    <tfoot>
                    <tr>
                        <th></th>
                        @for ($i = -3; $i <= 3; $i++)
                            <th class="text-center @if($i < 0) bg-gray-1 @endif  @if($i == 0)  bg-gray-2 @endif">
                                {{ \Carbon\CarbonImmutable::now()->addMonths($i)->format('Y-m') }}
                            </th>
                        @endfor
                    </tr>
                    </tfoot>
                </table>
            </div>

        </div>

    </div>
@endsection
