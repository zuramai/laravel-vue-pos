<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryCollection;
use App\Model\Category;
use App\Services\ImageUploader;

class CategoryController extends Controller
{
    const IMAGE_FOLDER = 'images/categories';

    private $images;

    public function __construct(ImageUploader $images)
    {
        $this->images = $images;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return new CategoryCollection(Category::search($request->get('search'))->orderBy('id','desc')->paginate(self::PER_PAGE));
    }

    /** Registered by Route::resource. Not used by the UI: answers 200 with an empty body. */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'photo' => 'required',
        ]);
        $name =  $request->name;
        $slug = str_slug($name,'-');
        $description = $request->description;
        $imageName = $this->images->store($request->file('photo'), self::IMAGE_FOLDER);

        $insert = Category::create([
            'name' => $name,
            'description' => $description,
            'slug' => $slug,
            'image_name' => $imageName
        ]);

        return $insert;
    }

    /** Registered by Route::resource. Not used by the UI: answers 200 with an empty body. */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return Category::findOrFail($id);
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
        $name = $request->name;
        $description = $request->description;
        $slug = str_slug($name);

        if($request->hasFile('photo')) {
            $image_name = $this->images->store($request->file('photo'), self::IMAGE_FOLDER);

            Category::find($id)->update([
                'name' => $name,
                'description' => $description,
                'slug' => $slug,
                'image_name' => $image_name
            ]);
        }else{
            Category::find($id)->update([
                'name' => $name,
                'description' => $description,
                'slug' => $slug,
            ]);

        }

        return response(['success'=>true], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $cat = Category::find($id);
        $cat->delete();

        return [
            'status' => 'true',
            'msg' => 'Kategori '.$cat->name.' Berhasil dihapus'
        ];
    }
}
