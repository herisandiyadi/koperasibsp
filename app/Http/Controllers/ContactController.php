<?php

namespace App\Http\Controllers;

use App\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $contacts = Contact::with('fromId')->FByUser($user->id)->get();
        return view('contact.contact-list', compact('contacts'));

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contact = Contact::find($id);
        return view('contact.contact-show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function edit(Contact $contact)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Contact $contact)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Contact  $contact
     * @return \Illuminate\Http\Response
     */
    public function destroy(Contact $contact)
    {
        //
    }


    public function datatable($contacts)
    {
        if($contacts === 'all')
        {
            $user = auth()->user();
            $contact = Contact::with('fromId')->FByUser($user->id)->get();
        }
        return \DataTables::of($contact)
            ->editColumn('dari', function($contact){
                return $contact->fromId->name;
            })
            ->editColumn('judul', function($contact){
                return $contact->judul;
            })
            ->editColumn('tags', function($contact){
                return $contact->pesan;

            })
            ->addColumn('action', function($contact){
                $viewUrl = url('contact').'/'.$contact->id.'/show';
                $btnView = '';
                if(auth()->user()->can('edit.master.contact')){
                    $btnView = '<a class="btn btn-sm btn-primary" href="'.$viewUrl.'"><i class="fa fa-eye"></i></a>';
                }

                return $btnView;
            })->make(true);
    }
}
