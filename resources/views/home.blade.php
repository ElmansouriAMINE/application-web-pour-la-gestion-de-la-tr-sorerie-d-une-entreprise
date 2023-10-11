@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tableau de bord</h1>
    </div>

    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center mb-3">Bienvenue</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Règlements d'achat de demain :</h6>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                            
                                            <th width="20%">Mode</th>
                                            <th width="20%">Reference</th>
                                            <th width="20%">Montant réglé</th>
                                            <th width="20%">Fournisseur</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reg as $item)
                                            <tr>
                                                
                                                <td>{{ $item->mode }}</td>
                                                <td>{{ $item->reference }}</td>
                                                <td>{{ $item->montant_regle }}</td>
                                                
                                                @foreach($fournisseurs as $fournisseur)
                                                @if($item->code_fournisseur == $fournisseur->code)
                                                <td> {{$fournisseur->raison_sociale}}
                                                   </td>
                                                @endif 
                    
                                                @endforeach
                                        
                    
                                                
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            
                            </div>
                            </div>
                            </div>
                          </div>
                        </div>
                        
                    </div>
                </div>
            
        
        <div class="col-md-12">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary"> Les règlements bancaires de demain :</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                           
                                            <th width="20%">Mode</th>
                                            <th width="20%">Reference</th>
                                            <th width="20%">Montant réglé</th>
                                            <th width="20%">Client</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($regbancaire as $item)
                                            <tr>
                                                
                                                <td>{{ $item->mode }}</td>
                                                <td>{{ $item->reference }}</td>
                                                <td>{{ $item->montant_regle }}</td>
                                                @foreach($clients as $client)
                                                    @if($item->code_client == $client->code)
                                                    <td> {{$client->raison_sociale}}
                                                    </td>
                                                    @endif 
                    
                                                @endforeach
                                                
                                            </tr>
                                        @endforeach
                                    </tbody>
                    </table>
                            
                </div>
            </div>
        </div>
       

        <!--  -->
        <div class="col-md-12">

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">les montants validés par mois:</h6>
                <!-- <button id="button">cliquez</button> -->
                <h4 style="color:red" id="error"></h3>   <h3></h3>
                <!-- <strong style="color:Green">Débit:</strong> <input type="checkbox" id="myCheck2">
                <strong style="color:Red">Crédit:</strong> <input type="checkbox" id="myCheck1" >
                <br> <br>  
                <button onclick="checkCheckboxnew()">Submit</button> <br>   -->
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                        <thead>
                                        <tr>
                                            <th width="10%">mois</th>
                                            <th width="5%">Janvier</th>
                                            <th width="5%">février</th>
                                            <th width="5%">mars</th>
                                            <th width="5%">avril</th>
                                            <th width="5%">mai</th>
                                            <th width="5%">juin</th>
                                            <th width="5%">juillet</th>
                                            <th width="5%">août</th>
                                            <th width="5%">septembre</th>
                                            <th width="5%">octobre</th>
                                            <th width="5%">novembre</th>
                                            <th width="5%">décembre</th>
                                            <th width="5%">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr id="montants1">
                                        </tr>
                                        <tr id="montants2">
                                        </tr>
                                        <tr id="montants3">
                                        </tr>
                                        <tr id="montants4">
                                        </tr>

                                    </tbody>
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


@section('scripts')


<script type="text/javascript">
///

var debit = document.getElementById("myCheck1");  
    var credit = document.getElementById("myCheck2");  

    document.getElementById("error").innerHTML ='';  
    var xhr = new XMLHttpRequest();
    xhr.open('GET',"{{ route('bancaires.bancairesTable1') }}",true);
    var xhr3 = new XMLHttpRequest();
    xhr3.open('GET',"{{ route('bancaires.bancairesTable2') }}",true);
    document.getElementById("error").innerHTML ='';  
    var xhr2 = new XMLHttpRequest();
    xhr2.open('GET',"{{ route('achats.achatsTable1') }}",true);
    var xhr4 = new XMLHttpRequest();
    xhr4.open('GET',"{{ route('achats.achatsTable2') }}",true);

    xhr.onload=function(){
        if(this.status == 200){
            var montants=JSON.parse(this.responseText);
            var amount=0;
            var output='<td>Montant(Crédit validé)</td>';
            for(var i in montants.datasets[0].data){
                amount+=montants.datasets[0].data[i];
                output +=
                '<td style="color:Green">'+montants.datasets[0].data[i]+'</td>';
            }
            output+='<td>'+amount+'</td>';
            document.getElementById('montants1').innerHTML =output;
        }
    }
    xhr3.onload=function(){
        if(this.status == 200){
            var montants=JSON.parse(this.responseText);
            var amount=0;
            var output='<td>Montant(Crédit En attente)</td>';
            for(var i in montants.datasets[0].data){
                amount+=montants.datasets[0].data[i];
                output +=
                '<td style="color:red">'+montants.datasets[0].data[i]+'</td>';
            }
            output+='<td>'+amount+'</td>';
            document.getElementById('montants2').innerHTML =output;
        }
    }
    xhr2.onload=function(){
        if(this.status == 200){
            var montants=JSON.parse(this.responseText);
            var amount=0;
            var output='<td>Montant(Débit validé)</td>';
            for(var i in montants.datasets[0].data){
                amount+=montants.datasets[0].data[i];
                output +=
                '<td style="color:green">'+montants.datasets[0].data[i]+'</td>';
            }
            output+='<td>'+amount+'</td>';
            document.getElementById('montants3').innerHTML =output;
        }
    }

    xhr4.onload=function(){
        if(this.status == 200){
            var montants=JSON.parse(this.responseText);
            var amount=0;
            var output='<td>Montant(Débit En attente)</td>';
            for(var i in montants.datasets[0].data){
                amount+=montants.datasets[0].data[i];
                output +=
                '<td style="color:red">'+montants.datasets[0].data[i]+'</td>';
            }
            output+='<td>'+amount+'</td>';
            document.getElementById('montants4').innerHTML =output;
        }
    }
    xhr.send();
    xhr2.send();
    xhr3.send();
    xhr4.send();   
  


///
</script>
@endsection