<?php

namespace App\Http\Controllers\User;

use App\User;
use App\Mail\UserCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\UserCollection;
use App\Http\Controllers\ApiController;
use App\Http\Resources\User as UserResource;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //SIGUIENDO EL TUTORIAL RETORNAMOS DE ESTA MANERA
        //PERO SE PUEDE RETORNAR UTILIZANDO COLLECTIONS PARA DARLE FORMATO A LA RESPUESTA.
        return new UserCollection(User::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required | email | unique:users',
            'password' => 'required | min:8 | confirmed',
        ]);

        $request['password'] = bcrypt($request->password);
        $request['verified'] = User::USUARIO_NO_VERIFICADO;
        $request['admin'] = User::USUARIO_REGULAR;
        $request['verification_token'] = User::generarVerificationToken();

        $usuario = User::create($request->all());

        return response()->json( ['data' => $usuario], 201 );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $usuario = User::findOrFail($id);

        return response()->json( ['data' => $usuario], 200 );
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
        $user = User::findOrFail($id);

        $request->validate([
            //VALIDA SI ES ÚNICO EN LOS EMAIL EXCEPTUANDO EL EMAIL CON SU ID
            'email' => 'email | unique:users,email,' . $user->id,
            'password' => 'min:8 | confirmed',
            //VALIDA SI LOS DATOS COINCIDEN CON ALGUNO DE ESTOS VALORES
            'admin' => 'in:' . User::USUARIO_ADMINISTRADOR . ',' . User::USUARIO_REGULAR,
        ]);

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email') && $user->email != $request->email) {
            $user->verified = User::USUARIO_NO_VERIFICADO;
            $user->verification_token = User::generarVerificationToken();
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->has('admin')) {
            if (!$user->esVerificado()) {
                return response()->json(['error' => 'Unicamente los usuarios verificados pueden cambiar su valor de administrador',
                                         'code' => 409], 409);
            }

            $user->admin = $request->admin;
        }

        if (!$user->isDirty()) {
            return response()->json(['error' => 'Se debe especificar al menos un valor diferente para actualizar',
                                     'code' => 422], 422);
        }

        $user->save();

        return response()->json(['data' => $user], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json(null, 200);
    }

    public function verify($token)
    {
        //Busca a el usuario por el token ingresado, ésto se ejecuta en la Route verify
        $user = User::where('verification_token', $token)->firstOrFail();

        $user->verified = User::USUARIO_VERIFICADO;
        $user->verification_token = null;

        $user->save();

        return $this->showMessage('La cuenta ha sido verificada');
    }

    public function resend(User $user)
    {
        if ($user->esVerificado()) {
            return $this->errorResponse('Este usuario ya ha sido verificado.', 409);
        }

        retry(5, function() use ($user) {
            Mail::to($user->email)->send(new UserCreated($user));
        }, 100);

        return $this->showMessage('El correo de verificación se ha enviado');
    }
}
