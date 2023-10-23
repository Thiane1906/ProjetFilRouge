<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

class UserController extends Controller
{
    // public function register(UserRequest $request)
    // {
    //     $user = User::create([
    //         "name" => $request->name,
    //         "email" => $request->email,
    //         "password" => Hash::make($request->password),
    //         "role"=>$request->role
    //     ]);
    //     return response($user, Response::HTTP_CREATED);
    // }

    public function login(Request $request)
    {
        if (!Auth::attempt($request->only("email", "password"))) {
            return response([
                "message" => "Identifiants invalides"
            ], Response::HTTP_UNAUTHORIZED);
        }
        $user = Auth::user();
        $token = $user->createToken("token")->plainTextToken;
        $cookie = cookie("token", $token, 24 * 60);
        return response([
            "token" => $token,
            "user"=>$user,
        ])->withCookie($cookie);
    }
    
    public function user(Request $request)
    {
        return $request->user();
    }

    public function logout()
    {
        // Auth::logout();
        // Cookie::forget("token");

        
        Auth::guard('sanctum')->user()->tokens()->delete();
        return response([
            "message" => "success"
        ]);
    
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return User::all();
    }

   public function getProfs(){
        $profs= User::where("role","prof")->get();
        return response()->json([
            "data"=>$profs
        ]);
    }

    public function getEtudiants(){
        $etudiants= User::where("role","etudiant")->get();
        return response()->json([
            "data"=>UserResource::collection($etudiants)
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // DB::transaction(function()use($request){
            $user = User::firstOrCreate([
                "name" => $request->name,
                "email" => $request->email,
                "password" => Hash::make($request->password),
                "role"=>"etudiant"
            ]);
                $user->classes()->attach($request->inscription);
            // });
            
                return response()->json([
                    "data"=>[UserResource::make($user)] 
                ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
