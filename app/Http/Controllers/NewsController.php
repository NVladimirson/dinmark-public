<?php

namespace App\Http\Controllers;

use App\Models\News\News;
use App\Services\News\NewsServices;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;

class NewsController extends Controller
{
	public function index()
	{
		$newsData = [];
		$news = News::with(['content'])
			->where([
				//['target','<>','site'],
				['target','=','b2b'],
				['active',1],
			])->orWhere([
				['target','=','both'],
				['active',1],
			])
			->orderBy('position','desc')
			->paginate(9);

		foreach ($news as $news_item){
			$content = NewsServices::getContent($news_item);
			if($content){
				$newsData[] = [
					'id'	=> $news_item->id,
					'name'	=> $content->name,
					'text'	=> $content->list,
					'image' => NewsServices::getImagePath($news_item)
				];
			}else{
				$newsData[] = [
					'id'	=> $news_item->id,
					'name'	=> '',
					'text'	=> '',
					'image' => NewsServices::getImagePath($news_item)
				];
			}
		}


		SEOTools::setTitle(trans('news.index_page_name'));
		return view('news.index',compact('news','newsData'));
    }

	public function show($id)
	{

		$news = News::with(['content'])
			->find($id);
		if(empty($news)){
			abort(404);
		}
		$content = NewsServices::getContent($news);
		if($content){
			$newsData = [
				'id'	=> $news->id,
				'name'	=> $content->name,
				'text'	=> $content->text,
				'image' => NewsServices::getImagePath($news)
			];
		}else{
			$newsData = [
				'id'	=> $news->id,
				'name'	=> '',
				'text'	=> '',
				'image' => NewsServices::getImagePath($news)
			];
		}


		SEOTools::setTitle($newsData['name']);
		return view('news.show',compact('news','newsData'));
    }
}
