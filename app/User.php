<?php

namespace App;

use Carbon\Carbon;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Intervention\Image\Facades\Image;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'patronymic',
        'email',
        'role_id',
        'password',
        'photo',
        'phone',
        'validator'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function rules($id = 0)
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'patronymic' => 'required|string|max:255',
            'phone' => 'required|min:11',
            'email' => 'required|email',
            'iin'=> 'required|min:12'
        ];
    }


    public function toArray()
    {
        $array = parent::toArray();
        $array['role'] = $this->role;
        $array['role_name'] = $this->role_name;
        $array['authenticated'] = true;
        return $array;
    }

    public function setPhotoAttribute($value)
    {
        $validator = \Validator::make(['image' => $value], [
            'image' => 'base64image'
        ]);

        if (!$validator->fails()) {

            $imageData = $value;
            $fileName = Carbon::now()->timestamp . '_' . uniqid() . '.' . explode('/', explode(':', substr($imageData, 0, strpos($imageData, ';')))[1])[1];

            Image::make($value)->fit(200)->save(public_path('images/') . $fileName);

            $this->attributes['photo'] = '/images/' . $fileName;

        }

    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    /**
     * @param $value
     * @return mixed|string
     */
    public function getRoleNameAttribute($value)
    {
        return $this->role->name;

    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param $filters
     * @return \Illuminate\Database\Eloquent\Builder mixed
     */
    public function scopeFilter($query, $filters)
    {

        if(isset($filters['search_text'])){
            $search_text = $filters['search_text'];

            $query->where(function($query) use($search_text) {
                $query->where('name', 'like', '%'.$search_text.'%');
                $query->orWhere('email', 'like', '%'.$search_text.'%');
                $query->orWhere('phone', 'like', '%'.$search_text.'%');
            });
        }

        $query->whereHas('role', function($query) {
            $query->where('name', 'administrator');
        });

        return $query;
    }


    /**
     * @param $roles
     * @return bool
     */
    public function authorizeRole($roles)
    {

        $authorize = false;

        foreach ($roles as $role) {
            if ($this->hasRole($role)) $authorize = true;
        }

        if(!$authorize)
            abort(401, 'Упс...доступ запрещен!');

        return true;
    }

    /**
     * @param $role
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->role_name == $role;
    }



    public function findForPassport($username) {
        return $this->where('phone', $username['name'])->where('id',$username['id'])->first();
    }


    public function scopeMultiAccount($query, $user_id){

    }


    public function patient(){
        return $this->hasOne('App\Patient', 'user_id');
    }

    public function doctor(){
        return $this->hasOne('App\Doctor', 'user_id');
    }


}
