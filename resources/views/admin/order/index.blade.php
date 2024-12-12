@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
<x-app-admin-layout :assets="$assets ?? []">
    <x-data-table :pageTitle="$pageTitle ?? 'List'" :headerAction="$headerAction ?? ''" :dataTable="$dataTable" />
</x-app-admin-layout>
