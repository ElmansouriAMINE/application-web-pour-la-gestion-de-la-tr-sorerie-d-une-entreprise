<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */

    public function rules(): array
            {
            return [
                '2' => Rule::unique('users', 'email')
            ];

            }

    public function customValidationMessages()
            {
                return [
                    '2.unique' => 'Duplicate',
                ];
            }
    public function model(array $row)
    {
        if(!User::where('email', '=', $row['email'])->exists()) {

        $user = new User([
            "first_name" => $row['first_name'],
            "last_name" => $row['last_name'],
            "email" => $row['email'],
            "mobile_number" => $row['mobile_number'],
            "role_id" => 2, // User Type User
            "status" => 1,
            "password" => Hash::make('password')
        ]);
    

        // Delete Any Existing Role
        DB::table('model_has_roles')->where('model_id',$user->id)->delete();
            
        // Assign Role To User
        $user->assignRole($user->role_id);
   
        return $user; }
    }
    
}
