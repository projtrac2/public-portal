<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $table = 'tbl_programs';

    public function section()
    {
        return $this->belongsTo(Section::class, 'projdept', 'stid');
    }

    public function projects()
    {
        return $this->hasMany(Project::class, 'progid', 'progid')->where([['projstatus','>', 0], ['projstatus', '!=', 3]]);
    }
}
