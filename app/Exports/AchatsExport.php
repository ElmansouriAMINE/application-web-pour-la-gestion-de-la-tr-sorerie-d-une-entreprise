<?php

namespace App\Exports;
use App\Models\Achat;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AchatsExport implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Achat::all([
            'nr_de_reglement',
            'date',
            'mode',
            'reference',
            'date_echeance',
            'montant_regle',
            'code_fournisseur' 
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
            'CODE_FOURNISSEUR'
        ];
    }
}
