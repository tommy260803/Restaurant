@props([
    'name',
    'label',
    'type' => 'text',
    'value' => '',
    'required' => false,
])

<div x-data="{ value: '{{ old($name, $value) }}', error: '' }" class="w-full">
    <label for="{{ $name }}" class="text-sm font-semibold text-gray-700">{{ $label }}</label>
    <input
        :class="error ? 'border-red-500' : 'border-gray-300'"
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        x-model="value"
        @input="
            if (value.trim() === '') {
                error = 'Este campo es obligatorio';
            } else {
                error = '';
            }
        "
        class="mt-1 w-full rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 border"
        {{ $required ? 'required' : '' }}
    >
    <template x-if="error">
        <p class="text-red-500 text-xs mt-1" x-text="error"></p>
    </template>
    @error($name)
        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
    @enderror
</div>
