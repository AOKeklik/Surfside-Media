<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class AdminContactController extends Controller
{
    public function contacts () {
        $contacts = Contact::orderBy("created_at", "DESC")->paginate(12);
        return view("admin.contacts", compact("contacts"));
    }

    public function delete_contact (Request $request) {
        $contact = Contact::find($request->contact_id);
        
        if (!$contact) 
            return redirect()->back()->with("error", "Comment does not exist!");

        $contact->delete();

        return redirect()->back()->with("status", "Contact has been deleted successfully!");
    }
}
