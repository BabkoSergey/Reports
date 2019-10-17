<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;
use App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use Dompdf\Dompdf;
use Dompdf\Options;
use Dompdf\Adapter\CPDF;
use App\Exports\EstimateExport;
use Maatwebsite\Excel\Facades\Excel;

use App\Events\editEstimateDevTiming;

use App\Repository\EstimateRepository;
use App\Rules\CheckResourceTypesRule;

class EstimateController extends Controller
{
    private $estimates;
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(EstimateRepository $estimates)
    {
        $this->middleware('permission:admin panel');
        $this->middleware('permission:show estimates', ['only' => ['index', 'projectsDTAjax', 'show']]);
        $this->middleware('permission:add estimates|append resources', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit estimates', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete estimates', ['only' => ['destroy']]);
        
        $this->estimates = $estimates;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.estimate.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.estimate.create_form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validateRules = [
                'name' => 'required|unique:estimates,name',
                'status' => 'nullable|boolean',
            ];
        
        $input = $request->all();
        $validator = Validator::make($input, $validateRules);
        
        if ($validator->fails())
            return response()->json(['error' => $validator->errors()], 422);
        
        $estimate = $this->estimates->create($input);
        
        if(!$estimate)
            return response()->json(['error'=>['form' => __('Error saving form')]], 422);
        
        $estimate->refresh();
        $estimate->note = $estimate->note ? mb_strimwidth(strip_tags($estimate->note), 0, 250, "...") : null;
        $estimate->view = __($estimate->view);
           
        return response()->json(['success' => __('Estimate added successfully'), 'estimate' => $estimate, 'type' => 'store', 'resource' => 'estimate']);    
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $estimate = $this->estimates->find($id);
        
        if(!$estimate)
            return redirect()->route('estimates.index')
                        ->with('errores', [__('Record not found')]);
        
        $views = transValues($this->estimates->getTypes());
        
        return view('admin.estimate.show',compact('estimate', 'views'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $estimate = $this->estimates->find($id);
        
        if(!$estimate)
            return response()->json(['error'=>['project' => __('Record not found')]], 422);
        
        $views = transValues($this->estimates->getTypes());
        
        return view('admin.estimate.edit_form',compact('estimate', 'views'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $estimateCheck = $this->estimates->find($id);
        if(!$estimateCheck)
            return response()->json(['error'=>['project' => __('Record not found')]], 422);
           
        $validateRules = [                
                'status'    => 'required|boolean',
                'view'      => ['nullable', new CheckResourceTypesRule($this->estimates->getTypes())],
            ];          
        
        if($estimateCheck->name != $request->get('name')){
            $validateRules['name'] = 'required|unique:estimates,name';
        }
        
        $input = $request->all();
        $validator = Validator::make($input, $validateRules);
        
        if ($validator->fails())
            return response()->json(['error' => $validator->errors()], 422);
        
        $estimate = $this->estimates->update($estimateCheck, $input);
        if(!$estimate)
            return response()->json(['error'=>['form' => __('Error saving form')]], 422);
        
        $estimate->note = $estimate->note ? mb_strimwidth(strip_tags($estimate->note), 0, 250, "...") : null;
        $estimate->view = __($estimate->view);
        
        broadcast(new editEstimateDevTiming($estimate));
        
        return response()->json(['success' => __('Estimate updated successfully'), 'estimate' => $estimate, 'type' => 'update']);  
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function updateViewStatus(Request $request, $id)
    {
        $estimateCheck = $this->estimates->find($id);
        if(!$estimateCheck)
            return redirect()->back()
                        ->with('errores', [__('Record not found')]);
        
        $this->validate($request, ['view' => ['required', new CheckResourceTypesRule($this->estimates->getTypes())]]);
        
        $estimate = $this->estimates->update($estimateCheck, ['view' => $request->get('view')]);      
        
        if(!$estimate)
            return redirect()->back()
                        ->with('errores', [__('Error saving form')]);
        
        broadcast(new editEstimateDevTiming($estimate))->toOthers();
        
        return redirect()->route('estimates.show', ['id' => $estimate->id])
                        ->with('success', __('Estimate updated successfully'));        
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function updateTiming(Request $request, $id)
    {
        $estimateCheck = $this->estimates->find($id);
        if(!$estimateCheck)
            return response()->json(['error'=>['timing' => __('Record not found')]], 422);
        
        $validateRules = [                
                'timing'    => 'nullable|json'
            ];          
        
        $input = $request->all();
        $validator = Validator::make($input, $validateRules);
        
        if ($validator->fails())
            return response()->json(['error' => $validator->errors()], 422);
        
        $estimate = $this->estimates->update($estimateCheck, ['timing' => $request->get('timing')]);      
        
        if(!$estimate)
            return response()->json(['error'=>['timing' => __('Error saving form')]], 422);
        $estimate->refresh();
        
        broadcast(new editEstimateDevTiming($estimate))->toOthers();
        
        return response()->json(['success' => 'ok']);  
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!$this->estimates->delete($id))
            return response()->json(['error'=>['form' => __('Error delete estimate')]], 422);
                
        return response()->json(['success' => __('Estimate deleted successfully')]);
    }
        
    /**
     * Estimates datatable Ajax fetch
     *
     * @return json $out
     */    
    public function estimatesDTAjax()
    {
        

        if(Auth()->user()->hasAnyPermission(['edit estimates', 'add estimates'])){
            $estimates = $this->estimates->all();            
        }else{
            $estimates = $this->estimates->get(['view'=>'estimate-dev', 'status'=>true]);                        
        }
        
        $out = datatables()->of($estimates)                
                ->editColumn('note', function($estimates) {                    
                    return $estimates->note ? mb_strimwidth(strip_tags($estimates->note), 0, 250, "...") : null;
                }) 
                ->editColumn('view', function($estimates) {                    
                    return __($estimates->view);
                }) 
                ->addColumn('actions', '')
                ->toJson();

        return $out;
    }
    
    /**
     * Display the specified PDF resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function createPDF($id)
    {
        App::setLocale('en');
        
        $estimate = $this->estimates->find($id);
        
        if(!$estimate)
            return redirect()->route('estimates.index')
                        ->with('errores', [__('Record not found')]);
        
        $options = new Options();
        $options->set([
                        'defaultFont'               => 'DejaVu Serif',
                        'isRemoteEnabled'           => true,                        
                    ]);
        
        $pdf = new Dompdf($options);
        
        $pdf->loadHtml(view('admin.estimate.pdf', ['estimate'=>$estimate]));              
        
        $pdf->render();
        
        return $pdf->stream(str_replace(' ', '_', $estimate->name).'-'.date('y-m-d-H:i:s'), array("Attachment" => 0));        
    }
    
    /**
     * Display the specified PDF resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function createXLS($id)
    {
        App::setLocale('en');
        
        $estimate = $this->estimates->find($id);
        
        if(!$estimate)
            return redirect()->route('estimates.index')
                        ->with('errores', [__('Record not found')]);
        
        return Excel::download(new EstimateExport($estimate->id), str_replace(' ', '_', $estimate->name).'-'.date('y-m-d-H:i:s').'.xlsx');
                
        
    }
    
}
