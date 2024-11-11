<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index () {
        return view("contact");
    }

    public function store_contact (Request $request) {
        $request->validate([
            "name" => "required|string|min:3|max:15",
            "email" => "required|email",
            "phone" => "required|numeric|regex:/^[0-9]{10}$/|digits:10",
            "comment" => "required|string|max:500",
        ]);

        $contact = new Contact();

        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->phone = $request->phone;
        $contact->comment = $request->comment;

        if (!$contact->save())
            return redirect()->back()->with("error", "Some thnigs was wrong! Please try again later.");

        return redirect()->back()->with("status", "Your comment has been received!");
    }
}
