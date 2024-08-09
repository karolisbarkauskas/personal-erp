<div>

    <div class="row">
        <div class="col-md-5">
            <div class="card mg-b-10">
                <div class="card-body pd-y-30">
                    <div class="d-sm-flex">
                        <h5>
                            Client: <em>{!! $precome->client->name !!}</em> <br>
                            Total Preliminary income: <strong>{!! \App\Label::formatPrice($precome->total_eur) !!}</strong> <br>

                            @if($precome->total_eur)
                                <a wire:click="createIncome({!! $precome->id !!})" class="btn btn-dark text-white">
                                    CREATE INCOME FOR {!! \App\Label::formatPrice($precome->total_eur) !!}
                                </a>
                            @else
                                <div class="alert alert-danger">
                                    Income not possible
                                </div>
                            @endif
                        </h5>
                    </div>
                </div><!-- card-body -->
            </div><!-- card -->
        </div>
    </div>

</div>
