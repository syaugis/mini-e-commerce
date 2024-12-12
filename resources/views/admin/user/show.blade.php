@push('scripts')
    {{ $shippingAddressTable->scripts(attributes: ['type' => 'module']) }}
    {{ $orderTable->scripts(attributes: ['type' => 'module']) }}
@endpush
<x-app-admin-layout :assets="$assets ?? []">
    <div class="row">
        <x-card :header="'User Detail'" :action="route('admin.user.index')" :id="$id ?? null" :createdAt="$data->created_at ?? null" :updatedAt="$data->updated_at ?? null">
            <div class="row">
                <x-form-input name="user_name" id="user_name" label="User Name" :value="$data->name ?? ''" :readonly="true" />
                <x-form-input name="email" id="user_email" label="User Email" :value="$data->email ?? ''" :readonly="true" />
            </div>
        </x-card>
    </div>
    <div class="row">
        <x-data-table :pageTitle="$shippingTitle ?? 'List'" :dataTable="$shippingAddressTable" />
    </div>
    <div class="row">
        <x-data-table :pageTitle="$orderTitle ?? 'List'" :dataTable="$orderTable" />
    </div>
</x-app-admin-layout>
