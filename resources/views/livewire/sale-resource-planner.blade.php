<div class="mt-2">
    <div class="card-header pd-b-0 pd-x-20 bd-b-0">
        <div class="d-sm-flex align-items-center justify-content-between">
            <h3 class="mg-b-0 card-body tx-center">
                Resources planner <br>
            </h3>
        </div>
    </div>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col" class="tx-left" style="width: 20%">Employee</th>
                <th scope="col" class="tx-center" style="width: 30%">Remarks</th>
                <th scope="col" class="tx-center" style="width: 10%">Sellable hourly rate</th>
                <th scope="col" class="tx-center" style="width: 10%">Hours estimated</th>
                <th scope="col" class="tx-center" style="width: 10%">MAX hours</th>
                <th scope="col" class="tx-center" style="width: 10%">Total</th>
                <th scope="col" class="tx-center" style="width: 10%">Time USED</th>
            </tr>
        </thead>
        <tbody>
        @foreach($sale->estimate as $estimate)
            <livewire:sale-resource-planner-row :estimate="$estimate" :wire:key="$estimate->id">
        @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="5" class="tx-right"><b>Total:</b></td>
                <td class="tx-center">
                    {{ \App\Label::formatPrice($total) }}
                </td>
            </tr>
        </tfoot>
    </table>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col" class="tx-left" style="width: 20%">Employee</th>
                <th scope="col" class="tx-center" style="width: 30%">Total minimal cost</th>
                <th scope="col" class="tx-center" style="width: 30%">Total cost with markup</th>
            </tr>
        </thead>
        <tbody>
        @foreach($minTotal as $employee => $row)
            <tr>
                <td class="tx-right"><b>{{ $employee }}</b></td>
                <td class="tx-center">{{ \App\Label::formatPrice($row['min']) }}</td>
                <td class="tx-center">{{ \App\Label::formatPrice($row['normal']) }}</td>
            </tr>
        @endforeach
        <tr>
            <td class="tx-right"><b>Total:</b></td>
            <td class="tx-center">
                <strong>
                    <em>{{ \App\Label::formatPrice($minTotal->sum('min')) }}</em>
                </strong>
            </td>
            <td class="tx-center">
                <strong>
                    <em>{{ \App\Label::formatPrice($minTotal->sum('normal')) }}</em>
                </strong>
            </td>
        </tr>
        </tbody>
    </table>
</div>
