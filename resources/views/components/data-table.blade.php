<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="header-title">
                    <h4 class="card-title">{{ $pageTitle }}</h4>
                </div>
                <div class="card-action d-flex gap-2 flex-column flex-sm-row">
                    @foreach ($headerAction as $action)
                        {!! $action !!}
                    @endforeach
                </div>
            </div>
            <div class="card-body px-0">
                <div class="table-responsive">
                    {{ $dataTable->table(['class' => 'table text-center table-striped table-hover w-100'], true) }}
                </div>
            </div>
        </div>
    </div>
</div>
