<h1>Form Create</h1>
<form action="{{route('user.store')}}" method="POST">
    {{ csrf_field() }}
    <label>Name:</label>
    <input type="text" name="name"></br>
    <label>Email:</label>
    <input type="email" name="email"></br>
    <label>Password:</label>
    <input type="password" name="password"></br>
    <label>Role:</label>
    <input type="radio" name="role" value="admin" checked>admin
    <input type="radio" name="role" value="cashier">cashier</br>
    <button type="submit">Save</button>
</form>