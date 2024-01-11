<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'added_by',
        'logo_url',
        'background_color',
        'heading_color',
        'text_color'
    ];
}
