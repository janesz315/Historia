<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateUserTestRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\PersonalAccessToken;

class UserController extends Controller
{
    public function login(Request $request){
        //beolvassuk az adatokat
        $email = $request->input('email');
        $password = $request->input('password');

        //megkeressük a usert
        $user= User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $password ? $user->password : "")) {
            return response()->json([
                'message' => 'invalid email or password'
            ], 200);
        }

        //Minden oké az adatokkal
        //Kitöröljük a userhez tartozó esetleges tokneket
        // $user->tokens()->delete();

        //Adunk egy tokent
        $user->token = $user->createToken('access')->plainTextToken;
        return response()->json(['user' => $user], options:JSON_UNESCAPED_UNICODE);
    }

    public function logout(Request $request){
        $token = $request->bearerToken();
        $personalAccessToken = PersonalAccessToken::findToken($token);
        if ($personalAccessToken) {
            $personalAccessToken->delete();
            return response()->json(['message' => 'Successfully logged out'], options:JSON_UNESCAPED_UNICODE);
            
        } else {
            return response()->json(['message' => 'Token not found'], options:JSON_UNESCAPED_UNICODE);
        }
        
    }

    public function index()
    {
        $rows = User::all();
        return response()->json(['rows' => $rows], options:JSON_UNESCAPED_UNICODE);
    }

    public function show(int $id)
    {
        $row = User::find($id);
        if ($row) {
            # code...
            $data = ['row' => $row];
        } else {
            $data = [
                'message' => 'Not found',
                'id' => $id
            ];
        }
        return response()->json($data, options:JSON_UNESCAPED_UNICODE);
        
    }

    public function store(StoreUserRequest $request)
    {

        $rows = User::where('email', $request['email'])
            ->get();
        if (count($rows)!=0) {
            # már van ilyen email
            $data = [
                'message' => 'This email already exists',
                'email' => $request['email']
            ];
        } else {
            # még nincs ilyen email
            $rows = User::create(attributes: $request->all());
            $data = ['rows' => $rows];
        }
                    
        return response()->json($data, options:JSON_UNESCAPED_UNICODE);
    }

    public function destroy(int $id)
    {
        $row = User::find($id);
        if ($row) {
            $row->delete();
            $data = [
                'message' => 'Deleted successfully',
                'id' => $id
            ];
        } else {
            $data = [
                'message' => 'Not found',
                'id' => $id
            ];
        }
        return response()->json($data, options:JSON_UNESCAPED_UNICODE);

    }

    public function update(UpdateUserRequest $request, int $id)
{
    $row = User::find($id);

    if (!$row) {
        return response()->json([
            'message' => 'Not found',
            'id' => $id
        ], 404);
    }

    // Ha van e-mail a kérésben ÉS az eltér a meglévőtől, akkor ellenőrizzük az egyediséget
    if ($request->has('email') && $request->email !== $row->email) {
        $emailExists = User::where('email', $request->email)->exists();
        if ($emailExists) {
            return response()->json([
                'message' => 'This email already exists',
                'email' => $request->email
            ], 400);
        }
    }

    // Csak azokat az adatokat frissítjük, amik valóban változtak
    $row->update($request->only(['name', 'email', 'password']));
    
    return response()->json(['row' => $row], 200);
}
}
