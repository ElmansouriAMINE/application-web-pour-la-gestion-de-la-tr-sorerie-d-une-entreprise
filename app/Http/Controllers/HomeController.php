<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
    //   
        // $query=DB::table('achats');
        // $query->addSelect(DB::raw("SUM(montant_regle) as montant"));
        // $query->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'>=',Carbon::now()->startOfYear());
        // $query->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'<=',Carbon::now()->endOfYear());
        // $query->where('status',1);
        // $data2=$query->get();
        

        
        
      
        // $montant =[];

        // foreach($data2 as $entry){
        //     $montant[$entry->montant]=(double)$entry->montant;
        // }
        // $montant=array_values($montant);
        
        // foreach($montant as $month => $name){
        //         if(!array_key_exists($month,$montant)){
        //             $montant[$month]=0;
        //         }
                
        //     }
        // ksort($montant);
        
        // dd($data1);
       
        $fournisseurs =DB::table('fournisseurs')->get();
        $clients =DB::table('clients')->get();

    // 
        $tomorrow =Carbon::now()->tomorrow('Africa/Casablanca')->format('d/m/Y');
        // Carbon::tomorrow()->format('d/m/Y');
        // 'nr_de_reglement','date','mode','reference','date_echeance','montant_regle','code_fournisseur'
        $reg=DB::table('achats')->where('date_echeance','=',$tomorrow)->get();
        $regbancaire=DB::table('bancaires')->where('date_echeance','=',$tomorrow)->get();

        return view('home', ['reg' => $reg , 'regbancaire' => $regbancaire,'fournisseurs' =>$fournisseurs,'clients' => $clients]);
    }

    /**
     * User Profile
     * @param Nill
     * @return View Profile
     * @author Shani Singh
     */
    public function getProfile()
    {
        return view('profile');
    }

    /**
     * Update Profile
     * @param $profileData
     * @return Boolean With Success Message
     * @author Shani Singh
     */
    public function updateProfile(Request $request)
    {
        #Validations
        $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'mobile_number' => 'required|numeric|digits:10',
        ]);

        try {
            DB::beginTransaction();
            
            #Update Profile Data
            User::whereId(auth()->user()->id)->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile_number' => $request->mobile_number,
            ]);

            #Commit Transaction
            DB::commit();

            #Return To Profile page with success
            return back()->with('success', 'Profile Updated Successfully.');
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Change Password
     * @param Old Password, New Password, Confirm New Password
     * @return Boolean With Success Message
     * @author Shani Singh
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        try {
            DB::beginTransaction();

            #Update Password
            User::find(auth()->user()->id)->update(['password'=> Hash::make($request->new_password)]);
            
            #Commit Transaction
            DB::commit();

            #Return To Profile page with success
            return back()->with('success', 'Password Changed Successfully.');
            
        } catch (\Throwable $th) {
            DB::rollBack();
            return back()->with('error', $th->getMessage());
        }
    }
}
