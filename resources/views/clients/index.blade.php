@extends('layouts.app')

@section('title', 'clients List')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">clients</h1>
            
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ route('clients.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus"></i> Ajouter un nouveau client
                    </a>
                </div>
                
                
                
            </div>

        </div>

        {{-- Alert Messages --}}
        @include('common.alert')

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">les clients</h6>

            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable2" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th width="40%">code</th>
                                <th width="40%">raison_sociale</th>
                                <th width="20%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clients as $client)
                                <tr>
                                    <td>{{ $client->code }}</td>
                                    <td>{{ $client->raison_sociale }}</td>
                                    <td style="display: flex">
                                        <a href="{{ route('clients.edit', ['client' => $client->id]) }}"
                                            class="btn btn-primary m-2">
                                            <i class="fa fa-pen"></i>
                                        </a>
                                        <a class="btn btn-danger m-2" href="#" data-toggle="modal" data-target="#deleteModal" data-attr="{{ route('clients.destroy', $client->id) }}">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    
                </div>
            </div>
        </div>

    </div>

    @include('clients.delete-modal')
    @include('common.footer')
@endsection



