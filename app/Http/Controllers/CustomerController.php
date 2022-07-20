<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return Customer::all();
    }

    public function show($id)
    {
        return Customer::findOrFail($id);
    }

    public function store(Request $request)
    {
        $customer = [
            'name'=> $request->name,
            'phone_number'=> $request->phone_number,
        ];
        
        $createdCustomer = Customer::create($customer);
        
        return response()->json([
            'success'=> true,
            'message'=> 'customer created successfully',
            'data'=> $createdCustomer
        ]);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $customer->update($request->all());

        return response()->json([
            'success'=> true,
            'message'=> 'customer updated successfully',
            'data'=> $customer
        ]);
    }

    public function delete($id)
    {
        $customer = Customer::findOrFail($id);

        $customer->destroy($id);

        return response()->json([
            'success'=> true,
            'message'=> 'customer deleted successfully',
            'data'=> $customer
        ]);
    }
}
