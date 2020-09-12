<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Profile;

class ProfileController extends Controller
{
    //
    public function add()
    {
        return view('admin.profile.create');
    }

    public function create(Request $request)
    {
        $this->validate($request, Profile::$rules);
        $profiles = new Profile;
        $form = $request->all();
    
     //フォームから送信されてきた_tokenを削除する
        unset($form['_token']);

     //データベースに保存
        $profiles->fill($form);
        $profiles->save();
        
        return redirect('admin/profile/create');
    }

    public function index(Request $request)
    {
      $cond_title = $request->cond_title;
      if ($cond_title != '') {
          $posts = Profile::where('title', $cond_title)->get();
      } else {
          $posts = Profile::all();
      }
      return view('admin.profile.index', ['posts' => $posts, 'cond_title' => $cond_title]);
    }

    public function edit(Request $request)
    {
        //Profile　Modeleからデータを取得
        $profile = Profile::find($request->id);
        if (empty($profile)) {
          abort(404);
        }
        return view('admin.profile.edit', ['profile_form' => $profile]);
    }

    public function update(Request $request)
    {
        //validationをかける
        $this->validate($request,Profile::$rules);
        //Profile Modeleからデータを取得する
         $profile = Profile::find($request->id);
        // 送信されてきたフォームデータを格納する
        $profile_form = $request->all();
       
        // 該当するデータを上書きして保存する
        $profile->fill($profile_form)->save();

        return redirect('admin/profile/edit?id=' . $profile->id);
    }
    
    public function delete(Request $request)
  {
      // 該当するNews Modelを取得
      $profile = Profile::find($request->id);
      // 削除する
      $profile->delete();
      return redirect('admin/profile/');
  }  

}