@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tresorerie-graphes(Crédit)</h1>
        
    </div>

    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center mb-3">Bienvenue</h2>
        </div>
    </div>

    <div class="col-md-12 mb-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            graph: amounts paid per month</div>
                            <div class="card-body">
                            <div>
                            <div class="btn-group" role="group" aria-label="Basic exemple">
                                <button type="button" data-group="day" class="btn btn-sm btn-success">Jour</button>
                                <button type="button" data-group="week" class="btn btn-sm btn-primary">Semaine</button>
                                <button type="button" data-group="month" class="btn btn-sm btn-danger">Mois</button>
                                <button type="button" data-group="year" class="btn btn-sm btn-secondary">Année</button>

                            </div>

                            </div>
                            <canvas id="myChart2" width="400" height="100"></canvas>
                            </div>
                        
                        
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
    </div>

</div>
@include('common.footer')
@endsection


@section('scripts')
@parent
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script>
  const ctx = document.getElementById('myChart2').getContext('2d');
  const myChart = new Chart(ctx, {
                        type: 'line',
                        options: {
                            scales: {
                                y: {
                                    beginAtZero: true
                                }
                            }
                        }
                    });
  function displayChart(group ='month'){

    fetch("{{ route('bancaires.bancairesChart') }}?group=" + group)
        .then(response =>response.json())
        .then(json => {
            myChart.data.labels=json.labels;
            myChart.data.datasets=json.datasets;
            myChart.update();

                        
        })
  }
  $('.btn-group .btn').on('click', function(e) {
    console.log($(this).data('group'));
    e.preventDefault();
    displayChart($(this).data('group'));
  });

  displayChart();



  
</script>


@endsection