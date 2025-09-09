<h3>Update Product</h3>
<form action="{{ route('products.update', $product->product_id) }}" method="POST" enctype="multipart/form-data">
    {{ csrf_field() }}
    @method('PUT')

    <label>Name</label>
    <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}">
    </br>


    <label>Description</label>
    <textarea name="description">{{ old('description', $product->description ?? '') }}</textarea>
    </br>


    <label>Price</label>
    <input type="number" name="price" value="{{ old('price', $product->price ?? '') }}">
    </br>


    <label>Stock</label>
    <input type="number" name="stock" value="{{ old('stock', $product->stock ?? '') }}">
    </br>


    <label>Category</label>
    <select name="categorie_id">
        <option value="">-- Choose Category --</option>
        @foreach ($categories as $category)
        <option value="{{ $category->categorie_id }}" {{ old('categorie_id', $product->categorie_id ?? '') ==
            $category->categorie_id ?
            'selected' : '' }}>
            {{ $category->name }}
        </option>
        @endforeach
    </select>
    </br>


    <label>Status</label>
    <select name="status">
        <option value="available" {{ old('status', $product->status ?? '') == 'available' ? 'selected' : '' }}>Available
        </option>
        <option value="unavailable" {{ old('status', $product->status ?? '') == 'unavailable' ? 'selected' : ''
            }}>Unavailable
        </option>
    </select>
    </br>


    <label>Image</label>
    <input type="file" name="image">
    @if (!empty($product->image))
    <p class="mt-2"><img src="{{ asset('storage/'.$product->image) }}" width="100"></p>
    @endif
    </br>
    <button type="submit">Update</button>
</form>