<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FileServer;

class FileServerController extends Controller
{
    // Ipakita ang file server page at listahan ng mga files
    public function index()
    {
        $files = FileServer::latest()->get();
        return view('file-server.index', compact('files'));
    }

    // I-save ang in-upload na file (hanggang 1GB para sa 10-min videos)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,mp4,mov,avi|max:1048576',
        ]);

        $path = $request->file('file')->store('file_server_uploads', 'public');
        $extension = $request->file('file')->getClientOriginalExtension();

        FileServer::create([
            'title' => $request->title,
            'file_path' => $path,
            'file_type' => strtolower($extension),
        ]);

        return redirect()->back()->with('success', 'Matagumpay na na-upload ang file sa File Server!');
    }
}