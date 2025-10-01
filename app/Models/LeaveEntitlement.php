<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveEntitlement extends Model
{
    /** @use HasFactory<\Database\Factories\LeaveEntitlementFactory> */
    use HasFactory;

    protected $fillable = [
        'leave_type',
        'full_entitlement', // number of days entitled per year
    ];
}
