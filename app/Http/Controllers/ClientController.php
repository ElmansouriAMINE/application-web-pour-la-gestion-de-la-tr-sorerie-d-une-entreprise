<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Exports\ClientsExport;
use App\Imports\ClientsImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ClientController extends Controller
{
    public function index()
    {
        $clients =DB::table('clients')->paginate(10);
        return view('clients.index', ['clients' => $clients]);
    }
    
    /**
     * Create Bancaire 
     * @param 
     * @return Array $bancaire
     * @author  Amine ELMANSOURI
     */
    public function create()
    {
        
       
        return view('clients.add');
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
            if(!Client::where('code', '=',$request->code)->exists()) {
            $client = client::create([
                'code'         => $request->code,
                'raison_sociale' => $request->raison_sociale,
               
            ]);
            

            

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('clients.index')->with('success','client Created Successfully.');
        
        }
            else{
                return redirect()->route('clients.index')->with('error','Ce code déjà existe');
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
    public function edit(Client $client)
    {
        
        return view('clients.edit')->with([
            'client'  => $client
        ]);
    }

    /**
     * Update Bancaire
     * @param Request $request, Bancaire $bancaire
     * @return View Bancaires
     * @author  Amine ELMANSOURI
     */
    public function update(Request $request, Client $client)
    {
        // Validations
        $request->validate([
            'code'    => 'required',
            'raison_sociale'     => 'required',
        ]);

        DB::beginTransaction();
        try {
          
            // Store Data
            $client_updated = Client::whereId($client->id)->update([
                'code'         => $request->code,
                'raison_sociale' => $request->raison_sociale,
            ]);

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('clients.index')->with('success','client Updated Successfully.');

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
    public function delete(Client $client)
    {
        DB::beginTransaction();
        try {
            // Delete Bancaire
            Client::whereId($client->id)->delete();

            DB::commit();
            return redirect()->route('clients.index')->with('success', 'client Deleted Successfully!.');

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
    public function importclients()
    {
        return view('clients.import');
    }

    public function uploadclients(Request $request)
    {
        Excel::import(new clientsImport, $request->file);
        
        return redirect()->route('clients.index')->with('success', 'client Imported Successfully');
    }

    public function export() 
    {
        return Excel::download(new clientsExport, 'clients.xlsx');
    }

}
