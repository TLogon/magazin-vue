<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        echo json_encode(User::orderBy('name')->get());
    }

    public function register(Request $request)
    {
        $requestData = $request->all();
        $validator = Validator::make($requestData, [
            'name' => 'required|max:50',
            'password' => 'required|max:100|confirmed'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }
        $user = new User();
        $user->name = $request->name;
        $user->password = Hash::make($requestData['password']);
        $user->save();
        $rezult = $user->save();
        if ($rezult) {
            return ["rezult" => 'Администратор успешно зарегистрирован'];
        } else {
            return ["rezult" => 'Ошибка при добавлении нового администратора'];
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'password' => 'required|max:100|confirmed'
            ]
        );
        if ($validator->fails()) {
            echo json_encode( [
                "status" => false,
                "errors" => 'Ошибка валидации'
            ]);
        }
        $user = User::findOrFail($id);
        $user->password = Hash::make($request->password);
        $rezult = $user->save();
        if ($rezult) {
            return ["rezult" => 'Пароль администратора успешно изменен'];
        } else {
            return ["rezult" => 'Ошибка при изменении пароля администратора'];
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $rezult = User::where('id', $id)->delete();
        if ($rezult) {
            return ["rezult" => 'Администратор успешно удален'];
        } else {
            return ["rezult" => 'Ошибка при удалении администратора'];
        }
    }
}
