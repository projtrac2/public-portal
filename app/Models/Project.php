<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $table = 'tbl_projects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        '',
        '',
        '',
    ];


    public function program()
    {
        return $this->belongsTo(Program::class, 'program_id', 'id');
    }

    public function financialYear()
    {
        return $this->belongsTo(FinancialYear::class, 'projyear');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'projstatus', 'id');
    }
}
