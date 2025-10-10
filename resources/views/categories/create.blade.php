<h3>Add Category</h3>
<form action="{{ route('category.store') }}" method="POST">
    {{ csrf_field() }}
    <label>Nama:</label>
    <input type="text" name="name" value="{{old('name')}}">
    <br>
    @if ($errors->has('name'))
    <span class="label label-danger">{{ $errors->first('name') }}</span>
    @endif
    <br>
    <label>Deskripsi:</label>
    <textarea name="description">{{old('description')}}</textarea>
    <br>
@if ($errors->has('description'))
<span class="label label-danger">{{ $errors->first('description') }}</span>
@endif
    <button type="submit">Save</button>
    <a href="{{route('category.index')}}">Kembali</a>
</form>