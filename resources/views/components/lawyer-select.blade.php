@props([
    'name' => 'lawyer_id',
    'selected' => null,
    'placeholder' => null,
    'required' => false,
    'class' => '',
    'id' => null
])

@php
    $componentId = $id ?? 'lawyer-select-' . uniqid();
    $placeholderText = $placeholder ?? trans('reservation.select_lawyer');
    
    // Load all lawyers for the select
    $lawyers = \App\Models\Lawyer::with('user')
        ->where('active', true)
        ->get();
@endphp

<select 
    name="{{ $name }}" 
    id="{{ $componentId }}"
    class="form-select {{ $class }}"
    {{ $required ? 'required' : '' }}
    {{ $attributes }}
>
    <option value="">{{ $placeholderText }}</option>
    @foreach($lawyers as $lawyer)
        <option value="{{ $lawyer->id }}" {{ $selected == $lawyer->id ? 'selected' : '' }}>
            {{ $lawyer->getTranslation('name', app()->getLocale()) }}
        </option>
    @endforeach
</select>
