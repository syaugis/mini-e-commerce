<div class="form-group col-md-12">
    <label class="form-label" for="{{ $id }}">
        {{ $label }}
        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <input type="file" class="form-control @error($name) is-invalid @enderror"
        name="{{ $name }}@if ($multiple) [] @endif" id="{{ $id }}"
        value="{{ $value }}" accept="{{ $accept }}" @if ($required) required @endif
        @if ($multiple) multiple @endif>
    @error($name)
        <div class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </div>
    @enderror
</div>
