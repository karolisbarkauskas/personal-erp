<div>
    <div class="card card-body">
        <h6 class="tx-uppercase tx-11 tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
            Weekly Actions
        </h6>
        <div class="d-flex d-lg-block d-xl-flex align-items-end">
            <p class="tx-11 tx-color-03 mg-b-0">
                @if($list->is_locked)
                    <span class="badge badge-info" style="cursor: pointer" wire:click="update()">WEEK IS LOCKED</span>
                @else
                    <span class="badge badge-warning" style="cursor: pointer" wire:click="update()">WEEK IS NOT LOCKED</span>
                @endif
            </p>
        </div>
    </div>
    <div class="card card-body mt-3">
        <h6 class="tx-uppercase tx-spacing-1 tx-color-02 tx-semibold mg-b-8">
            {{ $list->year }}/W{{ $list->week }}
        </h6>
        <div class="d-flex d-lg-block d-xl-flex align-items-end">
            <p class="tx-11 tx-color-03 mg-b-0">
                @if($list->is_finished)
                    <span class="badge badge-success" style="cursor: pointer" wire:click="updateFinish()">Week marked as finished</span>
                @else
                    <span class="badge badge-warning" style="cursor: pointer" wire:click="updateFinish()">Week is not finished</span>
                @endif
            </p>
        </div>
    </div>
</div>
