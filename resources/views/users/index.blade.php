<h3>Tampil data user</h3>
<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th><a href="{{route('user.create')}}">Create User</a></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($datauser as $v)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $v->name }}</td>
            <td>{{ $v->email }}</td>
            <td>{{ $v->role }}</td>
            <td>
                <form action="{{route('user.destroy', $v->user_id)}}" method="POST">
                    {{ csrf_field() }}
                    @method("DELETE")
                    <a href="{{route('user.edit',$v->user_id)}}">Edit</a>
                    <button type="submit" onclick="return confirm('Are you sure you want to delete this users?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>