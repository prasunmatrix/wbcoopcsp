<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
  protected $table = 'password_resets';
  protected $dates = ['created_at'];

  protected $fillable = [
    'email', 'token'
  ];

  protected $primaryKey = null;

  public $incrementing = false;

  public $timestamps = false;
}
