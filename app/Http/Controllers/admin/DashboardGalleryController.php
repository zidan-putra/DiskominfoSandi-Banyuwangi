<?php

namespace App\Http\Controllers\admin;

use App\Models\Gallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DashboardGalleryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $galleries = Gallery::latest()->filter(request(['search','type']))->paginate(7)->withQueryString();
        $galleryCount = Gallery::latest()->filter(request(['search','type']))->count();
        return view('admin.pages.galeri.galeri', [
            'galleries' => $galleries,
            'galleryCount' => $galleryCount,
            'title' => 'Semua galeri',
            'pageAction' => 'Gallery'
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.pages.galeri.tambah-galeri',[
            'title' => 'Tambah galeri',
            'pageAction' => 'Tambah galeri'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        // return response()->json(['test' => $request->type]);
        // if($request->wantsJson()){
        //     return response()->json(['text1' => 'request ini mengharapkan hasil berupa json']);
        // }

        // return response()->json(['test' => $request->wantsJson()]);
        $validated = '';
        
        if($request->type == 'image'){
            $validated = $request->validate([
                'title' => 'min:2|required',
                'slug' => 'required',
                'type' => 'required',
                'body' => 'required',
                'source' => 'url',
            ]);
        }elseif($request->type == 'video'){
            $validated = $request->validate([
                'title' => 'min:2|required',
                'slug' => 'required',
                'type' => 'required',
                'body' => 'required',
                'source' => 'image|file'
            ]);
        }else{
            return json_encode(['error' => 'ada error dengan typenya']);
        }

        if($validated->fails()){
            return response()->json(['errors' => $validated->getMessageBag()->toArray()]);
        }


        return json_encode(['success' => 'data berhasil divalidasi']);
        // if($request->hasFile('source')){
        //     return json_encode([$request->all(), $request->file('source')]);
        // }
        // return json_encode([$request->all()]);

        // if($request->file('source')){
        //     if($request->type == 'image'){
        //         $request->file('source')->storeAs('gallery-src', str_replace(' ', '-',$request->name).".jpg"); //simpan nama file ke array validated, jadi bukan file yg disimpan
        //         $validated['source'] = str_replace(' ', '-',$request->name).".jpg";
        //     }else{
        //         $validated['source'] = $request->source;
        //     }
        // }

        // //memasuakn data ke database
        // Gallery::create($validated);
        // $request->session()->flash('success', 'data berhasil ditambah');
        // return response()->json([$validated, 'success' => true]); //respon menggunakan json


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Gallery $galeri)
    {
        return response()->json([$galeri]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Gallery $galeri)
    {
        if($galeri->source){
            Storage::delete('gallery-src/'.$galeri->source);
        }

        Gallery::destroy($galeri->id);

        return redirect('/admin/galeri')->with('success', 'data telah berhasil dihapus');
    }
}
