<?php

namespace VamikaDigital\WorkflowManager\Models;

use Illuminate\Database\Eloquent\Model;

class WorkflowHistory extends Model
{
  /**
   * @var array
   */
  protected $fillable = ['model_name', 'model_id', 'transition', 'from', 'from_text', 'role_name', 'user_id'];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'extra' => 'array'
  ];

  public function historyable()
  {
    return $this->morphTo('historyable', 'model_name', 'model_id');
  }

  /**
   * A state belong to the issue.
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function model()
  {
    return $this->belongsTo("$this->model_name");
  }

  /**
   * A state belongs to a user.
   *
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function user()
  {
    return $this->belongsTo('App\User');
  }

  public function scopeAccepted($query)
  {
    return $query->where('transition', 'accept');
  }
}
