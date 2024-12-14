<?php
namespace App\Http\Controllers\API\V1;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Exception, Validator;


class UserManagementController extends Controller
{
    /* User Create */ 
    public function createUser(Request $request){
        try{
            // dd($request->all());
            $this->apiArray['state'] = 'Create';

            $inputs = $request->all();            
            $validator = Validator::make($inputs, [
                'name'          => 'required|string|max:255',
                'email'         => 'required|email|unique:users,email',
                'phone'         => 'required|regex:/^[789]\d{9}$/',
                'description'   => 'required|string',
                'role_id'       => 'required|exists:roles,id',
                'profile_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            if($validator->fails()){
                $this->apiArray['message'] = $validator->messages()->first();
                $this->apiArray['errorCode'] = 2;
                $this->apiArray['error'] = true;
                return response()->json($this->apiArray, 200);
            }

            $imageName = null;
            if ($request->hasFile('profile_image')) {
                $imageName = time() . '.' . $request->file('profile_image')->extension();
                $request->file('profile_image')->move(public_path('images'), $imageName);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'description' => $request->description,
                'role_id' => $request->role_id,
                'profile_image' => $imageName,
            ]);
            $user['role_id'] = $user->getRole->name;
            $this->apiArray['error'] = false;
            $this->apiArray['user'] = $user;
            $this->apiArray['errorCode'] = 0;
            $this->apiArray['message'] = "User Create successfully.";
            return response()->json($this->apiArray, 200);
        }catch (\Exception $e){
            $this->apiArray['message'] = 'Something is wrong, please try after some time'.$e->getMessage();
            $this->apiArray['errorCode'] = 1;
            $this->apiArray['error'] = true;
            $this->apiArray['data'] = null;
            return response()->json($this->apiArray, 400);
        }
    }
    /* End */
}   
