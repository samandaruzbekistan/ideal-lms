<?php

namespace App\Http\Controllers;

use App\Repositories\AdminRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function __construct(protected AdminRepository $adminRepository)
    {
    }

    public function login(Request $request){
        $admin = $this->adminRepository->getAdmin($request->login);
        if (!$admin){
            return response()->json([
                'success' => false
            ], 404);
        }
        if (Hash::check($request->input('password'), $admin->password)) {
            $admin->tokens()->delete();
            $token = $admin->createToken($request->login)->plainTextToken;
            return response()->json([
                'success' => true,
                'data' => $admin,
                'token' => $token
            ], 200);
        }
        else{
            return response()->json([
                'success' => false
            ], 404);
        }
    }

    public function home(){
        return response()->json([
            'add' => 44
        ]);
    }
}
