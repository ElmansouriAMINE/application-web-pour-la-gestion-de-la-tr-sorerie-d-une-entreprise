<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bancaire;
use App\Exports\BancairesExport;
use App\Imports\BancairesImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;


class BancaireController extends Controller
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
     * List Bancaire
     * @param 
     * @return Array $bancaire
     * @author  Amine ELMANSOURI
     */
    public function index(Request $request)
    {
        $bancaires =DB::table('bancaires')->paginate(10);



        if(request()->ajax())
            {
            if(!empty($request->from_date))
            {
                $query=DB::table('bancaires');
            
                $query->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'>=',Carbon::createFromFormat('d/m/Y', $request->from_date));
                $query->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'<=',Carbon::createFromFormat('d/m/Y', $request->to_date));
                $query->where('status',$request->filter_status);
            $data =$query->get();
            }
            else
            {
            $data = DB::table('bancaires')
                ->get();
            }
            return datatables()->of($data)

            ->editColumn('status', function ($bancaire) {
                if ($bancaire->status == 0) return '<span class="badge badge-danger">En attente</span>';
                elseif ($bancaire->status == 1) return '<span class="badge badge-success">validé</span>';
                
            })
            ->editColumn('code_client', function ($bancaire) {
                $clients =DB::table('clients')->get();
                foreach($clients as $client){
                    if ($bancaire->code_client == $client->code) return $client->raison_sociale;
                }
                if ($bancaire->code_client) return $bancaire->code_client;
            })
            
            ->addColumn('action', function ($bancaire) {
                $actionBtn='';

                
                if ($bancaire->status == 0){
                    
                    $actionBtn.='
                    <a href="' . route('bancaires.status', ['bancaire_id' => $bancaire->id,'status' => 1]) .'"
                        class="btn btn-success m-8">
                        <i class="fa fa-check"></i>
                    </a>';
                    
                }
                
                $actionBtn.='
                <a href="' . route('bancaires.status', ['bancaire_id' => $bancaire->id,'status' => 0]) .'"
                    class="btn btn-danger m-8">
                    <i class="fa fa-pause-circle" aria-hidden="true"></i>
                </a>';

                return '<td style="display: flex ">'.$actionBtn.'</td>';
                
            })

            ->rawColumns(['status', 'action'])
            ->make(true);
            }


        return view('bancaires.index', ['bancaires' => $bancaires]);
    }
    
    /**
     * Create Bancaire 
     * @param 
     * @return Array $bancaire
     * @author  Amine ELMANSOURI
     */
    public function create()
    {
        
        $clients=DB::table('clients')->get();
        return view('bancaires.add',['clients' =>$clients]);
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
        $debit_id=DB::table('bancaires')->count();
        $request->validate([
            'mode'   =>'required',
            'date'     => 'required',
            'date_echeance'       =>  'required',
            'montant_regle'       =>  'required',
            'code_client'     => 'required',
            
        ]);

        DB::beginTransaction();
        try {

            // Store Data
            $bancaire = Bancaire::create([
                'nr_de_reglement'    => 'R22-00'.($debit_id+1),
                'date'      => Carbon::createFromFormat('Y-m-d', $request->date)->format('d/m/Y'),
                'mode'         => $request->mode,
                'reference' => $request->reference?$request->reference:'',
                'date_echeance'       =>Carbon::createFromFormat('Y-m-d', $request->date_echeance)->format('d/m/Y'),
                'montant_regle'       => $request->montant_regle,
                'code_client'      => $request->code_client,
                'status'        => 0,
            ]);
            

            

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('bancaires.index')->with('success','bank settlement Created Successfully.');

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
    public function edit(Bancaire $bancaire)
    {
        
        return view('bancaires.edit')->with([
            'bancaire'  => $bancaire
        ]);
    }

    /**
     * Update Bancaire
     * @param Request $request, Bancaire $bancaire
     * @return View Bancaires
     * @author  Amine ELMANSOURI
     */
    public function update(Request $request, Bancaire $bancaire)
    {
        // Validations
        $request->validate([
            'date'     => 'required',
            'reference' => 'required',
            'date_echeance'       =>  'required',
            'montant_regle'       =>  'required',
            'code_client'     => 'required',
            
        ]);

        DB::beginTransaction();
        try {
          
            // Store Data
            $bancaire_updated = Bancaire::whereId($bancaire->id)->update([
                'nr_de_reglement'    => 'R22-00'.($bancaire->id),
                'date'      => Carbon::createFromFormat('Y-m-d', $request->date)->format('d/m/Y'),
                'mode'         => $request->mode ? $request->mode : '',
                'reference' => $request->reference,
                'date_echeance'       => Carbon::createFromFormat('Y-m-d', $request->date_echeance)->format('d/m/Y'),
                'montant_regle'       => $request->montant_regle,
                'code_client'      => $request->code_client,
                'status'        => 0,
            ]);

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('bancaires.index')->with('success','bank settlement Updated Successfully.');

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
    public function delete(Bancaire $bancaire)
    {
        DB::beginTransaction();
        try {
            // Delete Bancaire
            Bancaire::whereId($bancaire->id)->delete();

            DB::commit();
            return redirect()->route('bancaires.index')->with('success', 'Bancaire Deleted Successfully!.');

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
    public function importBancaires()
    {
        return view('bancaires.import');
    }

    public function uploadBancaires(Request $request)
    {
        Excel::import(new BancairesImport, $request->file);
        
        return redirect()->route('bancaires.index')->with('success', 'Reglement Bancaire Imported Successfully');
    }

    public function export() 
    {
        return Excel::download(new BancairesExport, 'bancaires.xlsx');
    }

    public function charts(){
        return view('bancaires.bancaireChart');
    }

    public function bancairesChart(Request $request)
    {

        $group=$request->query('group','month');
        $query=Bancaire::select([
                    DB::raw("SUM(montant_regle) as montant"),
                    DB::raw('COUNT(*) as count'),
                ])
                ->groupBy([
                    'label',
                ])
                ->orderBy('label');
        $now = Carbon::now()->format('d/m/Y');
        

        switch($group){
            case 'day':
                $query->addSelect(DB::raw("DATE(STR_TO_DATE(date_echeance, '%d/%m/%Y')) as label"));
                $query->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'>=',Carbon::now()->startOfMonth());
                $query->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'<=',Carbon::now()->endOfMonth());
                break;
            case 'week':
                $query->addSelect(DB::raw("DATE(STR_TO_DATE(date_echeance, '%d/%m/%Y')) as label"));
                $query->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'>=',Carbon::now()->startOfWeek());
                $query->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'<=',Carbon::now()->endOfWeek());
                break;
            case 'year':
                $query->addSelect(DB::raw("YEAR(STR_TO_DATE(date_echeance, '%d/%m/%Y')) as label"));
                $query->whereYear(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'>=',Carbon::now()->subYears(5)->year);
                $query->whereYear(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'<=',Carbon::now()->addYears(4)->year);
                break;
            case 'month':
                $query->addSelect(DB::raw("Month(STR_TO_DATE(date_echeance, '%d/%m/%Y')) as label"));
                $query->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'>=',Carbon::now()->startOfYear());
                $query->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'<=',Carbon::now()->endOfYear());
                // $labels=[
                //     1 => 'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec',
                // ];
            default: 

        }
        // SELECT DATE_FORMAT(STR_TO_DATE(`date_echeance`, '%d/%m/%Y'), '%c') FROM `achats`
        $entries=$query->where('status',1)->get();
        
        $dataset=[];
        $labels=$montant = $count =[];
        
        foreach($entries as $entry){
            $labels[] =$entry->label;
            $montant[$entry->label]=(double)$entry->montant;
            $count[$entry->label]=$entry->count;
        }
        // foreach($labels as $month => $name){
        //     if(!array_key_exists($month,$montant)){
        //         $montant[$month]=0;
        //     }
        //     if(!array_key_exists($month,$count)){
        //         $count[$month] =0;
        //     }
        // }
        // ksort($montant);
        // ksort($count);

        return[
            'group' =>$group,
            'labels' =>array_values($labels),
            'datasets'=>[
                [
                'label' =>'Montant Total Réglé',
                'borderColor' =>'blue',
                'backgroundColor'=> 'blue',
                'fill'=>false,
                'data' => array_values($montant),

                ],
                [
                    'label' => "Nombre des réglements bancaires effectués",
                    'borderColor' =>'red',
                    'backgroundColor'=> 'red',
                    'fill'=>false,
                    'data' => array_values($count),
                ],
                
                
                
                
                ]
            ];


    }



    public function bancairesTable1(Request $request)
    {

        $group=$request->query('group','month');
        $query=Bancaire::select([
                    DB::raw("SUM(montant_regle) as montant"),
                    DB::raw('COUNT(*) as count'),
                ])
                ->groupBy([
                    'label',
                ])
                ->orderBy('label');
        $now = Carbon::now()->format('d/m/Y');
        

        switch($group){
            case 'day':
                $query->addSelect(DB::raw("DATE(STR_TO_DATE(date_echeance, '%d/%m/%Y')) as label"));
                $query->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'>=',Carbon::now()->startOfMonth());
                $query->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'<=',Carbon::now()->endOfMonth());
                break;
            case 'week':
                $query->addSelect(DB::raw("DATE(STR_TO_DATE(date_echeance, '%d/%m/%Y')) as label"));
                $query->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'>=',Carbon::now()->startOfWeek());
                $query->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'<=',Carbon::now()->endOfWeek());
                break;
            case 'year':
                $query->addSelect(DB::raw("YEAR(STR_TO_DATE(date_echeance, '%d/%m/%Y')) as label"));
                $query->whereYear(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'>=',Carbon::now()->subYears(5)->year);
                $query->whereYear(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'<=',Carbon::now()->addYears(4)->year);
                break;
            case 'month':
                $query->addSelect(DB::raw("Month(STR_TO_DATE(date_echeance, '%d/%m/%Y')) as label"));
                $query->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'>=',Carbon::now()->startOfYear());
                $query->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'<=',Carbon::now()->endOfYear());
                // $labels=[
                //     1 => 'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec',
                // ];
            default: 

        }
        // SELECT DATE_FORMAT(STR_TO_DATE(`date_echeance`, '%d/%m/%Y'), '%c') FROM `achats`
        $entries=$query->where('status',1)->get();
        
        $dataset=[];
        $labels=$montant = $count =[];
        $labels=[
            1 => 'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec',
        ];
        foreach($entries as $entry){
            // $labels[] =$entry->label;
            $montant[$entry->label]=(double)$entry->montant;
            $count[$entry->label]=$entry->count;
        }
        foreach($labels as $month => $name){
            if(!array_key_exists($month,$montant)){
                $montant[$month]=0;
            }
            if(!array_key_exists($month,$count)){
                $count[$month] =0;
            }
        }
        ksort($montant);
        ksort($count);

        return[
            'group' =>$group,
            'labels' =>array_values($labels),
            'datasets'=>[
                [
                'label' =>'Total amount paid',
                'borderColor' =>'blue',
                'backgroundColor'=> 'blue',
                'fill'=>false,
                'data' => array_values($montant),

                ],
                [
                    'label' => "Number of purchase settlements",
                    'borderColor' =>'red',
                    'backgroundColor'=> 'red',
                    'fill'=>false,
                    'data' => array_values($count),
                ],
                
                
                
                
                ]
            ];


    }

    public function bancairesTable2(Request $request)
    {

        $group=$request->query('group','month');
        $query=Bancaire::select([
                    DB::raw("SUM(montant_regle) as montant"),
                    DB::raw('COUNT(*) as count'),
                ])
                ->groupBy([
                    'label',
                ])
                ->orderBy('label');
        $now = Carbon::now()->format('d/m/Y');
        

        switch($group){
            case 'day':
                $query->addSelect(DB::raw("DATE(STR_TO_DATE(date_echeance, '%d/%m/%Y')) as label"));
                $query->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'>=',Carbon::now()->startOfMonth());
                $query->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'<=',Carbon::now()->endOfMonth());
                break;
            case 'week':
                $query->addSelect(DB::raw("DATE(STR_TO_DATE(date_echeance, '%d/%m/%Y')) as label"));
                $query->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'>=',Carbon::now()->startOfWeek());
                $query->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'<=',Carbon::now()->endOfWeek());
                break;
            case 'year':
                $query->addSelect(DB::raw("YEAR(STR_TO_DATE(date_echeance, '%d/%m/%Y')) as label"));
                $query->whereYear(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'>=',Carbon::now()->subYears(5)->year);
                $query->whereYear(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'<=',Carbon::now()->addYears(4)->year);
                break;
            case 'month':
                $query->addSelect(DB::raw("Month(STR_TO_DATE(date_echeance, '%d/%m/%Y')) as label"));
                $query->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'>=',Carbon::now()->startOfYear());
                $query->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'<=',Carbon::now()->endOfYear());
                // $labels=[
                //     1 => 'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec',
                // ];
            default: 

        }
        // SELECT DATE_FORMAT(STR_TO_DATE(`date_echeance`, '%d/%m/%Y'), '%c') FROM `achats`
        $entries=$query->where('status',0)->get();
        
        $dataset=[];
        $labels=$montant = $count =[];
        $labels=[
            1 => 'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec',
        ];
        foreach($entries as $entry){
            // $labels[] =$entry->label;
            $montant[$entry->label]=(double)$entry->montant;
            $count[$entry->label]=$entry->count;
        }
        foreach($labels as $month => $name){
            if(!array_key_exists($month,$montant)){
                $montant[$month]=0;
            }
            if(!array_key_exists($month,$count)){
                $count[$month] =0;
            }
        }
        ksort($montant);
        ksort($count);

        return[
            'group' =>$group,
            'labels' =>array_values($labels),
            'datasets'=>[
                [
                'label' =>'Total amount paid',
                'borderColor' =>'blue',
                'backgroundColor'=> 'blue',
                'fill'=>false,
                'data' => array_values($montant),

                ],
                [
                    'label' => "Number of purchase settlements",
                    'borderColor' =>'red',
                    'backgroundColor'=> 'red',
                    'fill'=>false,
                    'data' => array_values($count),
                ],
                
                
                
                
                ]
            ];


    }

  


    // public function bancairesChart(Request $request)
    // {
    //     // SELECT DATE_FORMAT(STR_TO_DATE(`date_echeance`, '%d/%m/%Y'), '%c') FROM `bancaires`
    //     $entries=Bancaire::select([
    //         DB::raw("DATE_FORMAT(STR_TO_DATE(`date_echeance`, '%d/%m/%Y'), '%c') as month"),
    //         // DB::raw('YEEAR(date_echeance) as year'),
    //         DB::raw("SUM(montant_regle) as montant"),
    //         DB::raw('COUNT(*) as count'),
    //     ])
    //     ->groupBy([
    //         'month',
    //     ])
    //     ->orderBy('month')
    //     ->get();
    //     $labels=[
    //         1 => 'Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec',
    //     ];
    //     $dataset=[];
    //     $montant = $count =[];

    //     foreach($entries as $entry){
    //         $montant[$entry->month]=(double)$entry->montant;
    //         $count[$entry->month]=$entry->count;
    //     }
    //     foreach($labels as $month => $name){
    //         if(!array_key_exists($month,$montant)){
    //             $montant[$month]=0;
    //         }
    //         if(!array_key_exists($month,$count)){
    //             $count[$month] =0;
    //         }
    //     }
    //     ksort($montant);
    //     ksort($count);

    //     return[
    //         'labels' =>array_values($labels),
    //         'datasets'=>[
    //             [
    //             'label' =>'Montant Total reglé',
    //             'borderColor' =>'blue',
    //             'backgroundColor'=> 'blue',
    //             'data' => array_values($montant),

    //             ],
    //             [
    //                 'label' => "Nombres de reglements d'bancaires",
    //                 'borderColor' =>'red',
    //                 'backgroundColor'=> 'red',
    //                 'data' => array_values($count),
    //             ],
                
                
                
                
    //             ]
    //         ];

    // }


    public function updateStatus($bancaire_id, $status)
    {
        // Validation
        $validate = Validator::make([
            'bancaire_id'   => $bancaire_id,
            'status'    => $status
        ], [
            'bancaire_id'   =>  'required|exists:bancaires,id',
            'status'    =>  'required|in:0,1',
        ]);

        // If Validations Fails
        if($validate->fails()){
            return redirect()->route('bancaires.index')->with('error', $validate->errors()->first());
        }

        try {
            DB::beginTransaction();

            // Update Status
            Bancaire::whereId($bancaire_id)->update(['status' => $status]);

            // Commit And Redirect on index with Success Message
            DB::commit();
            return redirect()->route('bancaires.index')->with('success','Bancaire Status Updated Successfully!');
        } catch (\Throwable $th) {

            // Rollback & Return Error Message
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    
    


}