<?php

namespace App\Http\Controllers;

use App\CollabUsers;
use App\Employee;
use App\Http\Requests\CreateEmployee;
use App\Http\Requests\EditEmployee;
use App\Http\Requests\EmployeeEdit;
use App\Jobs\CalculateEmployeeNumbers;
use App\OutOfOffice;
use Carbon\Carbon;
use Spatie\Activitylog\Models\Activity;

class EmployeesController extends Controller
{
    public function index()
    {
        $employees = Employee::active()->get();

        return view('employees.index', compact('employees'));
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function create()
    {
        return view('employees.create');
    }

    public function store(CreateEmployee $employee)
    {
        $employee = Employee::create(
            $employee->only('full_name')
        );

        return redirect()->route('employees.edit', $employee)->with('success', 'OK!');
    }

    public function update(EditEmployee $editEmployee, Employee $employee)
    {
        $employee->update(
            $editEmployee->all()
        );

        CalculateEmployeeNumbers::dispatchSync();

        activity('employees')
            ->performedOn($employee)
            ->withProperties($employee->getChanges())
            ->log('Updated information');

        return redirect()->back()->with('success', 'OK!');
    }
}
