@extends('layouts.app')

@section('title', 'Add  client')

@section('content')

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add client</h1>
        <a href="{{route('clients.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-arrow-left fa-sm text-white-50"></i> Retour</a>
    </div>

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Ajouter un nouveau client</h6>
        </div>
        <form method="POST" action="{{route('clients.store')}}">
            @csrf
            <div class="card-body">
                <div class="form-group row">
               
                    {{-- code --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <span style="color:red;">*</span>code</label>
                        <input 
                            type="text" 
                            class="form-control form-control-client @error('code') is-invalid @enderror" 
                            id="examplEcode"
                            placeholder="code" 
                            name="code" 
                            value="{{ old('code') }}">

                        @error('code')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    
                    {{-- raison_sociale --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        Raison Sociale</label>
                        <input 
                            type="text"
                            class="form-control form-control-client" 
                            id="exampleraison_sociale"
                            placeholder="raison_sociale" 
                            name="raison_sociale" 
                            value="{{ old('raison_sociale') }}">
                    </div>

                    

                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-user float-right mb-3">Enregistrer</button>
                <a class="btn btn-primary float-right mr-3 mb-3" href="{{ route('clients.index') }}">Annuler</a>
            </div>
        </form>
    </div>

</div>

@include('common.footer')
@endsection