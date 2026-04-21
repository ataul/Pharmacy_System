<?php

namespace App\Http\Controllers;

use App\DataTables\UsersDataTable;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(UsersDataTable $dataTable)
    {
        return $dataTable->render('users.index', ['roles' => Role::all()]);
    }

    public function store(StoreUserRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            if ($request->has('roles')) {
                $user->assignRole($request->roles);
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            return to_route('users.index')->with('error', 'Error in Creating User.')->with('timeout', 3000);
        }
        return to_route('users.index')->with('success', 'User has been created successfully!')->with('timeout', 3000);
    }

    public function show(User $user)
    {
        return response()->json(['user' => $user, 'roles' => $user->getRoleNames()]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        try {
            $data = [
                'name' => $request->name,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            if ($request->has('roles')) {
                $user->syncRoles($request->roles);
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            return to_route('users.index')->with('error', 'Error in Updating User.')->with('timeout', 3000);
        }
        return to_route('users.index')->with('success', 'User updated successfully!')->with('timeout', 3000);
    }

    public function destroy(User $user)
    {
        try {
            $user->delete();
        } catch (\Illuminate\Database\QueryException $exception) {
            return to_route('users.index')->with('error', 'Error in Deleting User.')->with('timeout', 3000);
        }
        return to_route('users.index')->with('success', 'User deleted successfully!')->with('timeout', 3000);
    }
}
