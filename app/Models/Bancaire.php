<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bancaire extends Model
{
    protected $table='bancaires';
    protected $primaryKey ='id';
    protected $fillable=['nr_de_reglement','date','mode','reference','date_echeance','montant_regle','code_client','status'];
}
