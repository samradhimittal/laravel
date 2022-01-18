<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
 
use App\Models\Interest;
 
use Datatables;
 
class InterestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {
            return datatables()->of(Interest::select('*'))
             ->editColumn('created_at', function ($request) {
                return $request->created_at->format('Y-m-d'); // human readable format
            })
            ->addColumn('action', 'Interest-action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
        }
        $title = "Interest";
        return view('interests',compact('title'));
    }
      
      
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  
 
        $InterestId = $request->id;
 
        $Interest   =   Interest::updateOrCreate(
                    [
                     'id' => $InterestId
                    ],
                    [
                    'name' => $request->name, 
                    ]);    
                         
        return Response()->json($Interest);
 
    }
      
      
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Interest  $Interest
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {   
        $where = array('id' => $request->id);
        $Interest  = Interest::where($where)->first();
      
        return Response()->json($Interest);
    }
      
      
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Interest  $Interest
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $Interest = Interest::where('id',$request->id)->delete();
      
        return Response()->json($Interest);
    }
}