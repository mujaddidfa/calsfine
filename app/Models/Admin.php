<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admins';
    protected $guard = 'admin';
    
    // Disable timestamps karena tidak ada created_at, updated_at
    public $timestamps = false;
    
    protected $fillable = [
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    // Map email ke username untuk Fortify compatibility
    public function getEmailAttribute()
    {
        return $this->username;
    }
    
    // Fortify identifier - use ID instead of username for session
    public function getAuthIdentifierName()
    {
        return 'id';  // Changed from 'username' to 'id'
    }
    
    // Return the actual auth identifier (ID)
    public function getAuthIdentifier()
    {
        return $this->getKey(); // Returns the primary key (id)
    }
    
    // Disable remember token karena tidak ada kolom remember_token
    public function getRememberToken()
    {
        return null;
    }
    
    public function setRememberToken($value)
    {
        // Do nothing
    }
    
    public function getRememberTokenName()
    {
        return null;
    }
}
