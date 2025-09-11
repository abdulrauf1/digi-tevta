<?php

namespace App\Http\Controllers\Admin;
use App\Models\User;
use App\Models\Trainee;
use App\Http\Controllers\Controller;    


use Illuminate\Http\Request;

class AdminTraineeController extends Controller
{
    public function index()
    {
        $trainees = Trainee::with(['user'])->paginate(10);        
        return view('admin.trainees.index', compact('trainees'));
    }

    public function create()
    {
        return view('admin.trainees.create');
    }

    public function store(Request $request)
    {
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required',
            'phone' => 'required|string|max:20',
            'cnic' => 'required|string|max:15|unique:trainees,cnic',
            'gender' => 'required|in:Male,Female,Other',
            'date_of_birth' => 'required|date|before:today',
            'address' => 'required|string|max:500',
            'emergency_contact' => 'required|string|max:20',
            'domicile' => 'required|string|max:100',
            'education_level' => 'required|in:Primary,Secondary,Intermediate,Bachelor,Master,Doctorate'
        ], [
            'cnic.unique' => 'The CNIC has already been registered.',
            'date_of_birth.before' => 'The date of birth must be a date before today.',
            'education_level.in' => 'Please select a valid education level.',
        ]);

        
        $validated['password'] = bcrypt($validated['password']);
        $validated['role'] = 'trainee';
        
        $user = User::create($validated);
        
        $user->assignRole('trainee');
            
        Trainee::create([
            'user_id' => $user->id,
            'cnic' => $validated['cnic'],
            'gender' => $validated['gender'],
            'date_of_birth' => $validated['date_of_birth'],
            'contact' => $validated['phone'],
            'emergency_contact' => $validated['emergency_contact'],
            'domicile' => $validated['domicile'],
            'education_level' => $validated['education_level'],
            'address' => $validated['address'],            
        ]);

        return redirect()->route('admin.trainees.index')
            ->with('success', 'Trainee created successfully.');
    }

    public function show(User $trainee)
    {
        return view('admin.trainees.show', compact('trainee'));
    }

    public function edit(User $trainee)
    {
        return view('admin.trainees.edit', compact('trainee'));
    }

    public function update(Request $request, User $trainee)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $trainee->id,
            'password' => 'nullable|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        $trainee->update($validated);
        
        return redirect()->route('admin.trainees.index')
            ->with('success', 'Trainee updated successfully.');
    }

    public function destroy(User $trainee)
    {
        $trainee->delete();
        
        return redirect()->route('admin.trainees.index')
            ->with('success', 'Trainee deleted successfully.');
    }
}