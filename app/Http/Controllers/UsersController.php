<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    // Edita usuario
    public function edit()
    {
        return view('my-profile')->with('user', auth()->user());
    }

    // Atualiza usuario
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.auth()->id(),
            'password' => 'sometimes|nullable|string|min:6|confirmed',
        ]);

        $user = auth()->user();
        $input = $request->except('password', 'password_confirmation');

        if (! $request->filled('password')) {
            $user->fill($input)->save();

            return back()->with('success_message', 'Perfil atualizado com sucesso!');
        }

        $user->password = bcrypt($request->password);
        $user->fill($input)->save();

        return back()->with('success_message', 'Perfil (e senha) atualizados com sucesso!');
    }
}
