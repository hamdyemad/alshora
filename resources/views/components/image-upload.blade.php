@props([
    'name',
    'label',
    'image' => null,
    'placeholderIcon' => 'uil uil-user',
    'placeholderText' => 'Click to upload',
    'recommendedText' => 'Recommended size: 500x500px',
    'required' => false
])

@php
    $type = str_replace('_', '-', $name);
    $inputId = $name; // Keep input name/id as is for form submission
    $previewContainerId = $type . '-preview-container';
    $previewImageId = $type . '-preview';
    $placeholderId = $type . '-placeholder';
@endphp

<div class="col-md-6 mb-25">
    <div class="form-group">
        <label class="il-gray fs-14 fw-500 mb-10">
            {{ $label }}
            @if($required) <span class="text-danger">*</span> @endif
        </label>
        <div class="logo-upload-wrapper">
            <div class="logo-preview-container" id="{{ $previewContainerId }}">
                @if ($image)
                    <img src="{{ asset('storage/' . $image->path) }}"
                        alt="{{ $label }}" id="{{ $previewImageId }}"
                        class="uploaded-image">
                @else
                    <div class="logo-placeholder" id="{{ $placeholderId }}">
                        <i class="{{ $placeholderIcon }}"></i>
                        <p>{{ $placeholderText }}</p>
                        <small>{{ $recommendedText }}</small>
                    </div>
                @endif
                <div class="logo-overlay">
                    <button type="button" class="btn-change-image"
                        onclick="document.getElementById('{{ $inputId }}').click()">
                        <i class="uil uil-camera"></i> {{ trans('lawyer.change') }}
                    </button>
                    <button type="button" class="btn-remove-image"
                        onclick="removeImage('{{ $type }}')"
                        style="{{ $image ? '' : 'display: none;' }}">
                        <i class="uil uil-trash-alt"></i> {{ trans('lawyer.remove') }}
                    </button>
                </div>
            </div>
            <input type="file" class="d-none" id="{{ $inputId }}" name="{{ $name }}"
                accept="image/*" onchange="previewImage(this, '{{ $type }}')">
        </div>
        @error($name)
            <div class="text-danger mt-2">{{ $message }}</div>
        @enderror
    </div>
</div>

@push('scripts')
<script>
    if (typeof window.previewImage === 'undefined') {
        window.previewImage = function(input, type) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    const container = document.getElementById(`${type}-preview-container`);
                    const placeholder = document.getElementById(`${type}-placeholder`);
                    
                    // Find existing preview image or create new one
                    let previewImg = document.getElementById(`${type}-preview`);
                    
                    if (!previewImg) {
                        previewImg = document.createElement('img');
                        previewImg.id = `${type}-preview`;
                        previewImg.className = 'uploaded-image';
                        previewImg.alt = 'Preview';
                        // Insert before the overlay div
                        const overlay = container.querySelector('.logo-overlay');
                        container.insertBefore(previewImg, overlay);
                    }

                    // Set image source
                    previewImg.src = e.target.result;
                    previewImg.style.display = 'block';

                    // Hide placeholder
                    if (placeholder) placeholder.style.display = 'none';
                    
                    // Show remove button
                    const removeBtn = container.querySelector('.btn-remove-image');
                    if (removeBtn) removeBtn.style.display = 'inline-block';
                };

                reader.readAsDataURL(input.files[0]);
            }
        };
    }

    if (typeof window.removeImage === 'undefined') {
        window.removeImage = function(type) {
            const container = document.getElementById(`${type}-preview-container`);
            const previewImg = document.getElementById(`${type}-preview`);
            const placeholder = document.getElementById(`${type}-placeholder`);
            const removeBtn = container.querySelector('.btn-remove-image');
            // Input ID might have underscores, but type has hyphens. 
            // We need to reconstruct the input ID. 
            // Since we don't have the original name here easily in a global function, 
            // we assume the standard replacement or we need to pass the input ID to this function.
            // For now, let's try replacing hyphens with underscores as a guess, 
            // OR better, look for the input inside the wrapper if possible, but the input is outside the container in the blade.
            // Actually in the blade: <input ... id="{{ $inputId }}"> which is $name.
            // $type is $name with _ -> -.
            // So replacing - with _ in type should give back $name (usually).
            const inputId = type.replace(/-/g, '_');
            const inputField = document.getElementById(inputId);

            // Remove preview image
            if (previewImg) {
                previewImg.remove();
            }

            // Show placeholder and hide remove button
            if (placeholder) placeholder.style.display = 'flex';
            if (removeBtn) removeBtn.style.display = 'none';

            // Clear file input
            if (inputField) inputField.value = '';
        };
    }
</script>
@endpush
