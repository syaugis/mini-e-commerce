<div class="row">
    @empty(!$id)
        <div class="col-xl-9 col-lg-8">
        @endempty
        <div class="card">
            @isset($header)
                <div class="card-header d-flex justify-content-between">
                    <div class="header-title">
                        <h4 class="card-title">{{ $header }}</h4>
                    </div>
                    @isset($action)
                        <div class="card-action">
                            <a href="{!! $action !!}" class="btn btn-sm btn-primary" role="button">Back</a>
                        </div>
                    @endisset
                </div>
            @endisset
            <div class="card-body">
                {{ $slot }}
            </div>
        </div>

        @empty(!$id)
        </div>
        <div class="col-xl-3 col-lg-4">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="form-group col-md-12">
                            <p class="mb-0"><b>Created at</b></p>
                            <p class="mb-2">
                                {{ $createdAt === null ? '-' : \Carbon\Carbon::parse($createdAt)->diffForHumans() }}
                            </p>
                            <p class="mb-0"><b>Last modified at</b></p>
                            <p class="mb-0">
                                {{ $updatedAt === null ? '-' : \Carbon\Carbon::parse($updatedAt)->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endempty
</div>
