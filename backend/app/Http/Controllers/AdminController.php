<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller {
    public function index() {
        return response()->json([
            'ikasleak' => User::where('rola', 'ikaslea')->get(),
            'status' => 'success'
        ]);
    }
}
