<?php

namespace App\Http\Controllers;

use App\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'documents' => 'required',
            'documents.*' => 'mimes:doc,pdf,docx,zip,jpeg,png,bmp,gif,svg'
        ]);
        if($request->hasfile('documents'))
        {
            foreach($request->file('documents') as $file)
            {
                $ext = $file->getClientOriginalExtension();
                $store = $file->storeAs('documents', auth()->id() . time() . ".{$ext}", 's3', 'public');
                $store = "https://s3.us-east-2.amazonaws.com/fgcibucket/" . $store;
                $data[] = $store;
            }
        }
        $documents = json_encode($data);
        $data = Document::create(['documents' => $documents, 'owner_id' => auth()->id()]);
        return response()->json([
            'success' => 'Your files have been successfully added',
            'data' => $data,
        ]);
    }
    
    public function fetchMyDocuments()
    {
        $documents = Document::where('owner_id', auth()->id())->get();
        $data = [];
        foreach($documents as $document) {
            $data[] = $document->documents;
        }
        $result = array ($data);
        return response()->json([
            'success' => 'Successfully fetched',
            'data' => json_decode($documents),
        ]);
    }
}
