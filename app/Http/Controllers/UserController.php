<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; 
use App\Models\Interest;
use App\Models\UserInterest;
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
            ->editColumn('name', function ($request) {
                return $request->first_name.' '.$request->last_name; // human readable format
            })
            ->addColumn('interests', function (User $user) {
                    return $user->interest->map(function($interest) {
                        return $interest->name;
                    })->implode(',');
            })
            ->addColumn('action', 'user-action')
            ->rawColumns(['action'])
            ->addIndexColumn()
            ->make(true);
            

        }
        $title = "User";
        $interests = Interest::all();
        return view('user',compact('title','interests'));
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
            $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name'  => ['required', 'string', 'max:255'],
                'email'      => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$userId],
                "interests"  =>  ['required',  'array','min:1'],
            ]);
        }else{
            $validator = Validator::make($request->all(), [
                'first_name' => ['required', 'string', 'max:255'],
                'last_name'  => ['required', 'string', 'max:255'],
                'email'      => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password'   =>  ['required', 'string', 'min:8', 'confirmed'],
                "interests"  =>  ['required',  'array','min:1'],
            ]);
        }
        

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }



        if($request->hasFile('avatar')){
            $avatar = $request->file('avatar');
            $filename = time() . '.' . $avatar->getClientOriginalExtension();
            $save_path =  public_path('/uploads/avatars/');
            if (!file_exists($save_path)) {
                mkdir($save_path, 666, true);
            }
            Image::make($avatar)->resize(300, 300)->save($save_path.$filename);
        }else{
            $filename = $request->profile_pic;
        }
        
        

        $user = User::updateOrCreate([
                     'id' => $userId
                    ],
                    [
                        'first_name' => $request->first_name,
                        'last_name' => $request->last_name,
                        'email' => $request->email,
                        'password' => Hash::make($request->password),
                        'profile_pic' => $filename
                    ]);

        $userId = $user->id;

        foreach ($request->interests as $interest) {
           $userInterest  = new UserInterest();
           $userInterest->interest_id = $interest;
           $userInterest->user_id = $userId;
           $userInterest->save();
        }
   
        $user = User::with('interest')->first();                 
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
        $user  = User::with(['interest' => function($query){
             $query->select('interest_id');
        }])->where($where)->first();
        
        $interests = Interest::all();
        $returnHTML = view('edit-user',compact('user','interests'))->render();
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