<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Carbon\Carbon; // Ensure you have this line if using read_at logic

class ContactController extends Controller
{
    // --- INDEX (View all submissions - LIST VIEW) ---
    // app/Http/Controllers/Superadmin/ContactController.php

    public function index()
    {
        $contacts = Contact::latest()->paginate(15); 
        
        // THIS LINE MUST BE CORRECT:
        return view('superadmin.contacts.index', compact('contacts')); 
    }

    // --- SHOW (View details of one submission - SINGLE ITEM) ---
    public function show(Contact $contact)
    {
        // 2. THIS IS WHERE THE READ LOGIC BELONGS!
        // It operates on the single $contact object passed by route model binding.
        if (is_null($contact->read_at)) {
            $contact->update(['read_at' => Carbon::now()]);
        } 
        
        return view('superadmin.contacts.show', compact('contact'));
    }
    
    // --- DESTROY (Delete a submission) ---
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()->route('superadmin.contacts.index')
            ->with('success', "Contact submission from {$contact->name} deleted successfully.");
    }
}