<?php

namespace App\Http\Controllers;

use App\Models\Transaction;

class TransactionController extends Controller
{
    protected $principalModelClass = Transaction::class;
    protected $enableOnlyCreatorCanDelete = true;
}
