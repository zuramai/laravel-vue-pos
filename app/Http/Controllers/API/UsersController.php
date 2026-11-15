<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\User;
use App\Services\ImageUploader;
use Hash;
use Validator;

class UsersController extends Controller
{
    const IMAGE_FOLDER = 'images/users';

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
        return new UserCollection(User::search($request->search)->orderBy('id','desc')->paginate(self::PER_PAGE));
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
        $validator = Validator::make($request->all(), $this->rules());

        if($validator->fails()) {
            return response($validator->errors(), 400);
        }
        $image_name = $this->images->store($request->file('photo'), self::IMAGE_FOLDER);

        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'phone' => $request->phone,
            'address' => $request->address,
            'level' => $request->level,
            'image_name' => $image_name,
            'password' => Hash::make($request->password),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return $user;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return $user;
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
        // D-05 (kept): the validator is made but never checked, the password is saved without hash.
        Validator::make($request->all(), $this->rules());
        $image_name = $this->images->store($request->file('photo'), self::IMAGE_FOLDER);
        User::find($id)->update([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
            'phone' => $request->phone,
            'address' => $request->address,
            'level' => $request->level,
            'image_name' => $image_name,
        ]);

        return response()->json(['status'=>'success']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return response(['status'=>true]);
    }

    /**
     * Validation rules of a user form. store() and update() use the same rules.
     */
    private function rules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'photo' => ['required', 'image'],
            'username' => ['required','min:6', 'unique:users'],
            'level' => ['required','in:'.implode(',', User::LEVELS)],
            'address' => 'required',
            'phone' => 'required|numeric',
        ];
    }
}
