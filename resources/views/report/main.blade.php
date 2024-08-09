@extends('layouts.app')
@section('title', 'Efficiency report')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <form method="get" action="{{ route('report.main') }}">
                @csrf
                @method('GET')
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="yearFrom">Year from</label>
                        <input type="number" class="form-control" id="yearFrom" name="yearFrom" autocomplete="off"
                               value="{{ request()->get('yearFrom', now()->year) }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="weekFrom">Week from</label>
                        <input type="number" class="form-control" id="weekFrom" name="weekFrom" autocomplete="off"
                               value="{{ request()->get('weekFrom', 10) }}">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group col-md-3">
                        <label for="yearTo">Year to</label>
                        <input type="number" class="form-control" id="yearTo" name="yearTo" autocomplete="off"
                               value="{{ request()->get('yearTo', now()->year) }}">
                    </div>
                    <div class="form-group col-md-3">
                        <label for="weekTo">Week to</label>
                        <input type="number" class="form-control" id="weekTo" name="weekTo" autocomplete="off"
                               value="{{ request()->get('weekTo', now()->weekOfYear) }}">
                    </div>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input"
                               id="short" name="short" value="1" @if(request()->has('short')) checked @endif >
                        <label class="custom-control-label" for="short">
                            Hide Y axis labels?
                        </label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input"
                               id="minimal" name="minimal" value="1" @if(request()->has('minimal')) checked @endif >
                        <label class="custom-control-label" for="minimal">
                            Show minimal values
                        </label>
                    </div>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input"
                               id="optimal" name="optimal" value="1" @if(request()->has('optimal')) checked @endif >
                        <label class="custom-control-label" for="optimal">
                            Show optimal values
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Create</button>
            </form>

            <div style="height: 640px;" id="chart"></div>

            <div class="text-center">
                Average sold hours per week: {{ $report->averages['soldHours'] }}
            </div>

            <div class="text-center">
                Average to be sold hours per week: {{ $report->averages['totalMinimal'] }}
            </div>
            <div style="height: 350px;margin-top: 50px" id="gauge"></div>

            <div class="text-center">
                Min: {{ $report->normalizedValues['totalMinimal'] }}
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" href="{{ asset('lib/devextreme/dist/css/dx.common.css') }}">
    <link rel="stylesheet" href="{{ asset('lib/devextreme/dist/css/dx.light.css') }}">
@endpush

@push('javascript')
    <script src="{{ asset('lib/devextreme/dist/js/dx.all.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {

            const dataSource = [
                @foreach($report->report as $row)
                @if($row['minimal'] > 0)
                {
                    week: "{{ $row['week'] }}",
                    minimal: {{ $row['minimal'] }},
                    current: {{ $row['current'] }},
                    @if(request()->has('optimal'))
                    optimal: {{ $row['optimal'] }},
                    @endif
                    @if(request()->has('minimal'))
                    survival: {{ $row['survival'] }},
                    @endif
                },
                @else
                {
                    week: "{{ $row['week'] }}",
                },
                @endif
                @endforeach
            ];

            $('#chart').dxChart({
                dataSource,
                commonSeriesSettings: {
                    argumentField: 'week',
                    type: 'line',
                    point: {
                        hoverMode: 'allArgumentPoints',
                    },
                },
                argumentAxis: {
                    valueMarginsEnabled: false,
                    discreteAxisDivisionMode: 'crossLabels',
                    grid: {
                        visible: true,
                    },
                },
                @if(request('short', false))
                    valueAxis: {
                        label: {
                            visible: false,
                        },
                    },
                @endif
                crosshair: {
                    enabled: true,
                    color: '#949494',
                    width: 3,
                    dashStyle: 'dot',
                    label: {
                        visible: true,
                        backgroundColor: '#949494',
                        font: {
                            color: '#fff',
                            size: 12,
                        },
                    },
                },
                series: [
                    {valueField: 'minimal', name: 'Minimal', color: '#ff8000'},
                    {valueField: 'current', name: 'Current', color: '#2E3192'},
                    @if(request()->has('optimal'))
                     {valueField: 'optimal', name: 'Optimal', color: '#00ff33'},
                    @endif
                    @if(request()->has('minimal'))
                     {valueField: 'survival', name: 'Survival', color: '#ff0000'},
                    @endif
                ],
                legend: {
                    verticalAlignment: 'bottom',
                    horizontalAlignment: 'center',
                    itemTextPosition: 'bottom',
                    equalColumnWidth: true,
                },
                title: {
                    text: 'invoyer efficiency',
                    subtitle: {
                        text: '{{ $report->yearFrom }} W{{ $report->weekFrom }} -> {{ $report->yearTo }} W{{ $report->weekTo }}',
                    },
                },
                export: {
                    enabled: true,
                },
                tooltip: {
                    enabled: true,
                },
            });

            $('#gauge').dxCircularGauge({
                scale: {
                    startValue: 0,
                    endValue: 100,
                    tickInterval: 1,
                    label: {
                        useRangeColors: true,
                    },
                },
                rangeContainer: {
                    palette: 'pastel',
                    ranges: [
                        { startValue: 0, endValue: {{ $report->normalizedValues['totalMinimal'] }}, color: '#CE2029' },
                        { startValue: {{ $report->normalizedValues['totalMinimal'] }}, endValue: {{ $report->normalizedValues['totalOptimal'] }}, color: '#228B22' },
                    ],
                },
                title: {
                    text: 'Overall score: {{ $report->normalizedValues['totalCurrent'] }} / 100',
                    font: { size: 25 },
                },
                export: {
                    enabled: true,
                },
                value: {{ $report->normalizedValues['totalCurrent'] }},
            });
        });
    </script>
@endpush

