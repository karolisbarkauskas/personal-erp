@extends('layouts.app')

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card mg-b-10">
                <div class="card-body p-1">
                    <div class="d-sm-flex">
                        <h5 class="text-center">
                            Current period finances overview
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <table class="table table-dashboard mg-b-0 table-hover">
                        <tbody>
                        <tr>
                            <td class="tx-medium text-left">
                                Total monthly non-salary expenses
                            </td>
                            <td class="tx-medium text-center">
                                {{ \App\Label::formatPrice($finances->getNonSalaryExpenses()) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="tx-medium text-left">
                                Total salaries
                            </td>
                            <td class="tx-medium text-center">
                                {{ \App\Label::formatPrice($finances->getSalaryExpenses()) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="tx-medium text-left">
                                Total Expenses
                            </td>
                            <td class="tx-medium text-center">
                                {{ \App\Label::formatPrice($finances->getTotalExpenses()) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="tx-medium text-left" data-toggle="tooltip" data-placement="top"
                                title="DOES NOT INCLUDE INTER TEAM">
                                PROGRAMMING hours to sell per month
                            </td>
                            <td class="tx-medium text-center">
                                {{ $finances->getTotalWorkHoursPerMonth() }} h.
                            </td>
                        </tr>
                        <tr>
                            <td class="tx-medium text-left" data-toggle="tooltip" data-placement="top"
                                title="To pay all expenses and salaries without any markup">
                                Break even hourly rate
                            </td>
                            <td class="tx-medium text-center">
                                {{ \App\Label::formatPrice($finances->getCompanyHourlyRateWithoutMarkup()) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="tx-medium text-left">
                                Markup
                            </td>
                            <td class="tx-medium text-center">
                                {{ $finances->getMarkup() }}%
                            </td>
                        </tr>
                        <tr>
                            <td class="tx-medium text-left" data-toggle="tooltip" data-placement="top"
                                title="Markup + Break even rate">
                                Hourly rate
                            </td>
                            <td class="tx-medium text-center">
                                {{ \App\Label::formatPrice($finances->getCompanyHourlyRateWithMarkup()) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="tx-medium text-left">
                                Work days per month
                            </td>
                            <td class="tx-medium text-center">
                                {{ $finances->getWorkDaysPerMonth() }} d.
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <table class="table table-dashboard mg-b-0 table-hover">
                        <thead>
                        <tr>
                            <th colspan="5" class="text-center">
                                ONESOFT
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($onesoftTeam as $member)
                            <tr>
                                <td class="tx-medium text-center">
                                    {{ $member->getFullName() }}
                                </td>
                                <td class="tx-medium text-center" data-toggle="tooltip" data-placement="top"
                                    title="Sellable hours per day">
                                    {{ $member->expected_work_time }} h
                                </td>
                                <td class="tx-medium text-center" data-toggle="tooltip" data-placement="top"
                                    title="Payment">
                                    {{ \App\Label::formatPrice($member->getSalary(now()->format('Y'),now()->format('m'))) }}
                                </td>
                                <td class="tx-medium text-center" data-toggle="tooltip" data-placement="top"
                                    title="Sellable hours per month">
                                    {{ $member->estimatedWorkHours() }} h
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="tx-medium text-center bg-black-1">
                                Total hours per day to book
                            </td>
                            <td class="tx-medium text-center bg-black-1" colspan="3">
                                {{ $onesoftTeam->sum('expected_work_time') }} h
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <table class="table table-dashboard mg-b-0 table-hover">
                        <thead>
                        <tr>
                            <th colspan="5" class="text-center">
                                PrestaPRO
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($prestaPROTeam as $member)
                            <tr>
                                <td class="tx-medium text-center">
                                    {{ $member->getFullName() }}
                                </td>
                                <td class="tx-medium text-center" data-toggle="tooltip" data-placement="top"
                                    title="Sellable hours per day">
                                    {{ $member->expected_work_time }} h
                                </td>
                                <td class="tx-medium text-center" data-toggle="tooltip" data-placement="top"
                                    title="Payment">
                                    {{ \App\Label::formatPrice($member->getSalary(now()->format('Y'),now()->format('m'))) }}
                                </td>
                                <td class="tx-medium text-center" data-toggle="tooltip" data-placement="top"
                                    title="Sellable hours per month">
                                    {{ $member->estimatedWorkHours() }} h
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="tx-medium text-center bg-black-1">
                                Total hours per day to book
                            </td>
                            <td class="tx-medium text-center bg-black-1" colspan="3">
                                {{ $prestaPROTeam->sum('expected_work_time') }} h
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <table class="table table-dashboard mg-b-0 table-hover">
                        <thead>
                        <tr>
                            <th colspan="5" class="text-center">
                                Inter
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($interTeam as $member)
                            <tr>
                                <td class="tx-medium text-center">
                                    {{ $member->getFullName() }}
                                </td>
                                <td class="tx-medium text-center" data-toggle="tooltip" data-placement="top"
                                    title="Sellable hours per day">
                                    {{ $member->expected_work_time }} h
                                </td>
                                <td class="tx-medium text-center" data-toggle="tooltip" data-placement="top"
                                    title="Payment">
                                    {{ \App\Label::formatPrice($member->getSalary(now()->format('Y'),now()->format('m'))) }}
                                </td>
                                <td class="tx-medium text-center" data-toggle="tooltip" data-placement="top"
                                    title="Sellable hours per month">
                                    {{ $member->estimatedWorkHours() }} h
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td class="tx-medium text-center bg-black-1">
                                Total hours per day to book
                            </td>
                            <td class="tx-medium text-center bg-black-1" colspan="3">
                                {{ $interTeam->sum('expected_work_time') }} h
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <table class="table table-dashboard mg-b-0 table-hover">
                        <tbody>
                        <tr>
                            <td class="tx-medium text-center">
                                ONESOFT total
                            </td>
                            <td class="tx-medium text-center">
                                {{ $onesoftTeam->sum('expected_work_time') * $finances->getWorkDaysPerMonth() }} h
                            </td>
                            <td class="tx-medium text-center" data-toggle="tooltip" data-placement="top"
                                title="Minimal Eur to make">
                                {{ \App\Label::formatPrice($finances->getONESOFTMinimum(), null, '.', 0) }}
                            </td>
                            <td class="tx-medium text-center" data-toggle="tooltip" data-placement="top"
                                title="Expected to be earned">
                                {{ \App\Label::formatPrice($finances->getONESOFTPreferred(), null, '.', 0) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="tx-medium text-center">
                                PrestaPRO total
                            </td>
                            <td class="tx-medium text-center">
                                {{ $prestaPROTeam->sum('expected_work_time') * $finances->getWorkDaysPerMonth() }} h
                            </td>
                            <td class="tx-medium text-center" data-toggle="tooltip" data-placement="top"
                                title="Minimal Eur to make">
                                {{ \App\Label::formatPrice($finances->getPrestaPROMinimum(), null, '.', 0) }}
                            </td>
                            <td class="tx-medium text-center" data-toggle="tooltip" data-placement="top"
                                title="Expected to be earned">
                                {{ \App\Label::formatPrice($finances->getPrestaPROPrefered(), null, '.', 0) }}
                            </td>
                        </tr>
                        <tr>
                            <td class="tx-medium text-center">
                                Inter to generate
                            </td>
                            <td class="tx-medium text-center" data-toggle="tooltip" data-placement="top"
                                title="Expected to be earned" colspan="3">
                                {{ \App\Label::formatPrice($finances->getInterToGenerate(), null, '.', 0) }}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab"
                               aria-controls="home" aria-selected="true">Expenses</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab"
                               aria-controls="profile" aria-selected="false">Employee salaries</a>
                        </li>
                    </ul>
                    <div class="tab-content bd bd-gray-300 bd-t-0 pd-20" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <table class="table table-dashboard mg-b-0 table-hover">
                                <tbody>
                                @foreach($recurringExpenses as $expense)
                                    <tr>
                                        <td class="tx-medium text-center">
                                            <a href="{{ route('recurring-expenses.edit', $expense) }}" target="_blank">
                                                {{ $expense->name }}
                                            </a>
                                        </td>
                                        <td class="tx-medium text-center" data-toggle="tooltip" data-placement="top"
                                            title="Contract expiration">
                                            {{ $expense->contract_expires }}
                                        </td>
                                        <td class="tx-medium text-center">
                                            {{ \App\Label::formatPrice($expense->size) }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <table class="table table-dashboard mg-b-0 table-hover">
                                <tbody>
                                @foreach($onesoftTeam->merge($prestaPROTeam)->merge($interTeam) as $member)
                                    <tr>
                                        <td class="tx-medium text-center">
                                            <a href="{{ route('employees.edit', $member) }}" target="_blank">
                                                {{ $member->getFullName() }}
                                            </a>
                                        </td>
                                        <td class="tx-medium text-center">
                                            {{ \App\Label::formatPrice($member->getSalary(now()->format('Y'),now()->format('m'))) }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('javascript')
    <script type="text/javascript">
        $('[data-toggle="tooltip"]').tooltip();
    </script>
@endpush
