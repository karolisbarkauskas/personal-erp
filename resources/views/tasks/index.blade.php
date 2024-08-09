@extends('layouts.app')
@section('title', 'Tasks list')

@section('content')
    <div class="row">
        @if($currentWeek)
            <div class="col-sm-3">
                <div class="card card-body">
                    <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                        Sold hours this week
                    </h6>
                    <div class="d-flex d-lg-block d-xl-flex align-items-end">
                        <h3 class="tx-normal {{ $currentWeek->getBookedClass() }} tx-rubik mg-b-0 mg-r-5 lh-1">
                            {{ $currentWeek->client_sold_hours }}/{{ $currentWeek->client_sellable_hours }}<small>/{{ $currentWeek->break_even_hours }}</small>
                        </h3>
                    </div>
                </div>
            </div>
        @endif
        <div class="col-sm-3">
            <div class="card card-body">
                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                    Sold hours for next week
                </h6>
                <div class="d-flex d-lg-block d-xl-flex align-items-end">
                    <h3 class="tx-normal {{ $nextWeek->getBookedClass() }} tx-rubik mg-b-0 mg-r-5 lh-1">
                        {{ $nextWeek->client_sold_hours }}/{{ $nextWeek->client_sellable_hours }}<small>/{{ $nextWeek->break_even_hours }}</small>
                    </h3>
                </div>
            </div>
        </div>
        <div class="col-sm-2">
            <div class="card card-body">
                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                    Number reminders
                </h6>
                <div class="d-flex d-lg-block d-xl-flex align-items-end">
                    <div class="tx-11 tx-color-03 mg-b-0">
                        <div class="tx-medium tx-dark">
                            Salaries <strong>{{ \App\Label::formatPrice($salaries) }}</strong> <br>
                            Other expenses <strong>{{ \App\Label::formatPrice($expenses) }}</strong> <br>
                            <hr>
                            Total: <strong><em>{{ \App\Label::formatPrice($salaries + $expenses) }}</em></strong> <br>
                            <span class="tx-medium tx-success">
                                Average markup from employee {{ $averageMarkup }}%
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="card card-body">
                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                    Sales hours targets (with {{ \App\Label::formatPrice($hourlyRate) }} rate)
                </h6>
                <div class="d-flex d-lg-block d-xl-flex align-items-end">
                    <div class="tx-13 tx-color-03 mg-b-0">
                        <div class="tx-medium tx-dark">
                            Minimal (0% profits)
                            <strong class="text-danger">
                                {{ round($minimalHoursToSellNoProfit) }}
                                / {{ ceil($minimalHoursToSellNoProfit / \App\Settings::WEEKS_PER_MONTH_AVG) }}
                                = {{ \App\Label::formatPrice($minimalHoursToSellNoProfit*$hourlyRate) }}
                            </strong>
                            <br>
                            Targeted ({{ $markup }}% profits)
                            <strong class="text-info">
                                {{ round($optimalHoursToSell) }}
                                / {{ ceil($optimalHoursToSell / \App\Settings::WEEKS_PER_MONTH_AVG) }}
                                = {{ \App\Label::formatPrice($optimalHoursToSell*$hourlyRate) }}
                            </strong> <br>
                            SUPER ({{ $averageMarkup }}% profits):
                            <strong class="text-success">
                                {{ round($healthyHoursToSell) }}
                                / {{ ceil($healthyHoursToSell / \App\Settings::WEEKS_PER_MONTH_AVG) }}
                                = {{ \App\Label::formatPrice($healthyHoursToSell*$hourlyRate) }}
                            </strong> <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-sm-4">
            <livewire:rates-calculator>
                <div class="products-container mt-4">
                    <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                        Current active projects / longer tasks
                        <a href="{{ route('projects.create') }}" class="float-right">
                            Create new
                        </a>
                    </h6>
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 20%">Project name</th>
                                <th scope="col" style="width: 20%">Hours sold / used total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                                <tr>
                                    <td>
                                        <a href="{{ route('projects.edit', $project) }}">{{ $project->name }}</a>
                                    </td>
                                    <td></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
        </div>

        <div class="col-sm-8">
            <div class="products-container">
                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                    Current period (-1 week / current week / +1 week)
                </h6>
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th scope="col" style="width: 20%">Year/week</th>
                        <th scope="col" style="width: 20%">Clients hours <br> (sold/need to sell)</th>
                        <th scope="col" style="width: 20%">% time sold</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($currentPeriod as $period)
                        <tr style="@if($period->isCurrentWeek()) border: 2px solid red; @else @if(!$period->is_finished) background: rgb(255 0 0 / 8%); @endif @if($period->is_finished) background: rgba(0,255,0,0.17)@endif  @endif">
                            <td>
                                <a href="{{ route('week.edit', $period) }}"
                                   class="text-dark font-weight-bold">
                                    {{ $period->year }}/W{{ $period->week }} <small><em>{{ $period->getStartWeek() }} -> {{ $period->getEndWeek() }}</em></small>
                                </a>
                            </td>
                            <td class="{{ $period->getBookedClass() }}">
                                <a href="{{ route('week.edit', $period) }}" target="_blank" class="{{ $period->getBookedClass() }}">
                                    {{ $period->client_sold_hours }} / {{ $period->client_sellable_hours }}
                                </a>
                            </td>
                            <td>
                                {{ round(($period->client_sold_hours * 100)/($period->client_sellable_hours == 0 ? 1 : $period->client_sellable_hours)) }}%
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

            <hr/>

            <div class="products-container">
                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                    This year (all weeks)
                </h6>
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th scope="col" style="width: 20%">Year/week</th>
                        <th scope="col" style="width: 20%">Clients hours <br> (sold/need to sell)</th>
                        <th scope="col" style="width: 20%">% time sold</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($thisYear as $period)
                        <tr @if($period->isCurrentWeek()) style="border: 2px solid red" @endif @if(!$period->is_finished && $period->client_sold_hours > 0) style="background: rgb(255 0 0 / 8%)" @endif @if($period->is_finished) style="background: rgba(0,255,0,0.17)" @endif>
                            <td>
                                <a href="{{ route('week.edit', $period) }}"
                                   class="text-dark font-weight-bold">
                                    {{ $period->year }}/W{{ $period->week }} <small>{{ $period->getStartWeek() }} -> {{ $period->getEndWeek() }}</small>
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('week.edit', $period) }}" class="{{ $period->getBookedClass() }}" target="_blank">
                                    {{ $period->client_sold_hours }} / {{ $period->client_sellable_hours }}
                                </a>
                            </td>
                            <td>
                                {{ round(($period->client_sold_hours * 100)/($period->client_sellable_hours == 0 ? 1 : $period->client_sellable_hours)) }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
