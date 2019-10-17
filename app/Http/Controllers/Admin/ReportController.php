<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as Controller;
use Illuminate\Http\Request;

use App\Repository\DevReportsRepository;
use App\User;

class ReportController extends Controller
{
    private $devReports;
       
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(DevReportsRepository $devReports)
    {
        $this->middleware('permission:admin panel');  
        $this->middleware('permission:show dev_reports', ['only' => ['devIndex', 'devDayReportsShow']]);
        
        $this->devReports = $devReports;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function devIndex(Request $request)
    {      
        $this->validate($request, [
            'date' => 'nullable|date|date_format:Y-m-d'
        ]);
        
        $date = $request->get('date') ?? date('Y-m-d'); 
                
        $devByReports = $this->devReports->all(['date' => $date])->unique('user_id')->pluck('user_id')->toArray();
        $devActive = User::where('status', true)->role('Developer')->get()->pluck('id')->toArray();
        
        $developers = User::whereIn('id', array_merge($devByReports, $devActive))->get()
                                ->each(function ($developer) use($date)  {                                     
                                    $developer->time = $this->devReports->getFullTime(['user_id' => $developer->id, 'date' => $date]);
                                });

        $data = [
                'developer' => $developers->first(),
                'reports'   => $developers->first() ? $this->devReports->all(['user_id' => $developers->first()->id, 'date' => $date]) : []
            ];
        
        return view('admin.reports.developers.index', compact('date', 'developers', 'data'));
    }
    
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function devDayReportsShow(Request $request, $id)
    {                
        $date = $request->get('date') ?? date('Y-m-d'); 
        
        $developer = User::where('id', $id)->first();
        if(!$developer)
            return response()->json(['errors' => ['Not found']], 422);
        
        $developer->time = $this->devReports->getFullTime(['user_id' => $developer->id, 'date' => $date]);
        
        $data = [
                'developer' => $developer,
                'reports'   => $this->devReports->all(['user_id' => $developer->id, 'date' => $date])
            ];
        
        return view('admin.reports.developers.reports', compact('data'));
    }
    
}
