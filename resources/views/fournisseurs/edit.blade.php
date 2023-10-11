@extends('layouts.app')

@section('title', 'Edit Reglement fournisseur')

@section('content')

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit fournisseur</h1>
        <a href="{{route('fournisseurs.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
    </div>

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit fournisseur</h6>
        </div>
        <form method="POST" action="{{route('fournisseurs.update', ['fournisseur' => $fournisseur->id])}}">
            @csrf
            @method('PUT')

            <div class="card-body">
                <div class="form-group row">

                {{-- CODE --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <span style="color:red;">*</span>code</label>
                        <input 
                            type="text" 
                            class="form-control form-control-fournisseur @error('code') is-invalid @enderror" 
                            id="examplEcode"
                            placeholder="code" 
                            name="code" 
                            value="{{ old('code') ?  old('code') : $fournisseur->code}}">

                        @error('code')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                  

                    {{-- raison_sociale --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <span style="color:red;">*</span>raison_sociale</label>
                        <input 
                            type="text" 
                            class="form-control form-control-fournisseur" 
                            id="exampleraison_sociale"
                            placeholder="raison_sociale" 
                            name="raison_sociale" 
                            value="{{ old('raison_sociale') ?  old('raison_sociale') : $fournisseur->raison_sociale }}">

                        @error('raison_sociale')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    

                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-user float-right mb-3">Update</button>
                <a class="btn btn-primary float-right mr-3 mb-3" href="{{ route('fournisseurs.index') }}">Cancel</a>
            </div>
        </form>
    </div>

</div>

@include('common.footer')
@endsection



