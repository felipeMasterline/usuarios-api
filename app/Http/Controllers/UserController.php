<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        return User::get();
    }

    public function validate_store($request, $id = null)
    {
        $rules = [
            'name' => 'required',
            'document' => 'required',
            'apellido' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:4',
        ];
        if (!is_null($id)) $rules['name'] .= ',' . $id;

        $messages = [
            'name' => 'Ingrea un nombre',
            'document' => 'El documento es requerido',
            'apellido' => 'Ingresa tu apellido',
            'email' => 'Ingresa un correo electronico',
            'email.unique' => 'gmail ya registrado',
            'password' => 'ingresa una contraseña',
            'password.min' => 'minimo 4 caracteres',
        ];
        $attributes = [
            'name' => 'Nombre',
        ];

        // $validator = Validator::make($request->all(), $rules, [], $attributes);
        $validator = Validator::make($request->all(), $rules, $messages, $attributes);
        if ($validator->fails()) :
            return ['message' => $validator->errors(), 'code' => 422];
        endif;

        return [
            'code' => 200
        ];
    }

    public function store(Request $request)
    {
        $validate = $this->validate_store($request);
            if ($validate['code'] == 422) return $validate;

        DB::beginTransaction();
        try {
            $user = new User();

            $user->name = $request->name;
            $user->document = $request->document;
            $user->apellido = $request->apellido;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->save();

            DB::commit();
            return response()->json([
                'status' => 200,
                 'msg' => 'Se creo el usuario'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'file' => $th->getFile(),
                'msg' => $th->getMessage(),
                'line' => $th->getLine(),
            ]);
        }
    }
    public function update(Request $request)
    {

        DB::beginTransaction();
        try {
            $user = User::find($request->id);

            $user->name = $request->name;
            $user->document = $request->document;
            $user->apellido = $request->apellido;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->save();

            DB::commit();
            return response()->json([
                'status' => 200,
                 'msg' => 'Se edito el usuario'
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'file' => $th->getFile(),
                'msg' =>  $th->getMessage(),
                'line' => $th->getLine(),
            ]);
        }
    }

    public function destroy(Request $request)
    {
        if($request->id == null || $request->id == $request->id ){
            return 'no existe el dato a eliminar';
        } else{
            DB::beginTransaction();
            try {
                $user = User::find($request->id);
                $user->delete();
                DB::commit();
                return  response()->json([

                    'status' => 200,
                    'msg' => 'se elimino con exito'
                ]);
            } catch (\Throwable $th) {

                return response()->json([
                    'success' => false,
                    'file' => $th->getFile(),
                    'msg' =>  $th->getMessage(),
                    'line' => $th->getLine(),
                ]);

            }
        }
    }
}
