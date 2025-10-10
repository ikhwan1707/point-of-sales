<h3>Form Edit Category</h3>
<form action="{{ route('category.update', $dataeditcategory->categorie_id) }}" method="POST">
    {{ csrf_field() }}
    @method('PUT')
    <label>Nama:</label>
    <input type="text" name="name" value="{{ old('name',$dataeditcategory->name)}}">
    <br>
    @if ($errors->has('name'))
    <span class="label label-danger">{{ $errors->first('name') }}</span>
    @endif
    <br>
    <label>Deskripsi:</label>
    <textarea name="description">{{ old('description',$dataeditcategory->description)}}</textarea>
    <br>
    <button type="submit">Update</button>
</form>