<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function sql(Request $request)
    {
        $sql = $request->input('sql');
        $results = null;
        $error = null;

        if ($request->isMethod('post') && $sql) {
            $trimmedSql = trim($sql);
            if (preg_match('/\bdelete\b/i', $trimmedSql)) {
                $error = 'DELETE command is not allowed.';
            } else {
                try {
                    $firstWord = strtolower(strtok($trimmedSql, " \t\n\r\0\x0B"));
                    if (in_array($firstWord, ['select', 'show', 'describe', 'explain'])) {
                        $results = DB::select($sql);
                    } else {
                        $results = DB::statement($sql);
                    }
                } catch (\Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }

        return view('admin.sql', compact('sql', 'results', 'error'));
    }
}
