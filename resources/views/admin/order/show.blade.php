@push('scripts')
    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}
@endpush
<x-app-admin-layout :assets="$assets ?? []">
    <div class="row">
        @php
            $previousUrl = url()->previous();
            $backUrl = Str::contains($previousUrl, '/admin/order')
                ? route('admin.order.index')
                : (Str::contains($previousUrl, '/admin/user')
                    ? route('admin.user.show', $data->user->id)
                    : url()->previous());
        @endphp
        <x-card :header="'Order Detail'" :action="$backUrl" :id="$id ?? null" :createdAt="$data->created_at ?? null" :updatedAt="$data->updated_at ?? null">
            <form method="POST" action="{{ route('admin.order.update', $id) }}">
                @csrf
                @method('PUT')
                <input type="hidden" name="redirect" value="{{ url()->previous() }}">
                <div class="row">
                    <x-form-input name="user_name" id="order_user_name" label="Order User Name" :value="$data->user->name ?? ''"
                        :readonly="true" />
                    <x-form-input name="shipping_phone" id="order_shipping_phone" label="Order User Phone"
                        :value="$data->shippingAddress->phone ?? ''" :readonly="true" />
                    <x-select name="status" id="order_status" label="Order Status" :options="[
                        0 => 'Pending',
                        1 => 'Paid',
                        2 => 'Shipped',
                        3 => 'Completed',
                        4 => 'Canceled',
                    ]"
                        :selected="$data->status ?? ''" required="true" />
                    <x-form-input name="total_price" id="order_total_price" label="Order Total Price" :value="$data->formatted_total_price ?? ''"
                        :readonly="true" />
                    <x-form-input name="shipping_address" id="order_shipping_address" label="Order Shipping Address"
                        :value="$data->shippingAddress->address ?? ''" :readonly="true" />
                    <x-form-input name="shipping_city" id="order_shipping_city" label="Order Shipping City"
                        :value="$data->shippingAddress->city ?? ''" :readonly="true" />
                    <x-form-input name="shipping_postcode" id="order_shipping_postcode"
                        label="Order Shipping Postal code" :value="$data->shippingAddress->postcode ?? ''" :readonly="true" />
                </div>
                <button type="submit" class="btn btn-primary">Update Order</button>
            </form>
        </x-card>
    </div>
    <div class="row">
        <x-data-table :pageTitle="$pageTitle ?? 'List'" :dataTable="$dataTable" />
    </div>
</x-app-admin-layout>
