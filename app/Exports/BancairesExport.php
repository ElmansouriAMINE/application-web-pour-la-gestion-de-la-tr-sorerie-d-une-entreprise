<?php

namespace App\Exports;
use App\Models\Bancaire;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
class BancairesExport implements FromCollection,WithHeadings
{

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Bancaire::all([
            'nr_de_reglement',
            'date',
            'mode',
            'reference',
            'date_echeance',
            'montant_regle',
            'code_client' 
        ]);
    }
    public function headings():array
    {
        return[
            'NR_DE_RÉGLEMENT',
            'DATE',
            'MODE',
            'RÉFÉRENCE',
            'DATE_ÉCHÉANCE',
            'MONTANT_RÉGLÉ',
            'CODE_CLIENT'
        ];
    }
    
}



