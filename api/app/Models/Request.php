<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    protected $fillable = [
        'type',
        'start_date',
        'end_date',
        'multi_day',
        'status',
        'comments',
    ];

    protected $dates = ['start_date', 'end_date'];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function scopeOfType($query, $type)
    {
      return $query->where('type', $type);
    }

    public function scopeApproved($query)
    {
        return $query->whereStatus('Approved');
    }

    public function getDaysAttribute()
    {
        if (!$this->attributes['multi_day']) { return 1; }

        $start = new \Carbon\Carbon($this->attributes['start_date']);
        $end = new \Carbon\Carbon($this->attributes['end_date']);

        return $end->diffInWeekdays($start) + 1;
    }
}
