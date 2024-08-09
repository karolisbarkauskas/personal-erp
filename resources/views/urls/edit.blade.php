@extends('layouts.app')

@section('content')
    <div class="container pd-x-0 pd-lg-x-10 pd-xl-x-0">

        <h4 id="section3" class="mg-b-10">
            Url - {{ $url->url }}
        </h4>

        <div data-label="Example" class="df-example demo-forms">

{{--            <form method="post" action="{{ route('url.update', $url) }}">--}}
{{--                @csrf--}}
{{--                @method('PATCH')--}}
{{--            </form>--}}
        </div>

        <div class="row">
            <div class="col-md-12">
                <input type="hidden" id="urlId" value="{{ $url->id }}">
                <div style="height: 400px;" id="income-expenses"></div>
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
            let urlId = $('#urlId').val()
            function chart() {
                axios({
                    url: '/report/url/' + urlId,
                    method: 'get',
                    timeout: 30000,
                    headers: {
                        'Content-Type': 'application/json',
                    }
                })
                    .then(res => {
                        displayChart(res.data)
                    })
                    .catch(
                        err => console.error(err)
                    );

                function displayChart(dataSource) {
                    $("#income-expenses").dxChart({
                        palette: "SoftBlue",
                        dataSource: dataSource.data,
                        commonSeriesSettings:{
                            argumentField: "date",
                            type: "spline"
                        },
                        argumentAxis: {
                            label: {
                                overlappingBehavior: "stagger"
                            }
                        },
                        margin: {
                            bottom: 20
                        },
                        valueAxis: [{
                            grid: {
                                visible: true
                            }
                        }, {
                            name: "speed",
                            position: "right",
                            grid: {
                                visible: true
                            }
                        }],
                        series: dataSource.series,
                        tooltip: {
                            enabled: true,
                            customizeTooltip: function (arg) {
                                return {
                                    text: arg.argument + " (" + arg.value + ") "
                                };
                            }
                        },
                        legend: {
                            verticalAlignment: "bottom",
                            horizontalAlignment: "center"
                        },
                        "export": {
                            enabled: true
                        },

                        title: "Booked time (Monthly)"
                    }).dxChart("instance");
                }
            }
            chart();
        });
    </script>
@endpush
