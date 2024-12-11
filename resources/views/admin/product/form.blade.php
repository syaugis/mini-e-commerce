<x-app-admin-layout :assets="$assets ?? []">
    <x-card :header="isset($id) ? 'Update Product' : 'New Product'" :action="route('admin.product.index')" :id="$id ?? null" :createdAt="$data->created_at ?? null" :updatedAt="$data->updated_at ?? null">
        <form method="POST" action="{{ isset($id) ? route('admin.product.update', $id) : route('admin.product.store') }}"
            enctype="multipart/form-data">
            @csrf
            @if (isset($id))
                @method('PUT')
            @endif
            <div class="row">
                <x-form-input name="name" id="product_name" label="Product Name" placeholder="Enter Product Name"
                    :value="$data->name ?? ''" required="true" />
                <x-form-input name="description" id="product_description" label="Description"
                    placeholder="Enter Product Description" :value="$data->description ?? ''" required="false" type="textarea" />
                <x-form-input name="price" id="product_price" label="Price" placeholder="Enter Price"
                    :value="$data->price ?? ''" required="true" type="number" />
                <x-form-input name="stock" id="product_stock" label="Stock" placeholder="Enter Stock Quantity"
                    :value="$data->stock ?? ''" required="true" type="number" />
                <x-select name="category_id" label="Category" :options="$product_categories" :selected="$data->category_id ?? ''" required="true" />
                <x-image name="images" id="product_images" accept="image/*" label="Upload Image"
                    placeholder="Pilih gambar" multiple="true" />

            </div>
            <button type="submit" class="btn btn-primary">{{ isset($id) ? 'Update' : 'Add' }} Product</button>
        </form>
    </x-card>
</x-app-admin-layout>
