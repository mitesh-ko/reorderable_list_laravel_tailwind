@props(['disabled' => false])
@props(['options' => false])
@props(['selected' => ''])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!}>
    <option value="">Select a option</option>
    @foreach($options as $key => $value)
        <option value="{{ $key }}" @selected(old('project', $selected) == $key)>{{ $value }}</option>
    @endforeach
</select>
