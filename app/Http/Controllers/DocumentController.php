<?php

namespace App\Http\Controllers;

use App\Models\User\ExportUserKey;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;
use LaravelLocalization;

class DocumentController extends Controller
{
    public function index()
    {
        $company = auth()->user()->getCompany;
        SEOTools::setTitle(trans('documents.page_name'));
        return view('documents.index', compact('company'));
    }

    public function getFeeds()
    {
        $user = auth()->user();
        ExportUserKey::create([
            'user' => '#'.$user->id.' '.$user->name.', '.$user->email,
            'groups' => 'Запит на файл імпорту',
            'date_add' => Carbon::now()->timestamp,
            'language' => LaravelLocalization::getCurrentLocale() == 'ua'?'uk':LaravelLocalization::getCurrentLocale(),
            'new' => 1
        ]);

        return redirect()->back()->with('status', trans('documents.get_feeds_success'));
    }
}
