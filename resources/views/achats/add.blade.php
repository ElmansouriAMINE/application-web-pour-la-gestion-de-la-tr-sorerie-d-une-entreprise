@extends('layouts.app')

@section('title', 'Add Achats')

@section('content')

<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Add Achats</h1>
        <a href="{{route('achats.index')}}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                class="fas fa-arrow-left fa-sm text-white-50"></i> Retour</a>
    </div>

    {{-- Alert Messages --}}
    @include('common.alert')
   
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"> Ajouter un nouveau reglement d'acaht</h6>
        </div>
        <form method="POST" action="{{route('achats.store')}}">
            @csrf
            <div class="card-body">
                <div class="form-group row">
            
                    {{-- Date --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        <span style="color:red;">*</span>Date</label>
                        <input 
                            type="date" 
                            class="form-control form-control-achat @error('date') is-invalid @enderror" 
                            id="exampleDate"
                            placeholder="Date" 
                            name="date" 
                            value="{{ old('date') }}">

                        @error('date')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                    </div>

                    {{-- Mode --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                    <span style="color:red;">*</span></span>Mode</label>

                        <select name="mode" id="exampleMode" class="form-control form-control-achat" required>
                            <option value="">Select Mode</option>
                            <option value="ESPECE">ESPECE</option>
                            <option value="CHEQUE">CHEQUE</option>
                            <option value="EFFET">EFFET</option>
                            <option value="VIREMENT">VIREMENT</option>
                            <option value="CARTE">CARTE</option>
                            <option value="AVOIR">AVOIR</option>
                </select>
                        @error('mode')
                            <span class="text-danger">{{$message}}</span>
                        @enderror
                     
                    </div>

                    {{-- Reference --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                        Reference</label>
                        <input 
                            type="text" 
                            class="form-control form-control-achat @error('reference') is-invalid @enderror" 
                            id="exampleReference"
                            placeholder="Reference" 
                            name="reference" 
                            value="{{ old('reference') }}">

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
                                value="{{ old('date_echeance') }}">

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
                                value="{{ old('montant_regle') }}">

                            @error('montant_regle')
                                <span class="text-danger">{{$message}}</span>
                            @enderror
                    </div>

                    {{-- Code Fournisseur --}}
                    <div class="col-sm-6 mb-3 mt-3 mb-sm-0">
                    
                        <span style="color:red;">*</span>Raison sociale</label>
                        <a href="{{route('fournisseurs.create')}}">nouvelle raison</a>
                        <select name="code_fournisseur" class="form-control form-control-achat @error('code_fournisseur') is-invalid @enderror" id="list-fournisseurs" required>
                                <option value="">Select Type</option>
                            @foreach($fournisseurs as $fournisseur)
                        
                                <option value="{{ $fournisseur->raison_sociale }} ">
                                    {{ $fournisseur->raison_sociale }}
                                </option>

                            @endforeach
                       
                        </select>
                        
                       
                            @error('code_fournisseur')
                                <span class="text-danger">{{$message}}</span>
                            @enderror

                           
                    </div>

                </div>
            </div>

            <div class="card-footer">
                <button type="submit" class="btn btn-success btn-user float-right mb-3">Enregistrer</button>
                <a class="btn btn-primary float-right mr-3 mb-3" href="{{ route('achats.index') }}">Annuler</a>
            </div>
        </form>
    </div>

</div>

@include('common.footer')
@endsection

@section('scripts')
<script>

</script>

@endsection