<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Artesaos\SEOTools\Facades\SEOTools;

class DocumentController extends Controller
{
    public function index()
    {
        $company = auth()->user()->getCompany;
        SEOTools::setTitle(trans('documents.page_name'));
        return view('documents.index', compact('company'));
    }
}
