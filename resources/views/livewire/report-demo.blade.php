<div>
    <div class="row">
        <div class="col-sm-2">
            <div class="card card-body">
                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                    Number reminders
                </h6>
                <div class="d-flex d-lg-block d-xl-flex align-items-end">
                    <div class="tx-15 tx-color-03 mg-b-0">
                        <div class="tx-dark">
                            Salaries <strong>{{ \App\Label::formatPrice($salaries) }}</strong> <br>
                            Other expenses <strong>{{ \App\Label::formatPrice($expenses) }}</strong> <br>
                            <hr>
                            Total: <strong><em>{{ \App\Label::formatPrice($salaries + $expenses) }}</em></strong> <br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-sm-6">
            <div class="products-container">
                <h6 class="tx-uppercase tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                    Expenses: <br>
                    Recurring expenses sum + Employee expenses to cover + Employee salaries
                </h6>
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th scope="col" style="width: 20%">Type</th>
                        <th scope="col" style="width: 50%">Name</th>
                        <th scope="col" style="width: 30%">Size</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $i = 0 @endphp
                    @foreach($employees as $employee)
                        @php $i++ @endphp
                        <tr>
                            <td>Salary</td>
                            <td>{{ $employee->full_name }}</td>
                            <td><input type="text" wire:model="allSalaries.{{ $i }}" class="form-control"/></td>
                        </tr>
                    @endforeach
                    @foreach($employees as $employee)
                        @php $i++ @endphp
                        <tr>
                            <td>Covered by others</td>
                            <td>{{ $employee->full_name }}</td>
                            <td><input type="text" wire:model="allExpenses.{{ $i }}" class="form-control"/></td>
                        </tr>
                    @endforeach
                    @foreach($recurringExpenses as $recurringExpense)
                        @php $i++ @endphp
                        <tr>
                            <td>Recurring expense</td>
                            <td>
                                {{ $recurringExpense->name }}
                                @if($recurringExpense->getCoveredIncome()->sum('average') > 0)
                                    <small>{{ $recurringExpense->getCoverage() }}
                                        = {{ \App\Label::formatPrice($recurringExpense->getCoveredIncome()->sum('average')) }}</small>
                                    <br>
                                    <small>New
                                        total: {{ \App\Label::formatPrice($recurringExpense->size - $recurringExpense->getCoveredIncome()->sum('average')) }}</small>
                                @endif
                            </td>
                            <td><input type="text" wire:model="allExpenses.{{ $i }}" class="form-control"/></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="card card-body">
                <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
                    Sales hours targets (with {{ \App\Label::formatPrice($hourlyRate) }} rate)
                </h6>
                <div class="mg-b-0">
                    <table class="table-hover table-striped">
                        <tr>
                            <td class="p-2">Minimal (0% profits)</td>
                            <td>
                                <strong class="text-danger">
                                    {{ round($minimalHoursToSellNoProfit) }}
                                    / {{ ceil($minimalHoursToSellNoProfit / \App\Settings::WEEKS_PER_MONTH_AVG) }}
                                    = {{ \App\Label::formatPrice($minimalHoursToSellNoProfit*$hourlyRate) }}
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2" style="padding-right: 30px !important;">Targeted ({{ $markup }}% profits, {{ \App\Label::formatPrice(($optimalHoursToSell*$hourlyRate)-($minimalHoursToSellNoProfit*$hourlyRate)) }})</td>
                            <td>
                                <strong class="text-info">
                                    {{ round($optimalHoursToSell) }}
                                    / {{ ceil($optimalHoursToSell / \App\Settings::WEEKS_PER_MONTH_AVG) }}
                                    = {{ \App\Label::formatPrice($optimalHoursToSell*$hourlyRate) }}
                                </strong>
                            </td>
                        </tr>
                        <tr>
                            <td class="p-2">SUPER ({{ $averageMarkup }}% profits, {{ \App\Label::formatPrice(($healthyHoursToSell*$hourlyRate)-($minimalHoursToSellNoProfit*$hourlyRate)) }})</td>
                            <td>
                                <strong class="text-success">
                                    {{ round($healthyHoursToSell) }}
                                    / {{ ceil($healthyHoursToSell / \App\Settings::WEEKS_PER_MONTH_AVG) }}
                                    = {{ \App\Label::formatPrice($healthyHoursToSell*$hourlyRate) }}
                                </strong>
                            </td>
                        </tr>
                    </table>
                    <hr />
                    Markup: <input type="text" wire:model.defer="markup" class="form-control"/> <br>
                    Hourly rate: <input type="text" wire:model.defer="hourlyRate" class="form-control"/>
                    <button class="btn btn-info mt-3 w-100" wire:click="calculate()">Go</button>
                </div>
            </div>
        </div>
    </div>
</div>
