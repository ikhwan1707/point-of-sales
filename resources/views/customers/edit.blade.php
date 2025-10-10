<h3>Form Edit Customer</h3>

<form action="{{ route('customers.update',  $dataeditcustomer->customer_id) }}" method="POST">
    {{csrf_field()}}
    @method('PUT')
    <label>Nama:</label>
    <input type="text" name="name" value="{{ old('name',$dataeditcustomer->name)}}">
    <br>
    @if ($errors->has('name'))
    <span>{{$errors->first('name')}}</span>
    @endif
    </br>
    <label>Phone:</label>
    <input type="text" name="phone" value="{{ old('phone',$dataeditcustomer->phone)}}">
    <br>
    @if ($errors->has('phone'))
    <span>{{$errors->first('phone')}}</span>
    @endif
    </br>
    <label>Address:</label>
    <textarea name="address">{{ old('address', $dataeditcustomer->address)}}</textarea>
    <br>
    @if ($errors->has('address'))
    <span>{{$errors->first('address')}}</span>
    @endif
    </br>
    <label>Member</label>
    <select name="is_member">
        <option value=1 {{ old('is_member', $dataeditcustomer->is_member ?? '') == 1 ? 'selected' : ''}}>Member</option>
        <option value=0 {{ old('is_member', $dataeditcustomer->is_member ?? '') == 0 ? 'selected' : ''}}>Non-Member</option>
    </select>
    <br>
    @if ($errors->has('is_member'))
    <span>{{$errors->first('is_member')}}</span>
    @endif
    <br>
    <button type="submit">Update</button>
</form>