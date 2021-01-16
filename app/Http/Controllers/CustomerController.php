<?php

namespace App\Http\Controllers;

use App\Models\customer;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use PHPUnit\Util\Json;


class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('customer');
    }

    public function getCustomers()
    {
        $customers = customer::all();
        return json_encode(array('data' => $customers));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $customer = customer::create(
            $this->validate($request, [
                'name' => ['required', 'max:255', 'unique:customers'],
                'contact_no' => ['required', 'max:255']
            ])
        );
        return $customer;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(customer $customer)
    {
        //
        return $customer;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, customer $customer)
    {

        $customer->update($this->validate($request, [
            'name' => ['required', 'max:255', Rule::unique('customers')->ignore($customer)],
            'contact_no' => ['required', 'max:255']
        ]));
        return $customer;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(customer $customer)
    {
        //
        $customer->delete();
    }
}
