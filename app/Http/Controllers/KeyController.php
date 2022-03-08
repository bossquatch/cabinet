<?php

namespace App\Http\Controllers;

use App\Models\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Validation\Rule;

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        Validator::make($input, [
            'user_id' => ['required'],
            'description' => ['required', 'string', 'max:100'],
            'value' => ['required', 'string', 'max:50'],
            'public' => ['required', 'boolean'],
        ])->validateWithBag('createKey');

        $key = Key::create($input);

        return redirect()->route('key.show', ['key' => $key->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Key  $key
     * @return \Illuminate\Http\Response
     */
    public function show(Key $key)
    {
        return Inertia::render('Keys/Show', [
            'key' => $key->load('users'),
        ]);
    }

    private function allowedKeys()
    {
        $user = auth()->user();

        return Key::select('*')
            ->where('user_id', '=', $user->id)
            ->get();
    }
}