<h1>Form Edit</h1>
<form action="{{route('user.update',$dataedit->user_id)}}" method="POST">
    {{ csrf_field() }}
    @method("PUT")
    <label>Name:</label>
    <input type="text" name="name" value="{{$dataedit->name}}"></br>
    <label>Email:</label>
    <input type="email" name="email" value="{{$dataedit->email}}"></br>
    <label>Password:</label>
    <input type="password" name="password" value=""></br>
    <label>Role:</label>
    <input type="radio" name="role" value="admin" {{$dataedit->role == 'admin' ? "checked" : ""}}>admin
    <input type="radio" name="role" value="cashier" {{$dataedit->role == 'cashier' ? "checked" : ""}}>cashier</br>
    <button type="submit">Update</button>
</form>