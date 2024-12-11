<x-app-admin-layout :assets="$assets ?? []">
    <x-card :header="isset($id) ? 'Update Category' : 'New Category'" :action="route('admin.category.index')" :id="$id ?? null" :createdAt="$data->created_at ?? null" :updatedAt="$data->updated_at ?? null">
        <form method="POST"
            action="{{ isset($id) ? route('admin.category.update', $id) : route('admin.category.store') }}"
            enctype="multipart/form-data">
            @csrf
            @if (isset($id))
                @method('PUT')
            @endif
            <div class="row">
                <x-form-input name="name" id="category_name" label="Category Name" placeholder="Enter Category Name"
                    :value="$data->name ?? ''" required="true" />
            </div>
            <button type="submit" class="btn btn-primary">{{ isset($id) ? 'Update' : 'Add' }} Category</button>
        </form>
    </x-card>
</x-app-admin-layout>
