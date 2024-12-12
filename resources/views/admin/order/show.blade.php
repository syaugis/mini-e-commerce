@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
<x-app-admin-layout :assets="$assets ?? []">
    <div class="row">
        <x-card :header="'Order Detail'" :action="url()->previous()" :id="$id ?? null" :createdAt="$data->created_at ?? null" :updatedAt="$data->updated_at ?? null">
            <div class="row">
                <x-form-input name="user_name" id="order_user_name" label="Order User Name" :value="$data->user->name ?? ''"
                    :readonly="true" />
                <x-form-input name="shipping_phone" id="order_shipping_phone" label="Order User Phone" :value="$data->shippingAddress->phone ?? ''"
                    :readonly="true" />
                <x-form-input name="status" id="order_status" label="Order Status" :value="$data->status_label ?? ''"
                    :readonly="true" />
                <x-form-input name="total_price" id="order_total_price" label="Order Total Price" :value="$data->formatted_total_price ?? ''"
                    :readonly="true" />
                <x-form-input name="shipping_address" id="order_shipping_address" label="Order Shipping Address"
                    :value="$data->shippingAddress->address ?? ''" :readonly="true" />
                <x-form-input name="shipping_city" id="order_shipping_city" label="Order Shipping City"
                    :value="$data->shippingAddress->city ?? ''" :readonly="true" />
                <x-form-input name="shipping_postcode" id="order_shipping_postcode" label="Order Shipping Postal code"
                    :value="$data->shippingAddress->postcode ?? ''" :readonly="true" />
            </div>
        </x-card>
    </div>
    <div class="row">
        <x-data-table :pageTitle="$pageTitle ?? 'List'" :dataTable="$dataTable" />
    </div>
</x-app-admin-layout>
