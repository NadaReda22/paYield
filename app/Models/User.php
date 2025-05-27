<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Permission;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

use function PHPUnit\Framework\isEmpty;

class User extends Authenticatable implements MustVerifyEmail,CanResetPassword
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable,HasRoles,Sluggable, HasRoles;



    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];
protected $guarded=[];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * 
     * 
     *Sluggable
     */
    public function Sluggable():array{
       return [ 
        'user_slug'=>[
        'source'=>'SlugSource'
        ]
        
       ];
    }

 // Define a custom method to generate the slug source
public function getSlugSourceAttribute()
{
    return substr($this->email,0,4) ?:Str::random(4);
}

    // Define a custom method to generate the slug source
   
    // public static function boot() {
    //     parent::boot();

    //     static::creating(function ($user) {
    //         // Extract the first 4 characters of the email before '@'
    //         $prefix = substr($user->email, 0, 4);
            
    //         // Generate a random string (4 characters)
    //         $random = Str::random(4);

    //         // Use either the email prefix or random string if prefix is empty
    //         $user->user_slug = $prefix ?: $random;
    //     });
    // }
    /**
     * 
     *         
     * Ensure is User Online??
     * 
     * 
     */
     public function isOnline()
     {
        // checks if user's lastseen <=5 minutes from now so he is online
        return $this->lastseen && Carbon::parse($this->lastseen)->diffInMinutes(now()) <= 5;
     }


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }



public function reviews()
{
    return $this->hasMany(Review::class);
}




/**
 * ****************************************************************************************************
 * 
 * 
 * ***************************************Manage Roles AND Permissions*********************************
 * 
 * 
 * ***************************************************************************************************
 */
    


   /*
   Get all groups of permissions
   */

 public static function getpermissionGroups()
 {
  return Permission::select('group_name')
  ->groupBy('group_name')
  ->get();
 }
 
   /*
   Get all   permissions of a group
   */
  
   public static function getpermissionByGroupName($group_name)
   {
       return Permission::where('group_name', $group_name)
                        ->get(['name', 'id','guard_name']);
                        
   }
   
   

      /*
   Check if the role has  all these  permissions 
   */

   
   public static function roleHasPermissions($role,$permissions)
   {
    $hasPermission=true;

    if(!$permissions ||$permissions->isEmpty())
    return false;// ✅ Prevent foreach error

    foreach($permissions as $permission)
    {
        if(!$role->hasPermissionTo($permission))
        {
           
            return  false; // ✅ Returns only if any permission is missing
        }
        return true; // ✅ Returns true only if all permissions exist
    }

}
}