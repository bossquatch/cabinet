<?php

namespace App\Http\Controllers;

use App\Models\Key;
use App\Models\SharedKey;
use App\Models\User;
use App\Models\Team;
use App\Models\KeyAccessRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;
use Illuminate\Validation\Rule;
use Laravel\Jetstream\Jetstream;
use Carbon\Carbon;

class KeyAccessRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->adminKeyAccess();

        return Inertia::render('Requests/Index', [
            'requests' => KeyAccessRequest::all()->map(function ($req) {
                $admin = User::where('id', $req->admin_id)->firstorfail();
                $user = User::where('email', $req->user_email)->firstorfail();
                return [
                    'id' => $req->id,
                    'admin_id' => $req->admin_id,
                    'admin_name' => $admin->name,
                    'user_email' => $user->email,
                    'user_name' => $user->name,
                    'purpose' => $req->purpose,
                    'approved' => $req->approved
                ];
            })
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Requests/Create');
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
            'admin_id' => ['required'],
            'user_email' => ['required', 'string', 'max:255'],
            'purpose' => ['required', 'string', 'max:255']
        ])->after(
            $this->ensureEmailExists($input)
        )->after(
            $this->ensureEmailNotCurrentUser($input)
        )->validateWithBag('createRequest');

        $newRequest = KeyAccessRequest::create($input);

        return redirect()->route('request.index');
    }

    /**
     * Remove a resource in storage.
     *
     * @param \App\Models\KeyAccessRequest $req
     * @return \Illuminate\Http\Response
     */
    public function delete(KeyAccessRequest $req)
    {   
        KeyAccessRequest::select('*')
            ->where('id', '=', $req->id)
            ->firstorfail()
            ->delete();

        return redirect()->route('request.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \App\Models\KeyAccessRequest $req
     * @return \Illuminate\Http\Response
     */
    public function approveRequest(Request $request, KeyAccessRequest $req)
    {
        $input = $request->all();
        $input['approved'] = true;
        $input['approved_at'] = Carbon::now();
        $req->update($input);

        return back(303);
    }

    /**
     * Check if admin access to a user's keys is still available.
     */
    private function adminKeyAccess()
    {
        $requests = KeyAccessRequest::where('approved', true)->get();

        foreach ($requests as $request)
        {
            $approvedTime = Carbon::createFromFormat('Y-m-d H:s:i', KeyAccessRequest::where('id', $request->id)->pluck('approved_at')->first());
            $currentTime = Carbon::now();

            $diff_in_hours = $approvedTime->diffInHours($currentTime);

            if ($diff_in_hours > 24)
            {
                KeyAccessRequest::select('*')
                    ->where('id', '=', $request->id)
                    ->firstorfail()
                    ->delete();
            }
        }

        return;
    }

    /**
     * Ensure that the email exists.
     *
     * @param  mixed  $input
     * @return \Closure
     */
    protected function ensureEmailExists(mixed $input)
    {
        $email = User::select('*')
            ->where('email', '=', $input['user_email'])
            ->exists();

        return function ($validator) use ($email) {
            $validator->errors()->addIf(
                !$email,
                'user_email',
                __('We were unable to find a registered user with this email address.')
            );
        };
    }

    /**
     * Ensure that the email is not the current user.
     *
     * @param  mixed  $input
     * @return \Closure
     */
    protected function ensureEmailNotCurrentUser(mixed $input)
    {
        $user = auth()->user();
        $myEmail = false;

        if ($input['user_email'] == $user->email)
        {
            $myEmail = true;
        }

        return function ($validator) use ($myEmail) {
            $validator->errors()->addIf(
                $myEmail,
                'user_email',
                __('You already have access to your keys.')
            );
        };
    }
}