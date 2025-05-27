<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\rolesHasPermission;


class RoleController extends Controller
{
    //

    public function allPermission()
    {
     $permissions=Permission::all();
     return view('admin.role.all_permission',compact('permissions'));
    }

    public function addPermission()
    {
     return view('admin.role.add_permission');
    }

    public function storePermission(Request $request)
    {
   $permission=Permission::create(
    [
        'name'=>$request->name,
        'group_name'=>$request->group_name,
    ]
    );
    return redirect()->route('permissions.all')->with('success','The permission added successfully');
    }

    public function editPermission($id)
    {
        $permission=Permission::findOrFail($id);
     return view('admin.role.edit_permission',compact('permission'));
    }


    public function updatePermission(Request $request,$id)
    {
        $permission=Permission::findOrFail($id)->update(
            [
                'name'=>$request->name,
                'group_name'=>$request->group_name,
            ]
        );

    return redirect()->route('permissions.all')->with('success','The permission updated successfully');
    }

    public function deletePermission($id)
    {
        $permission=Permission::findOrFail($id)->delete();

    return redirect()->route('permissions.all')->with('success','The permission deleted successfully');
    }




    /**
     * 
     * 
     * Roles 
     * 
     */





     public function allRole()
     {
      $roles=Role::all();
      return view('admin.role.all_roles',compact('roles'));
     }
 
     public function addRole()
     {
      return view('admin.role.add_roles');
     }
 
     public function storeRole(Request $request)
     {
        //checking role ?????

    $role=Role::create(
     [
         'name'=>$request->name,
     ]
     );
     return redirect()->route('roles.all')->with('success','The role added successfully');

     }
 
     public function editRole($id)
     {
         $roles=Role::findOrFail($id);
      return view('admin.role.edit_roles',compact('roles'));
     }
 
 
     public function updateRole(Request $request,$id)
     {
         $role=Role::findOrFail($id)->update(
             [
                 'name'=>$request->name,
             ]
         );
 
     return redirect()->route('roles.all')->with('success','The role updated successfully');
     }
 
     public function deleteRole($id)
     {
         $role=Role::findOrFail($id)->delete();
 
     return redirect()->route('roles.all')->with('success','The role deleted successfully');
     }

     


         /**
     * 
     * 
     * Manage Role has permissions 
     * 
     */

     public function allRolePermissions()
     {
        $roles=Role::all();
        return view('admin.role.all_roles_permission',compact('roles'));
     }


     public function addRolePermissions()
     {
        $roles=Role::all();
        $permissions=Permission::all();
        $permission_groups=User::getpermissionGroups();
        return view('admin.role.add_roles_permission',compact(['roles','permissions','permission_groups']));
     }

     public  function storeRolePermissions(Request $request)
     {
        $roleId=$request->role_id;
 // is a function that loops through an array and transforms each item.
     $permissions=array_map(fn($permission_id)=>[
     'role_id'=>$roleId,
     'permission_id'=>$permission_id,
    ],$request->permission);//the name of input permission[]

        DB::table('role_has_permissions')->insert($permissions);

        return redirect()->route('role.permission.all')->with('success','Role permissions added successfully');
     }

     public function editRolePermissions($id)
     {
        $role=Role::findOrFail($id);
        $permissions=Permission::all();
        $permission_groups=User::getpermissionGroups();
        return view('admin.role.edit_role_permission',compact(['role','permissions','permission_groups']));
     }


     public function updateRolePermissions(Request $request ,$id)
     {
        $role=Role::findOrFail($id);

         // Update role name if provided
        if($request->has('name') && !empty($request->name))
        {
            $role->name=$request->name;
            $role->save();
        }

       // Get permissions (convert to integers)
        $permissions=Permission::whereIn('id',$request->permission ?? [])
                                ->pluck('name')
                                ->toArray();



    // Sync permissions (if empty, it will remove all existing permissions)
        $role->syncPermissions($permissions);//it requires permissions names only

        return redirect()->route('role.permission.all')->with('success','Role permissions updated successfully');

     }

     /**
      * Delete Role Permissions
      */
     public function deleteRolePermissions($id)
     {
        $role=Role::findOrFail($id);
        $role->syncPermissions([]); // Remove all permissions

        return redirect()->route('role.permission.all')->with('success','Role permissions deleted successfully');

     }
  

}
