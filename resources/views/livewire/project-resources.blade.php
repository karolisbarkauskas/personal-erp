<div>
    <div class="row">
        <div class="col-md-12">
            <div class="card mg-b-10">
                <div class="card-body">
                    <table class="table table-dashboard mg-b-0 table-hover table-striped">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th class="text-center">Client time sold</th>
                                <th class="text-center">Employee time available</th>
                                <th class="text-center">Employee time actual</th>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($project->resources as $resource)
                                <livewire:resource-line :resource="$resource" :wire:key="$resource->id">
                            @endforeach
                        </tbody>
                        <tr>
                            <td colspan="3" class="text-right">
                                <em>Total:</em>
                            </td>
                            <td class="text-left">
                                {{ \App\Label::formatPrice($project->getTotalBudget()) }}
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
