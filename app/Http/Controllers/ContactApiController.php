<?php

namespace App\Http\Controllers;

use App\Http\Resources\ContactResource;
use App\Models\ContactApi;
use App\Http\Requests\StoreContactApiRequest;
use App\Http\Requests\UpdateContactApiRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ContactApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $contacts = ContactApi::latest('id')->paginate(10)->each(function ($contact){
//           if ($contact->photo === null) {
//               $contact->photo = asset('default.png');
//           }else{
//               $contact->photo = asset('storage/profile/'.$contact->photo);
//           }
//           $contact->makeHidden(['created_at','updated_at']);
//            $contact->date = $contact->created_at->format('J M Y');
//            $contact->time = $contact->created_at->format('H i s');
//        });
        $contacts = ContactResource::collection(ContactApi::latest('id')->paginate(10));
        return response()->json([
            'message' => 'success',
            'data' => $contacts
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreContactApiRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreContactApiRequest $request)
    {
        $request->validate([
           'name' => 'required',
           'phone' => 'required|min:11',
           'photo' => 'nullable|file|mimes:jpg,png,jpeg'
        ]);

        $contact = new ContactApi();
        $contact->name = $request->name;
        $contact->phone = $request->phone;
        if ($request->hasFile('photo')){
            $newName = "profile_".uniqid().".".$request->file('photo')->extension();
            $request->file('photo')->storeAs('public/profile',$newName);
            $contact->photo = $newName;
        }
        $contact->save();

        return response()->json(['message'=>'success create'],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ContactApi  $contactApi
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $contactApi = ContactApi::find($id);
        if (is_null($contactApi)){
            return response()->json([
               'message' => 'failed'
            ],404);
        }
        return response()->json([
            'message' => 'find contact',
            'data' => new ContactResource($contactApi)
        ],200);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateContactApiRequest  $request
     * @param  \App\Models\ContactApi  $contactApi
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateContactApiRequest $request, $id)
    {
        $request->validate([
            'name' => 'nullable',
            'phone' => 'nullable|min:11',
            'photo' => 'nullable|file|mimes:jpg,png,jpeg'
        ]);
        $contactApi = ContactApi::find($id);
        if (is_null($contactApi)){
            return response()->json(['message'=>'failed update'],404);
        }
        if ($request->name){
            $contactApi->name = $request->name;
        }
        if ($request->phone){
            $contactApi->phone = $request->phone;
        }
        if ($request->hasFile('photo')){
            Storage::delete('public/profile'.$contactApi->photo);
            $newName = "profile_".uniqid().".".$request->file('photo')->extension();
            $request->file('photo')->storeAs('public/profile',$newName);
            $contactApi->photo = $newName;
        }
        $contactApi->update();

        return response()->json(['message'=>'success updated'],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ContactApi  $contactApi
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContactApi $contactApi)
    {
        $contactApi->delete();

        return response()->json(['message'=>'success deleted'],204);
    }
}
