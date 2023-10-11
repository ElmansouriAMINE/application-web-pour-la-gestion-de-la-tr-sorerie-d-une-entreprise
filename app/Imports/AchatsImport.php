<?php

namespace App\Imports;
use App\Models\Achat;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;
class AchatsImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function rules(): array
            {
            return [
                '0' => Rule::unique('achats', 'nr_de_reglement')
            ];

            }

    public function customValidationMessages()
            {
                return [
                    '0.unique' => 'Duplicate',
                ];
            }
    public function model(array $row)
    {
        if(!Achat::where('nr_de_reglement', '=','R22-00'.$row['nr_de_reglement'])->exists()) {
        $achat = new Achat([
            "nr_de_reglement" => 'R22-00'.$row['nr_de_reglement'],
            "date" => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date'])->format('d/m/Y'),
            "mode" => $row['mode'],
            "reference" => $row['reference']?$row['reference']:'',
            "date_echeance" => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_echeance'])->format('d/m/Y'),
            "montant_regle" => $row['montant_regle'],
            "code_fournisseur" => $row['code_fournisseur'],
            "status" => 0,
           
        ]);
          
        return $achat;}
    }
}




