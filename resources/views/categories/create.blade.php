<h3>Add Category</h3>
<form action="{{ route('category.store') }}" method="POST">
    {{ csrf_field() }}
    <label>Nama:</label>
    <input type="text" name="name">
    <br>
    <label>Deskripsi:</label>
    <textarea name="description"></textarea>
    <br>
    <button type="submit">Save</button>
</form>