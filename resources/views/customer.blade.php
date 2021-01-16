@extends('layouts.app')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
  
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />  
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>  
      
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

<script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js" defer></script>

 
 <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">

@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Customer') }}</div>

                <div class="card-body">
                    <div style="float:right">
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#customerform">
                            New Customer
                        </button>
                        
                        <!-- Modal -->
                        <div class="modal fade" id="customerform" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Customer Form</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="customer-form" onsubmit="return saveCustomer(event)">
                                    <div class="modal-body">
                                        <input type="hidden" name="id">
                                        <div class="mb-3">
                                            <label for="name" class="form-label">Customer Name:</label>
                                            <input type="text" class="form-control" id="name"  name="name" placeholder="Customer name">
                                        </div>
                                        <div class="mb-3">
                                            <label for="contact" class="form-label">Contact No.:</label>
                                            <input type="text" class="form-control" id="contact" name="contact_no">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="submit" id="save" class="btn btn-primary">Save changes</button>
                                    </div>
                                </form>
                            </div>
                            </div>
                        </div>

                        <div >
                            <table id="customertable" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Customer name</th>
                                        <th>Contact Number</th>
                                    </tr>

                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

{{-- <script src="/plugins/datatables/datatables.bundle.js?v=7.0.4"></script> --}}
 
<script>
    let api;
    $(document).ready(function(){
        const baseapi = axios.create({ baseURL: "http://127.0.0.1:8000" });
         api = function(){
            let token = document.head.querySelector('meta[name="csrf-token"]');
            if (token) 
            {
                baseapi.defaults.headers.common["Authorization"] = `Bearer ${token}`;
            }
            return baseapi;
        }
       
       getCustomers();
    });

    async function getCustomers(){
        const customers = await api().get('customerapi');
        console.log(customers.data[0]);
        $('#customertable').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": customers,
        });
    }
   function saveCustomer(e){
        e.preventDefault();
        
       const form = document.getElementById('customer-form');
       let formdata = new FormData();
       formdata.append('name', form.name.value)
       formdata.append('contact_no', form.contact_no.value)

       api().post('customer', formdata);
   }
</script>
@endsection