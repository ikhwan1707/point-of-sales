<?php

namespace App\Http\Controllers;

use App\Customers;
use Illuminate\Http\Request;

class CustomersController extends Controller
{

    public function index()
    {
        $customers = Customers::all();
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'is_member' => 'required'
        ]);

        Customers::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_member' => $request->is_member
        ]);

        return redirect(route('customer.index'));
    }


    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $dataeditcustomer = Customers::findOrFail($id);
        return view('customers.edit', compact('dataeditcustomer'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'    => 'required|string',
            'phone'   => 'required|digits_between:10,15',
            'address' => 'nullable|string',
            'is_member' => 'required'
        ]);

        $Customers = Customers::findOrFail($id);
        $Customers->update([
            'name'    => $request->name,
            'phone'   => $request->phone,
            'address' => $request->address,
            'is_member' => $request->is_member
        ]);

        return redirect()->route('customer.index');
    }

    public function destroy($id)
    {
        Customers::where('customer_id', $id)->delete();
        return redirect(route('customer.index'));
    }
}
