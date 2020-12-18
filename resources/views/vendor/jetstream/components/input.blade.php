@props(['disabled' => false, 'model' => ""])

<input wire:model="{{ $model }}" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'form-input rounded-md shadow-sm']) !!}>
