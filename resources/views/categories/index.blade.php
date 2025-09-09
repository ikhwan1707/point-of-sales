<h3>Categories</h3>
<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>Category Name</th>
            <th>Description</th>
            <th>
                <a href="{{ route('category.create') }}">+ Create Category</a>
            </th>
        </tr>
    </thead>
    <tbody>
        @foreach ($categories as $v)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $v->name }}</td>
            <td>{{ $v->description }}</td>
            <td>

                <form action="{{ route('category.destroy', $v->categorie_id) }}" method="POST" style="display:inline">
                    {{ csrf_field() }}
                    @method('DELETE')
                    <a href="{{ route('category.edit', $v->categorie_id) }}">Edit</a> {{-- Tombol Edit --}}
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this category?')">
                        Delete
                    </button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>