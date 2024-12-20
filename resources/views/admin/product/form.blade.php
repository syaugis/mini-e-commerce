@push('styles')
    <style>
        .image-preview {
            display: inline-block;
            position: relative;
        }

        .image-preview button {
            position: absolute;
            top: 5px;
            right: 5px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let uploadedFiles = [];

        function previewImages() {
            var preview = document.getElementById('image_preview');
            var files = document.getElementById('product_images').files;

            for (var i = 0; i < files.length; i++) {
                uploadedFiles.push(files[i]);
                var file = files[i];
                var reader = new FileReader();

                reader.onload = (function(file) {
                    return function(e) {
                        var div = document.createElement('div');
                        div.classList.add('image-preview');
                        div.innerHTML = '<img src="' + e.target.result +
                            '" class="img-thumbnail" style="max-height: 120px; margin-right: 10px;">' +
                            '<button type="button" class="btn btn-danger btn-sm" onclick="removeImage(this, \'' +
                            file.name + '\')">Remove</button>';
                        preview.appendChild(div);
                    };
                })(file);

                reader.readAsDataURL(file);
            }
        }

        function removeImage(button, fileName) {
            var div = button.parentNode;
            div.parentNode.removeChild(div);
            uploadedFiles = uploadedFiles.filter(file => file.name !== fileName);
        }

        function removeExistingImage(button, imageId) {
            var div = button.parentNode;
            div.parentNode.removeChild(div);
            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'delete_images[]';
            input.value = imageId;
            document.getElementById('image_preview').appendChild(input);
        }

        function prepareForm() {
            var form = document.getElementById('product_form');
            var formData = new FormData(form);

            uploadedFiles.forEach(file => {
                formData.append('images[]', file);
            });

            var xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);
            xhr.onload = function() {
                if (xhr.status === 200) {
                    window.location.href = "{{ route('admin.product.index') }}";
                } else if (xhr.status === 422) {
                    alert('Validation error occurred!');

                } else {
                    alert('An error occurred!');
                }
            };
            xhr.send(formData);

            return false;
        }
    </script>
@endpush

<x-app-admin-layout :assets="$assets ?? []">
    <x-card :header="isset($id) ? 'Update Product' : 'New Product'" :action="route('admin.product.index')" :id="$id ?? null" :createdAt="$data->created_at ?? null" :updatedAt="$data->updated_at ?? null">
        <form id="product_form" method="POST"
            action="{{ isset($id) ? route('admin.product.update', $id) : route('admin.product.store') }}"
            enctype="multipart/form-data" onsubmit="return prepareForm()">
            @csrf
            @if (isset($id))
                @method('PUT')
            @endif
            <div class="row">
                <x-form-input name="name" id="product_name" label="Product Name" placeholder="Enter Product Name"
                    :value="$data->name ?? ''" required="true" />
                <x-form-input name="description" id="product_description" label="Description"
                    placeholder="Enter Product Description" :value="$data->description ?? ''" required="true" type="textarea" />
                <x-form-input name="price" id="product_price" label="Price" placeholder="Enter Price"
                    :value="$data->price ?? ''" required="true" type="number" />
                <x-form-input name="stock" id="product_stock" label="Stock" placeholder="Enter Stock Quantity"
                    :value="$data->stock ?? ''" required="true" type="number" />
                <x-select name="category_id" label="Category" :options="$product_categories" :selected="$data->category_id ?? ''" />
                <div class="form-group">
                    <label for="product_images">Upload Image</label>
                    <input type="file" class="form-control @error('product_images') is-invalid @enderror"
                        id="product_images" accept="image/*" multiple onchange="previewImages()">
                    <div id="image_preview" class="mt-3">
                        @if (isset($data) && $data->productImages)
                            @foreach ($data->productImages as $image)
                                <div class="image-preview" data-id="{{ $image->id }}">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" class="img-thumbnail"
                                        style="max-height: 120px; margin-right: 10px;">
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="removeExistingImage(this, {{ $image->id }})">Remove</button>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    @error('product_images')
                        <div class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </div>
                    @enderror
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </x-card>
</x-app-admin-layout>
