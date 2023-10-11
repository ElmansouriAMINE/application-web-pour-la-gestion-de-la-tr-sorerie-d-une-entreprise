<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fournisseur;
use App\Exports\FournisseursExport;
use App\Imports\FournisseursImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
class FournisseurController extends Controller
{
    public function index()
    {
        $fournisseurs =DB::table('fournisseurs')->paginate(10);
        
        return view('fournisseurs.index', ['fournisseurs' => $fournisseurs]);
    }
    
    /**
     * Create Bancaire 
     * @param 
     * @return Array $bancaire
     * @author  Amine ELMANSOURI
     */
    public function create()
    {
        
        // return redirect()->back()
        // ->with('error','username is invalid')
        // ->withInput();
        return view('fournisseurs.add');
    }

    /**
     * Store Bancaire
     * @param Request $request
     * @return View Bancaire
     * @author  Amine ELMANSOURI
     */
    public function store(Request $request)
    {
        // Validations
        //nr_de_reglement date mode reference date_echeance montant_regle code_client
        $request->validate([
            'code'    => 'required',
            'raison_sociale'     => 'required',
        ]);

        DB::beginTransaction();
        try {

            // Store Data
            if(!Fournisseur::where('code', '=',$request->code)->exists()) {
            $fournisseur = Fournisseur::create([
                'code'         => $request->code,
                'raison_sociale' => $request->raison_sociale,
               
            ]);
        
            

            

            // Commit And Redirected To Listing
            DB::commit();
            
            return redirect()->route('fournisseurs.index')->with('success','fournisseur Created Successfully.');
        
        }
            else{
                return redirect()->route('fournisseurs.index')->with('error','Ce code déjà existe');
            }
            

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

  
    

    /**
     * Edit Bancaire
     * @param Integer $bancaire
     * @return Collection $bancaire
     * @author  Amine ELMANSOURI
     */
    public function edit(Fournisseur $fournisseur)
    {
        
        return view('fournisseurs.edit')->with([
            'fournisseur'  => $fournisseur
        ]);
    }

    /**
     * Update Bancaire
     * @param Request $request, Bancaire $bancaire
     * @return View Bancaires
     * @author  Amine ELMANSOURI
     */
    public function update(Request $request, Fournisseur $fournisseur)
    {
        // Validations
        $request->validate([
            'code'    => 'required',
            'raison_sociale'     => 'required',
        ]);

        DB::beginTransaction();
        try {
          
            // Store Data
            $fournisseur_updated = Fournisseur::whereId($fournisseur->id)->update([
                'code'         => $request->code,
                'raison_sociale' => $request->raison_sociale,
            ]);

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('fournisseurs.index')->with('success','fournisseur Updated Successfully.');

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    /**
     * Delete Bancaire
     * @param Bancaire $bancaire
     * @return Index Bancaires
     * @author Amine ELMANSOURI
     */
    public function delete(Fournisseur $fournisseur)
    {
        DB::beginTransaction();
        try {
            // Delete Bancaire
            Fournisseur::whereId($fournisseur->id)->delete();

            DB::commit();
            return redirect()->route('fournisseurs.index')->with('success', 'fournisseur Deleted Successfully!.');

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Import Bancaires 
     * @param Null
     * @return View File
     */
    public function importFournisseurs()
    {
        return view('fournisseurs.import');
    }

    public function uploadFournisseurs(Request $request)
    {
        Excel::import(new FournisseursImport, $request->file);
        
        return redirect()->route('fournisseurs.index')->with('success', 'fournisseur Imported Successfully');
    }

    public function export() 
    {
        return Excel::download(new FournisseursExport, 'fournisseurs.xlsx');
    }



  



}
