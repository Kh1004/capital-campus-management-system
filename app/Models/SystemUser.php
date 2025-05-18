<?php
// app/Models/SystemUser.php
class SystemUser extends Model {
    protected $table = 'v_all_users';
    public $timestamps = false;
    protected $fillable = ['id','role','email','password'];
    protected $hidden   = ['password'];
}
