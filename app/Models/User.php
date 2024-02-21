<?php

namespace App\Models;

use App\Notifications\EmailVerifiedNotification;
use App\Notifications\ResetPasswordNotification;
use App\Notifications\VerifyEmailNotification;
use DB;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends BaseModel implements
  AuthenticatableContract,
  AuthorizableContract,
  CanResetPasswordContract,
  MustVerifyEmail
{
  use HasApiTokens;
  use Notifiable;
  use Authenticatable;
  use Authorizable;
  use CanResetPassword;

  public static $cacheKey = 'users';
  protected $fillable = [
    'email',
    'password',
    'firstname',
    'lastname',
    'phone',
    'avatar_id',
    'location_id',
    'rating',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = [
    'roles',
    'permissions',
    'password',
    'remember_token',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];
  protected $with = [
    'avatar',
    'location',
  ];

  protected $appends = [
    'rolesNames',
    'permissionsNames',
  ];

  public function getRolesNamesAttribute()
  {
    $rolesNames = $this->roles->pluck('name')->all();
    sort($rolesNames);
    return $rolesNames;
  }


  public function getPermissionsNamesAttribute()
  {
    return $this->allPermissions()->pluck('name')->all();
  }

  protected static function booted()
  {
    parent::booted();
    static::created(function ($user) {
      $user->givePermission('users.' . $user->id . '.read');
      $user->givePermission('users.' . $user->id . '.update');
      $user->givePermission('users.' . $user->id . '.delete');
    });
    static::deleted(function ($user) {
      $permissions = Permission::where('name', 'like', 'users.' . $user->id . '.%')->get();
      DB::table('users_permissions')->whereIn('permission_id', $permissions->pluck('id'))->delete();
      Permission::destroy($permissions->pluck('id'));
    });
  }
  // Define relationships with other models
  public function reviews()
  {
    return $this->hasMany(Review::class);
  }
  public function avatar()
  {
    return $this->belongsTo(Upload::class);
  }
  public function location()
  {
    return $this->belongsTo(Location::class);
  }

  public function bookings()
  {
    return $this->hasMany(Booking::class);
  }
  public function roles()
  {
    return $this->belongsToMany(Role::class, 'users_roles');
  }

  public function hasRole($roleName)
  {
    return $this->roles->contains('name', $roleName);
  }

  public function assignRole($roleName)
  {
    $role = Role::where('name', $roleName)->first();
    $this->roles()->save($role);
  }

  public function syncRoles($roleNames)
  {
    $roles = Role::whereIn('name', $roleNames)->get();
    $this->roles()->sync($roles);
  }

  public function permissions()
  {
    return $this->belongsToMany(Permission::class, 'users_permissions');
  }

  public function allPermissions()
  {
    $permissions = $this->permissions;
    foreach ($this->roles as $role) {
      $permissions = $permissions->merge($role->permissions);
    }
    return $permissions;
  }

  public function hasPermissionName($permissionName)
  {
    return $this->allPermissions()->contains('name', $permissionName);
  }

  public function hasPermission($entityName, $action, $entityId = null)
  {
    $permissionName = $entityName . ".$action";
    if ($this->hasPermissionName($permissionName)) {
      return true;
    }
    $permissionName = $entityName . '.*';
    if ($this->hasPermissionName($permissionName)) {
      return true;
    }
    if ($entityId !== null) {
      $permissionName = $entityName . ".$entityId.$action";
      if ($this->hasPermissionName($permissionName)) {
        return true;
      }
    }
    return false;
  }

  public function givePermission($permissionName)
  {
    $permission = Permission::where('name', $permissionName)->first();
    if (!$permission) {
      $permission = Permission::create(['name' => $permissionName]);
    }
    $this->permissions()->save($permission);
  }

  public function rules($id = null)
  {
    $id = $id ?? request()->route('id');
    $rules = [
      'role' => 'required|exists:roles,name',
      'email' => 'required|email|unique:users,email',
      'password' => 'required|string',
      'firstname' => 'required|string',
      'lastname' => 'required|string',
      'phone' => 'required|string',//should be unique
      'rating' => 'nullable|float',
      'avatar_id' => 'required|exists:uploads,id',
      'location_id' => 'required|exists:locations,id',
    ];
    if ($id !== null) {
      $rules['email'] .= ',' . $id;
      $rules['password'] = 'nullable|string';
    }
    return $rules;
  }
  public function sendEmailVerificationNotification()
  {
      $this->notify(new VerifyEmailNotification());
  }
  public function sendVerifiedEmailNotification()
  {
      $this->notify(new EmailVerifiedNotification());
  }
  public function sendPasswordResetNotification($token)
  {
    $this->notify(new ResetPasswordNotification($token));
  }
/**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedEmail()
    {
        return $this->email_verified_at !== null; // Assuming you have a column named 'hasVerifiedEmail' in your users table
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return void
     */
    public function markEmailAsVerified()
    {
        $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(), // Optional if you are using Laravel's default email verification functionality
        ])->save();
    }

    /**
     * Get the email address that should be used for verification.
     *
     * @return string
     */
    public function getEmailForVerification()
    {
        return $this->email;
    }
  }
