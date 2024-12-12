<div class="form-group col-md-12">
    <label class="form-label" for="{{ $id }}">
        {{ $label }} @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    @if ($type === 'textarea')
        <textarea name="{{ $name }}" id="{{ $id }}"
            class="form-control{{ $errors->has($name) ? ' is-invalid' : '' }}" placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }} {{ $readonly ? 'readonly' : '' }}>{{ old($name, $value) }}</textarea>
    @else
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $id }}"
            value="{{ old($name, $value) }}" class="form-control{{ $errors->has($name) ? ' is-invalid' : '' }}"
            placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }} {{ $readonly ? 'readonly' : '' }} />
    @endif

    @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
