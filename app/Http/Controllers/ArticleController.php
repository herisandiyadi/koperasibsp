<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\User;
use Illuminate\Http\Request;
use App\Article;
use NotificationChannels\OneSignal\OneSignalChannel;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $policys = Article::get();
        return view('article.article-list', compact('policys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('article.article-create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required|unique:articles|max:255',
            'content' => 'required|min:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
		$name = '';
        if ($request->hasfile('images')) {
            $file = $request->file('images');
            $name = time() . $file->getClientOriginalName();
            $file->move(public_path() . '/images/news/', $name);
		}

        $article = new Article;
        $article->title = $request->get('title');
        $article->content = $request->get('content');
		$article->image_name = $name;
        $article->tags = $request->get('tags');
		$article->author = auth()->user()->name;
		if($request->publish)
        {
            $article->published_at = now();
            $article->isShow = 1;
        }else{
		    $article->isShow = false;
        }
        $article->save();

        session()->flash('success', trans('response-message.success.create',['object'=>'Article']));
        return redirect('article');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GeneralSetting  $generalSetting
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GeneralSetting  $generalSetting
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $article = Article::findOrFail($id);
        return view('article.article-edit', compact('article'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GeneralSetting  $generalSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		$article = Article::findOrFail($id);
        $validator = \Validator::make($request->all(), [
            'title' => 'required|unique:articles,title,'.$article->id.'|max:255',
            'content' => 'required|min:10',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
		if ($request->hasfile('images')) {
		    if($article->image_name != "")
            {
                $cekimage = public_path('images/news/'.$article->image_name);
                if(file_exists($cekimage)){
                    unlink($cekimage);
                }
            }
			$file = $request->file('images');
            $name = time() . $file->getClientOriginalName();
            $file->move(public_path() . '/images/news/', $name);
		}else{
			$name = $article->image_name;
		}
		$article->title = $request->get('title');
        $article->content = $request->get('content');
		$article->image_name = $name;
        $article->tags = $request->get('tags');
		$article->author = auth()->user()->name;
		if($request->publish && !$article->isPublished())
        {
           $article->isShow = true;
           $article->published_at = now();
        }else{
		    $article->isShow = false;
		    $article->published_at = null;
        }
		$article->update();

        session()->flash('success', trans('response-message.success.update',['object'=>'Article']));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GeneralSetting  $generalSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
		$article = Article::findOrFail($id);
		if(!empty($article->image_name))
        {
            $cekimage = public_path().'/images/news/'.$article->image_name;
            if(file_exists($cekimage)){
                unlink($cekimage);
            }
        }
        if (Article::destroy($id)) {
            $data = 'Success';
         }else{
             $data = 'Failed';
         }
        return response()->json($data);
	}

	public function datatable($policies)
    {
        if($policies == 'all')
        {
            $policies = Article::all();
		}
        return \DataTables::of($policies)
            ->editColumn('published_at', function($article){
                if(!$article->isPublished())
                {
                    return '';
                }
                return $article->published_at->format('Y-m-d H:i:s');
            })
            ->editColumn('title', function($article){
                return $article->title;
            })
			->editColumn('tags', function($article){
			    $labels = '';
			    if(is_null($article->tags))
                {
                    return '';
                }
			    foreach ($article->tags as $tag)
                {
                    $labels= $labels.' <span class="label label-success">'.$tag.'</span>';
                }
                return $labels;
            })
            ->addColumn('status', function($article){
                $showUI = '';
                $publishUI = '<span class="label label-info">Draft</span>';
//                if($article->isShow){
//                    $showUI = '<span class="label label-info">Aktif</span>';
//                }
                if($article->isPublished())
                {
                    $publishUI = '<span class="label label-info" data-toggle="tooltip" title="'.$article->published_at->format('d-M-Y H:i:s').'">Diterbitkan</span>';
                }
                return $showUI.' '.$publishUI;
            })
            ->addColumn('action', function($article){
                $deleteUrl = url('article').'/'.$article->id;
                $editUrl = url('article').'/'.$article->id.'/edit';
                $publishUrl = url('article').'/'.$article->id.'/publish/0';
                $publishAndBlastUrl = url('article').'/'.$article->id.'/publish/1';
                $btnEdit = '';
                $btnDelete = '';
                $btnPublish = '<a class="btn btn-sm btn-warning" href="'.$publishUrl.'" data-toggle="tooltip" title="Terbitkan"><i class="fa fa-newspaper-o"></i></a>';
                $btnPublishBlast = '<a class="btn btn-sm btn-warning" href="'.$publishAndBlastUrl.'" data-toggle="tooltip" title="Terbit dan Sebarkan"><i class="fa fa-paper-plane-o"></i></a>';
                if(auth()->user()->can('edit.master.article')){
                    $btnEdit = '<a class="btn btn-sm btn-primary" href="'.$editUrl.'"><i class="fa fa-edit"></i></a>';
                }
                if(auth()->user()->can('delete.master.article')){
                    $btnDelete = '<button class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="destroyData('."'article'".','."'".$article->id."'".','."'". csrf_token() ."'".','."'dtArticle'".')"><i class="fa fa-trash" data-token="{{ csrf_token() }}"></i></button>';
//                    $btnDelete = '<form method="POST" action="'.$deleteUrl.'" class="form-inline">'.csrf_field().method_field('DELETE').'<button class="btn btn-danger" type="submit"><i class="fa fa-trash"></i></button> </form>';
                }
                if($article->isPublished())
                {
                    return $btnEdit.' '.$btnDelete.' '.$btnPublishBlast;
                }
				return $btnEdit.' '.$btnDelete.' '.$btnPublish.' '.$btnPublishBlast;
            })->make(true);
	}

	public function publish(Request $request, $id, $blast = false)
    {
        $article = Article::findOrFail($id);
        $article->update(['isShow'=> true,'published_at'=> now()]);
        if($blast)
        {
			$users = User::fMemberOnly()->whereNotNull('os_token')->get();
            $article->blastTo($users,['mail',OneSignalChannel::class, 'database']);
        }
        session()->flash('success', 'Artikel berhasil diterbitkan!');
        return redirect()->back();
    }

    public function getImage($id)
    {
        $article = Article::findOrFail($id);
        if($article->image_name =='' || is_null($article->image_name))
        {
            return ResponseHelper::json('', 404, trans('response-message.notfound.file'));
        }
        return response()->download(public_path('images/news/'.$article->image_name));
    }
}
