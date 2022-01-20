<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 
use App\Models\UserDetail;
use App\Models\User;
use Datatables;
use Image;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax()) {

            return datatables()->of(User::select('*'))
            ->editColumn('created_at', function ($request) {
                return $request->created_at->format('Y-m-d'); // human readable format
            })
            ->editColumn('first_name', function ($request) {
                return $request->first_name; // human readable format
            })
            ->editColumn('last_name', function ($request) {
                return $request->last_name; // human readable format
            })
            ->addColumn('type', function (User $user) {
                    return $user->userDetail->type;
            })
            ->addColumn('amount', function (User $user) {
                    return $user->userDetail->currency.$user->userDetail->amount;
            })
            ->addColumn('date', function (User $user) {
                    return $user->userDetail->date;
            })
            ->addColumn('action', 'user-action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
            

        }
        $title = "User";
        return view('user',compact('title'));
    }
      
      
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {  

        $userId = $request->id;
        if(isset($userId) && $userId!=''){
            $emailValid = ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$userId];
            $passValid = "";
        }else{
            $emailValid = ['required', 'string', 'email', 'max:255', 'unique:users'];  
            $passValid = ['required', 'string', 'min:8', 'confirmed'];          
        }
        
        $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name'  => ['required', 'string', 'max:255'],
                'email'      =>  $emailValid,
                'password'   =>  $passValid,
                "type"       =>  ['required',  'in:Expense,Income'],
                "amount"     =>  ['required',  'numeric'],
                "date"       =>  ['required',  'date'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }


        $user = User::updateOrCreate([
                     'id' => $userId
                    ],
                    [
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password)              
                    ]);

        $userId = $user->id;

        $user = UserDetail::updateOrCreate([
                     'user_id' => $userId
                    ],
                    [
                        'type' => $request->type,
                        'date' => date('Y-m-d',strtotime($request->date)),
                        'amount' => $request->amount,
                    ]);
  
        $user = User::with('userDetail')->first();                 
        return Response()->json($user);
 
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
        $user  = User::with('userDetail')->where($where)->first();   
        $returnHTML = view('edit-user',compact('user'))->render();
        return response()->json(array('success' => true, 'html'=>$returnHTML));
    }
      
      
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Interest  $Interest
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = User::where('id',$request->id)->delete();
      
        return Response()->json($user);
    }
}