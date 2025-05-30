<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {
    public function index() {
        return User::all();
    }

    public function show($id)
    {
        $user = User::find($id);
    
        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }
    
        return response()->json($user);
    }
    
    public function store(Request $request) {
        $data = $request->validate([
            'username' => 'required|string|unique:users',
            'name' => 'required|string',
            'last_name' => 'nullable|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }
    public function storeAdmin(Request $request) {
        $data = $request->validate([
            'username' => 'required|string|unique:users',
            'name' => 'required|string',
            'last_name' => 'nullable|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,client',
        ]);

        $data['password'] = Hash::make($data['password']);
        return User::create($data);
    }
    public function update(Request $request, $id)
{
    $user = User::findOrFail($id);

    $data = $request->validate([
        'username' => 'sometimes|string|unique:users,username,' . $user->id,
        'name' => 'sometimes|string',
        'last_name' => 'nullable|string',
        'email' => 'sometimes|email|unique:users,email,' . $user->id,
        'password' => 'nullable|string|min:6',
        'role' => 'in:admin,client',
    ]);

    if (isset($data['password'])) {
        $data['password'] = Hash::make($data['password']);
    }

    $user->update($data);

    return $user;
}

public function destroy($id)
{
    $user = User::findOrFail($id);
    $user->delete();
    return response()->json(['message' => 'Usuario eliminado']);
}
public function adminUsers()
{
   $users = User::with(['cartItems.product', 'orders'])->get();


    return response()->json($users);
}


}
