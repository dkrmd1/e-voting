<?php

namespace App\Http\Controllers\Web;

use App\Models\Voter;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\User;
use App\Http\Requests\VoterStoreRequest;
use Illuminate\Routing\Controllers\HasMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Illuminate\Routing\Controllers\Middleware as ControllersMiddleware;

class VoterController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new ControllersMiddleware(PermissionMiddleware::using('voter-view'), only: ['index', 'show']),
            new ControllersMiddleware(PermissionMiddleware::using('voter-create'), only: ['create', 'store']),
            new ControllersMiddleware(PermissionMiddleware::using('voter-update'), only: ['edit', 'update']),
            new ControllersMiddleware(PermissionMiddleware::using('voter-delete'), only: ['destroy']),
        ];
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $voters = Voter::all();

        return view('pages.app.voter.index', compact('voters'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.app.voter.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VoterStoreRequest $request)
    {
        try {
            $user = User::create([
                'email' => $request->email,
                'password' => bcrypt($request->password)   
            ]);

            $user->assignRole('voter');

            $user->voter()->create([
                'name' => $request->name
            ]);

            return redirect()->route('app.voter.index')->with('succes', 'Voter updated successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Failed to update voter, ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
