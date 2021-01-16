@extends('layouts.app')

@section('head')
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
<link href="https://cdn.datatables.net/1.10.23/css/dataTables.bootstrap4.min.css" rel="stylesheet" >
<script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js" defer ></script>
<script src="https://cdn.datatables.net/1.10.23/js/dataTables.bootstrap4.min.js" defer></script>


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

                        <div class="row">
                            <div class="col">
                                <table id= "customertable">
                                    <thead>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Contact No</th>
                                        <th>Actions</th>
                                    </thead>
                                </table>
                            </div>
                        </div>
                            
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
    let isEdit = false;
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
        $('#customertable').DataTable({
            ajax:{
                url:'/customerapi',
                type:'GET'
            },
            columns:[
                {data:'id'},
                {data:'name'},
                {data:'contact_no'},
                {data: 'id',responsivePriority:-1}
            ],
            columnDefs:[
                {
                    targets:-1,
                    title:'Actions',
                    orderable:false,
                    render:function(data, type, full, meta){
                        return '\
                                <a href="javascript:editCustomer('+ data +');" class="btn btn-sm btn-clean btn-icon" title="Edit details">\
                                Edit\
                                </a>\
                                <a href="javascript:deleteCustomer('+data+')" class="btn btn-sm btn-clean btn-icon" title="Delete" id="'+data+'">\
                                Delete\
                                </a>\
                            ';
                    }
                   
                }
            ]
        });
    });


   function saveCustomer(e){
        e.preventDefault();
        
       const form = document.getElementById('customer-form');
       let formdata = new FormData();
       formdata.append('id', form.id.value)
       formdata.append('name', form.name.value)
       formdata.append('contact_no', form.contact_no.value)
        if(!isEdit){
            api().post('customer', formdata).then(
                response=>{
                    $('#customerform').modal('hide');
                   var table =$('#customertable').DataTable();
                   console.log('response add',response);
                   table.row.add(response.data).draw();
                }
            )
        }
        else{
            let formEdit = document.getElementById('customer-form');
            
            const data = {id:formEdit.id.value, name:formEdit.name.value, contact_no:formEdit.contact_no.value}
            console.log('data',data);
            api().put(`customer/${formEdit.id.value}`,data).then(
                response=>{
                    var table = $('#customertable').DataTable();
                    const row = $(`#${formEdit.id.value}`);
                    $('#customertable').DataTable().row(row.parents('tr')).remove().draw();
                    table.row.add(response.data).draw();
                    $('#customerform').modal('hide');
                    isEdit = false;
                }
            );
        }
      
   }

   function editCustomer(data){
       let editform = document.getElementById('customer-form');
       api().get(`customer/${data}/edit`).then(
           response=>{
               console.log('response',response);
               editform.id.value = response.data.id;
               editform.name.value = response.data.name;
               editform.contact_no.value = response.data.contact_no;
               $('#customerform').modal('show');
               isEdit = true;
           }
       );
       
       
   }

   function deleteCustomer(data){
       const row = $(`#${data}`);
       api().delete(`customer/${data}`).then(
        response=>{
            $('#customertable').DataTable().row(row.parents('tr')).remove().draw();
        }
       );
   }
</script>
@endsection