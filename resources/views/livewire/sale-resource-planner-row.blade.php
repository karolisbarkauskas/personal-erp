<tr @if($estimate->isOver($estimate->empl->full_name)) class="bg-danger text-white" @endif>
    <td>
        {{ $estimate->empl->full_name }}
    </td>
    <td>
        <textarea class="form-control" wire:model="remarks" rows="2"
                  id="remarks"></textarea>
        @error('hourly_rate') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td>
        <input type="text" class="form-control" wire:model="hourly_rate" value="{{ $estimate->hourly_rate }}"
               id="hourly_rate" name="text" />
        @error('hourly_rate') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td>
        <input type="text" class="form-control" wire:model="hours_needed" value="{{ $estimate->hours_needed }}"
               id="hours_needed" name="text" />
        @error('hours_needed') <span class="error">{{ $message }}</span> @enderror
    </td>
    <td class="text-center">
        {{ $estimate->decimalToTime($hours_for_employee) }}
    </td>
    <td class="text-center">
        {{ \App\Label::formatPrice($total) }}
    </td>
    <td class="text-center">
        {{ $estimate->decimalToTime($estimate->sale->usageByEmployee($estimate->empl->full_name) / 60) }}
    </td>
</tr>
