@extends('layouts.app')

@section('title', 'Edit Achat')

@section('content')

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Achats</h1>
        <a href="{{route('achats.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-arrow-left fa-sm text-white-50"></i> Back</a>
    </div>

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Achat</h6>
        </div>
        <form method="POST" action="{{route('achats.update', ['achat' => $achat->id])}}">
            @csrf
            @method('PUT')

            <div class="card-body">
                <div class="form-group row">

                <!-- {{-- NR_DE_RÉGLEMENT --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <span style="color:red;">*</span>NR_DE_RÉGLEMENT</label>
                        <input 
                            type="text" 
                            class="form-control form-control-achat @error('nr_de_reglement') is-invalid @enderror" 
                            id="examplEnr_de_reglement"
                            placeholder="NR_DE_RÉGLEMENT" 
                            name="nr_de_reglement" 
                            value="{{ old('nr_de_reglement') ?  old('nr_de_reglement') : $achat->nr_de_reglement}}">

                        @error('nr_de_reglement')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div> -->

                    {{-- Date --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <span style="color:red;">*</span>Date</label>
                        <input 
                            type="date" 
                            class="form-control form-control-achat @error('date') is-invalid @enderror" 
                            id="exampleDate"
                            placeholder="Date" 
                            name="date" 
                            value="{{ old('date') ?  old('date') : $achat->date }}">

                        @error('date')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    {{-- Mode --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <span style="color:red;">*</span>Mode</label>
                        <input 
                            type="text" 
                            class="form-control form-control-achat @error('mode') is-invalid @enderror" 
                            id="exampleMode"
                            placeholder="Mode" 
                            name="mode" 
                            value="{{ old('mode') ?  old('mode') : $achat->mode }}">

                        @error('mode')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    {{-- Reference --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <span style="color:red;">*</span>Reference</label>
                        <input 
                            type="text" 
                            class="form-control form-control-achat @error('reference') is-invalid @enderror" 
                            id="exampleReference"
                            placeholder="Reference" 
                            name="reference" 
                            value="{{ old('reference') ?  old('reference') : $achat->reference }}">

                        @error('reference')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    {{-- Date Echeance --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <span style="color:red;">*</span>date_echeance</label>
                            <input 
                                type="date" 
                                class="form-control form-control-achat @error('date_echeance') is-invalid @enderror" 
                                id="exampleDateEcheance"
                                placeholder="Date Echeance" 
                                name="date_echeance" 
                                value="{{ old('date_echeance') ? old('date_echeance') : $achat->date_echeance }}">

                            @error('date_echeance')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                    </div>

                    {{-- Montant Regle --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <span style="color:red;">*</span>Montant Regle</label>
                            <input 
                                type="text" 
                                class="form-control form-control-achat @error('date_echeance') is-invalid @enderror" 
                                id="exampleMontantRegle"
                                placeholder="Montant Regle" 
                                name="montant_regle" 
                                value="{{ old('montant_regle') ? old('montant_regle') : $achat->montant_regle }}">

                            @error('montant_regle')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                    </div>

                    {{-- Code Fournisseur --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <span style="color:red;">*</span>Code Fournisseur</label>
                            <input 
                                type="text" 
                                class="form-control form-control-achat @error('date_echeance') is-invalid @enderror" 
                                id="exampleCodeFournisseur"
                                placeholder="Code Fournisseur" 
                                name="code_fournisseur" 
                                value="{{ old('code_fournisseur') ? old('code_fournisseur') : $achat->code_fournisseur }}">

                            @error('code_fournisseur')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                    </div>

                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-user float-right mb-3">Mettre à jour</button>
                <a class="btn btn-primary float-right mr-3 mb-3" href="{{ route('achats.index') }}">Annuler</a>
            </div>
        </form>
    </div>

</div>

@include('common.footer')
@endsection



