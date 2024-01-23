<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'tbl_projects';

    protected $primaryKey = 'projid';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public function program()
    {
        return $this->belongsTo(Program::class, 'progid', 'progid');
    }

    public function financialYear()
    {
        return $this->belongsTo(FinancialYear::class, 'projfscyear');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'projstatus', 'statusid');
    }
}
