@extends('layouts.app')

@section('title', 'Bancaires List')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Crédit</h1>

            <div class="row">
            <div class="row input-daterange">
                <div class="col-md-4">
                    <input type="text" name="from_date" id="from_date" class="form-control" placeholder="à partir de la date" readonly />
                </div>
                <div class="col-md-4">
                    <input type="text" name="to_date" id="to_date" class="form-control" placeholder="jusqu'à la date" readonly />
                </div>
                <div class="col-md-4">
                    <button type="button" name="filter" id="filter" class="btn btn-primary">Filtrer</button>
                    <button type="button" name="refresh" id="refresh" class="btn btn-success">rafraîchir</button>
                </div>
                <div class="col-md-4">
                &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;    
                <select name="filter_status " id="filter_status" class="form-control" required>
                            <option value="">Select Status</option>
                            <option value=1>Validé</option>
                            <option value=0>En attente</option>
                </select>

                </div>

            </div>
            </div>
          
            <div class="row">
                
                <div class="col-md-6">
                    <a href="{{ route('bancaires.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Ajouter un noubeau réglement bancaire
                    </a>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('bancaires.export') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-check"></i> Exporter vers excel
                    </a>
                </div>
                
                
            </div>
            

        </div>

        {{-- Alert Messages --}}
        @include('common.alert')

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Toutes les reglements bancaires</h6>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="0%">NR_DE_Réglement</th>
                                <th width="10%">Date</th>
                                <th width="15%">Mode</th>
                                <th width="15%">Reference</th>
                                <th width="10%">Date d'échéance</th>
                                <th width="15%">Montant réglé</th>
                                <th width="15%">CLient</th>
                                <th width="10%">Status</th>
                                <th width="25%">Action</th>
                            </tr>
                        </thead>
                        
                        <tfoot>
                            <tr>
                                <th></th>
                                <th></th>
                                <th colspan="4" style="text-align:right" ></th>
                                <th></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>

                    
                </div>
            </div>
        </div>

    </div>

    @include('bancaires.delete-modal')
    @include('common.footer')
    @endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.js"></script>

<script>
$(document).ready(function(){
 $('#from_date').datepicker({ 
  todayBtn:'linked',
  dateFormat: 'dd/mm/yy',
  autoclose:true
});
 $('#to_date').datepicker({ 
  todayBtn:'linked',
  dateFormat: 'dd/mm/yy',
  autoclose:true
});


 load_data();

 function load_data(from_date = '', to_date = '', filter_status= '')
 {
  $('#dataTable2').DataTable({
   processing: true,
   serverSide: true,
   ajax: {
    url:'{{ route("bancaires.index") }}',
    data:{from_date:from_date, to_date:to_date , filter_status:filter_status},
    
   },
   
   columns: [
    {
     data:'nr_de_reglement',
     name:'nr_de_reglement'
    },
    {
     data:'date',
     name:'date'
    },
    {
     data:'mode',
     name:'mode'
    },
    {
     data:'reference',
     name:'reference'
    },
    {
     data:'date_echeance',
     name:'date_echeance'
    },
    {
     data:'montant_regle',  
     name:'montant_regle'
    },
    {
     data:'code_client',
     name:'code_client'
    }
    ,
    {
     data:'status',
     name:'status',
    }
    ,
    {
     data:'action',
     name:'action'
    }
   ],
   columnDefs: [
            {
                target: 0,
                visible: false,
                searchable: false,
            },
            
        ],
        footerCallback: function (row, data, start, end, display) {
            var api = this.api();
 
            // Remove the formatting to get integer data for summation
            var intVal = function (i) {
                return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
            };
 
            // Total over all pages
            total = api
                .column(5)
                .data()
                .reduce(function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0);
 
 
            // Update footer
            $(api.column(4).footer()).html('Montant Total:'+' ' + total.toFixed(2) + 'Dh');
        },

    
  });
  


 
 }

 $('#filter').click(function(){
  var from_date = $('#from_date').val();
  var to_date = $('#to_date').val();
  var filter_status = $('#filter_status').val();
  if(from_date != '' &&  to_date != '' && filter_status != '')
  {
   $('#dataTable2').DataTable().destroy();
   load_data(from_date, to_date, filter_status);
  }
  else
  {
   alert('Both Date is required and the status(Non validé/validé)');
  }
 });

 $('#refresh').click(function(){
  $('#filter_status').val('');
  $('#from_date').val('');
  $('#to_date').val('');
  $('#dataTable2').DataTable().destroy();
  load_data();
 });

 

});




</script>


@endsection
