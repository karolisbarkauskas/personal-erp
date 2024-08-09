<div>
    <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
        Sold hours this week
    </h6>
    <div>
        <h3 class="tx-normal {{ $list->getBookedClass() }} tx-rubik mg-b-0 mg-r-5 lh-1 w-full">
            {{ $list->client_sold_hours }}/{{ $list->client_sellable_hours }}<small>/{{ $list->break_even_hours }}</small>
        </h3>
    </div>
</div>
