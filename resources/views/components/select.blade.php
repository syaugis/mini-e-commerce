<div class="form-group">
    <label for="{{ $name }}" class="form-label">{{ $label }} @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <select name="{{ $name }}" id="{{ $name }}" class="form-control @error($name) is-invalid @enderror"
        @if ($required) required @endif>
        <option value="">Select {{ $label }}</option>
        @foreach ($options as $value => $text)
            <option value="{{ $value }}" @if (old($name, $selected) == $value) selected @endif>
                {{ $text }}
            </option>
        @endforeach
    </select>

    @error($name)
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
    @enderror
</div>
