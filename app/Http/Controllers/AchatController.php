<?php

namespace App\Http\Controllers;
use \Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Models\Achat;
use App\Exports\AchatsExport;
use App\Imports\AchatsImport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;


class AchatController extends Controller
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
     * List Achat
     * @param 
     * @return Array $achat
     * @author  Amine ELMANSOURI
     */
    public function index(Request $request)
    {
        $achats =DB::table('achats')->paginate(10);
        
        
        
        if(request()->ajax())
            {
            if(!empty($request->from_date))
            {
                $query=DB::table('achats');
            
                $query->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'>=',Carbon::createFromFormat('d/m/Y', $request->from_date));
                $query->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'<=',Carbon::createFromFormat('d/m/Y', $request->to_date));
                $query->where('status',$request->filter_status);
            $data =$query->get();
            // $montantw->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'>=',Carbon::createFromFormat('d/m/Y', $request->from_date));
            //     $montantw->whereDate(DB::raw("STR_TO_DATE(date_echeance, '%d/%m/%Y')"),'<=',Carbon::createFromFormat('d/m/Y', $request->to_date));
            //     $montantw->where('status',$request->filter_status);
            //     $montantw->addSelect(DB::raw("SUM(montant_regle) as montant"));
            //     $montantTotal=$montantw->get();
            
            }
            else
            {
            $data = DB::table('achats')
                ->get();
            // $montantw->addSelect(DB::raw("SUM(montant_regle) as montant"));
            // $montantTotal=$montantw->get();

            }
            return datatables()->of($data)

            
            ->editColumn('status', function ($achat) {
                if ($achat->status == 0) return '<span class="badge badge-danger">En attente</span>';
                elseif ($achat->status == 1) return '<span class="badge badge-success">validé</span>';
                
            })
            ->editColumn('code_fournisseur', function ($achat) {
                $fournisseurs =DB::table('fournisseurs')->get();
                foreach($fournisseurs as $fournisseur){
                    if ($achat->code_fournisseur == $fournisseur->code) return $fournisseur->raison_sociale;
                }
                if ($achat->code_fournisseur) return $achat->code_fournisseur;
            })
            
            ->addColumn('action', function ($achat) {
                $actionBtn='';

                
                if ($achat->status == 0){
                    
                    $actionBtn.='
                    <a href="' . route('achats.status', ['achat_id' => $achat->id,'status' => 1]) .'"
                        class="btn btn-success m-8">
                        <i class="fa fa-check"></i>
                    </a>';
                    
                }
                
                $actionBtn.='
                <a href="' . route('achats.status', ['achat_id' => $achat->id,'status' => 0]) .'"
                    class="btn btn-danger m-8">
                    <i class="fa fa-pause-circle" aria-hidden="true"></i>
                </a>';

                return '<td style="display: flex ">'.$actionBtn.'</td>';
            
                    
                

                
                
                
                
            //     '<td style="display: flex">
            //     <a href= "' . route('achats.edit', ['achat' => $achat->id]) .'"
            //         class="btn btn-primary m-2">
            //         <i class="fa fa-pen"></i>
            //     </a>
            //     <a class="btn btn-danger m-2" href="#" data-toggle="modal" data-target="#deleteModal" data-attr="{{ route("achats.destroy", '.$achat->id.') }}">
            //         <i class="fas fa-trash"></i>
            //     </a>
            // </td>';

            
                              

                
            })
            
            ->rawColumns(['status', 'action'])

            ->make(true);
            }

        


        return view('achats.index', ['achats' => $achats]);
    }
    
    /**
     * Create Achat 
     * @param 
     * @return Array $achat
     * @author  Amine ELMANSOURI
     */
    public function create()
    {
        
        $fournisseurs=DB::table('fournisseurs')->get();
        
        return view('achats.add',['fournisseurs' => $fournisseurs]);
    }

    /**
     * Store Achat
     * @param Request $request
     * @return View Achat
     * @author  Amine ELMANSOURI
     */
    public function store(Request $request)
    {
        // Validations
        //nr_de_reglement date mode reference date_echeance montant_regle code_fournisseur
        $debit_id=DB::table('achats')->count();
        $request->validate([
            'mode'    =>'required',
            'date'     => 'required',
            'date_echeance'       =>  'required',
            'montant_regle'       =>  'required',
            'code_fournisseur'     => 'required',
           
        ]);

        DB::beginTransaction();
        try {

            // Store Data
            
            $achat = Achat::create([
                'nr_de_reglement'    => 'R22-00'.($debit_id+1),
                'date'      => Carbon::createFromFormat('Y-m-d', $request->date)->format('d/m/Y'),
                'mode'         => $request->mode,
                'reference' => $request->reference?$request->reference:'',
                'date_echeance'       =>Carbon::createFromFormat('Y-m-d', $request->date_echeance)->format('d/m/Y'),
                'montant_regle'       => $request->montant_regle,
                'code_fournisseur'      => $request->code_fournisseur,
                'status'        => 0,
            ]);
            

            

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('achats.index')->with('success','purchase settlement Created Successfully.');

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

  
    

    /**
     * Edit Achat
     * @param Integer $achat
     * @return Collection $achat
     * @author  Amine ELMANSOURI
     */
    public function edit(Achat $achat)
    {
        
        return view('achats.edit')->with([
            'achat'  => $achat
        ]);
    }

    /**
     * Update Achat
     * @param Request $request, Achat $achat
     * @return View Achats
     * @author  Amine ELMANSOURI
     */
    public function update(Request $request, Achat $achat)
    {
        // Validations
        $debit_id=DB::table('achats')->count();
        $request->validate([
            'nr_de_reglement'    => 'required',
            'date'     => 'required',
            'reference' => 'required',
            'date_echeance'       =>  'required',
            'montant_regle'       =>  'required',
            'code_fournisseur'     => 'required',
            
        ]);

        DB::beginTransaction();
        try {
          
            // Store Data
            $achat_updated = Achat::whereId($achat->id)->update([
                'nr_de_reglement'    => 'R22-00'.($achat->id),
                'date'      => Carbon::createFromFormat('Y-m-d', $request->date)->format('d/m/Y'),
                'mode'         => $request->mode ? $request->mode : '',
                'reference' => $request->reference,
                'date_echeance'       => Carbon::createFromFormat('Y-m-d', $request->date_echeance)->format('d/m/Y'),
                'montant_regle'       => $request->montant_regle,
                'code_fournisseur'      => $request->code_fournisseur,
                'status'        => 0,
            ]);

            // Commit And Redirected To Listing
            DB::commit();
            return redirect()->route('achats.index')->with('success','purchase settlement Updated Successfully.');

        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    /**
     * Delete Achat
     * @param Achat $achat
     * @return Index Achats
     * @author Amine ELMANSOURI
     */
    public function delete(Achat $achat)
    {
        DB::beginTransaction();
        try {
            // Delete Achat
            Achat::whereId($achat->id)->delete();

            DB::commit();
            return redirect()->route('achats.index')->with('success', 'Achat Deleted Successfully!.');

        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }

    /**
     * Import Achats 
     * @param Null
     * @return View File
     */
    public function importAchats()
    {
        return view('achats.import');
    }

    public function uploadAchats(Request $request)
    {
        Excel::import(new AchatsImport, $request->file);
        
        return redirect()->route('achats.index')->with('success', 'Achat Imported Successfully');
    }

    public function export() 
    {
        return Excel::download(new AchatsExport, 'achats.xlsx');
    }

    public function charts(){
        return view('achats.achatChart');
    }

    public function achatsChart(Request $request)
    {

        $group1=$request->query('group','month');
        $query=Achat::select([
                    DB::raw("SUM(montant_regle) as montant"),
                    DB::raw('COUNT(*) as count'),
                ])
                ->groupBy([
                    'label',
                ])
                ->orderBy('label');
        $now = Carbon::now()->format('d/m/Y');
        

        switch($group1){
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
            'group' =>$group1,
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
                    'label' => "Nombre des réglements d'achats effectués",
                    'borderColor' =>'red',
                    'backgroundColor'=> 'red',
                    'fill'=>false,
                    'data' => array_values($count),
                ],
                
                
                
                
                ]
            ];

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



    ///for tables

    public function achatsTable1(Request $request)
    {
       
        $group=$request->query('group','month');
        $query=Achat::select([
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

    public function achatsTable2(Request $request)
    {
       
        $group=$request->query('group','month');
        $query=Achat::select([
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

  


    // public function achatsChart(Request $request)
    // {
    //     // SELECT DATE_FORMAT(STR_TO_DATE(`date_echeance`, '%d/%m/%Y'), '%c') FROM `achats`
    //     $entries=Achat::select([
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
    //                 'label' => "Nombres de reglements d'achats",
    //                 'borderColor' =>'red',
    //                 'backgroundColor'=> 'red',
    //                 'data' => array_values($count),
    //             ],
                
                
                
                
    //             ]
    //         ];

    // }



    public function updateStatus($achat_id, $status)
    {
        // Validation
        $validate = Validator::make([
            'achat_id'   => $achat_id,
            'status'    => $status
        ], [
            'achat_id'   =>  'required|exists:achats,id',
            'status'    =>  'required|in:0,1',
        ]);

        // If Validations Fails
        if($validate->fails()){
            return redirect()->route('achats.index')->with('error', $validate->errors()->first());
        }

        try {
            DB::beginTransaction();

            // Update Status
            Achat::whereId($achat_id)->update(['status' => $status]);

            // Commit And Redirect on index with Success Message
            DB::commit();
            return redirect()->route('achats.index')->with('success','Achat Status Updated Successfully!');
        } catch (\Throwable $th) {

            // Rollback & Return Error Message
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }
    
    


}



