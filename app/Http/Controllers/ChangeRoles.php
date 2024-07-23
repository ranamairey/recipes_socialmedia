<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\GeneralTrait;
use League\Event\GeneratorTrait;

use function PHPUnit\Framework\isEmpty;

class ChangeRoles extends Controller
{
    use GeneratorTrait;
    public function getUsers()
    {
        $users = User::where('role', '!=' ,'admin')->get();
        return response()->json($users);
    }

    public function changePermissions(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'role'=>['required',  Rule::in(['admin', 'chef','user']),],
        ]);
        
        if($validator->fails()){
            return response()->json($validator->errors()->all(),400);
        }
        $user = User::where('email', $request->email )->first();

        if($user != null&&$user!=Auth::user()){
            $user->role=$request->role;
            $user->save();
            return response()->json([
                'message' => 'Successfully created user!'
            ], 201);
        }
        return response()->json([
            'message' => 'Data Error'
        ], 400);
    }



    public function chefcreate(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'f_name' => 'required|max:20|min:2',
            'l_name' => 'required|max:20|min:2',
            'email' => 'required|unique:users|email',
            'password' => 'required|min:8|max:20',
            'image' => 'nullable|file|mimes:png,jpg',
        ]);
        
        if($validator->fails()){
            return response()->json($validator->errors()->all(),400);
        }
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = $file->store('images/user');
        
            // الحصول على المسار الكامل للملف المحفوظ
            $storedPath = str_replace(storage_path('app'), 'C:\Users\scc-asus\Desktop\Project2\yam\storage\app', storage_path('app/' . $path));
        
            // تعيين المسار المحفوظ في المتغير المناسب
            $image = $storedPath;
        }
        else{
            $image = $request->image;
        }
        User::create([
            'f_name' => $request->f_name,
            'l_name' => $request->l_name,
            'email' =>$request->email,
            'password' => bcrypt($request->password),
            'image' => $image,
            'role' => 'chef'
        ]);
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }
}
