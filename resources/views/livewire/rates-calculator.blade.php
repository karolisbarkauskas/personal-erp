<div>
    <div class="card card-body">
        <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
            Calculator
        </h6>

        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col" style="width: 25%">Name</th>
                    <th scope="col" style="width: 15%">30€/h</th>
                    <th scope="col" style="width: 15%">35€/h</th>
                    <th scope="col" style="width: 15%"><strong>40€/h</strong></th>
                    <th scope="col" style="width: 15%">45€/h</th>
                    <th scope="col" style="width: 15%">50€/h</th>
                    <th scope="col" style="width: 15%">65€/h</th>
                </tr>
            </thead>
            <tbody>
            @foreach($employees as $employee)
                <tr>
                    <td>{{ $employee->full_name }}</td>
                    <td>{{ $employee->calculateDelay(30, $hours) }}</td>
                    <td>{{ $employee->calculateDelay(35, $hours) }}</td>
                    <td><strong>{{ $employee->calculateDelay(40, $hours) }}</strong></td>
                    <td>{{ $employee->calculateDelay(45, $hours) }}</td>
                    <td>{{ $employee->calculateDelay(50, $hours) }}</td>
                    <td>{{ $employee->calculateDelay(65, $hours) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="sellable_hours_per_day">Sold hours to client</label>
                <input type="text" class="form-control" wire:model="hours" value="">
            </div>
            <div class="form-group col-md-6">
                <label for="sellable_hours_per_day">Sold EUROS to client</label>
                <input type="text" class="form-control" wire:model="euros" value="">
            </div>
        </div>
    </div>
</div>
