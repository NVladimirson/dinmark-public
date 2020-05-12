<?php

namespace App\Http\Controllers;


use App\Models\FAQ\FAQQuestion;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Http\Request;
use LaravelLocalization;

class FAQController extends Controller
{
	public function index()
	{
		$questions = [];
		$lang = LaravelLocalization::getCurrentLocale() == 'ua'?'uk':LaravelLocalization::getCurrentLocale();
		$questionsModel = FAQQuestion::with(['content'])
			->where([
				['wl_alias',35],
				['active',1],
			])
			->orderBy('position')
			->get();
		foreach ($questionsModel as $questionModel){
			$content = $questionModel->content->where('language',$lang)->where('alias',$questionModel->wl_alias)->first();
			$questions[] = [
				'id' => $questionModel->id,
				'question' => $content?$content->name:'',
				'answer' => $content?$content->text:'',
			];;

		}


		SEOTools::setTitle(trans('faq.page_name'));
		return view('faq',compact('questions'));
    }
}
