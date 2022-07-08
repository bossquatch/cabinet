<?php

namespace App\Http\Controllers;

use App\Models\Key;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Update a key's category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateCategory(Request $request)
    {
        $input = $request->all();
        
        Validator::make($input, [
            'user_id' => ['required'],
            'key_id' => ['required'],
            'name' => ['required', 'string', 'max:255']
        ])->validateWithBag('updateCategory');

        Category::where('key_id', $input['key_id'])->delete();
        
        Category::create($input);

        return redirect()->route('key.show', ['key' => $input['key_id']]);
    }

    /**
     * Remove a key from its category.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Models\Key $categoryKey
     * @return \Illuminate\Http\Response
     */
    public function removeCategory(Request $request, Key $key)
    {
        $input = $request->all();
        $user = auth()->user();

        Category::where('user_id' , $user->id)->where('key_id', $key->id)->firstorfail()->delete();

        return redirect()->route('key.show', ['key' => $key->id]);
    }
}