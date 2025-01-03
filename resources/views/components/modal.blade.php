<div class="modal fade" id="{{ $modalId }}" tabindex="-1" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false" aria-labelledby="{{ $modalId }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ $action }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if ($method !== 'POST')
                    @method($method)
                @endif

                <div class="modal-header">
                    <h5 class="modal-title" id="{{ $modalId }}Title">{{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body" id="{{ $modalId }}Body">
                    <div class="main_form-body">
                        {{ $slot }}
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{ $submitLabel }}</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
