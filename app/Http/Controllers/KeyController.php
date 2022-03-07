<?php

namespace App\Http\Controllers;

use App\Models\Key;
use Inertia\Inertia;

class KeyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Keys/Index', [
            'keys' => $this->allowedKeys()->map(function ($key) {
                return [
                    'description' => $key->description,
                    'value' => $key->value,
                    'public' => $key->public,
                ];
            }),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Keys/Create');
    }

    private function allowedKeys()
    {
        $user = auth()->user();

        return Key::select('*')
            ->where('user_id', '=', $user->id)
            ->get();
    }
}