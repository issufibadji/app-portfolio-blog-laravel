<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Blog;
use App\Models\Hero;
use App\Models\About;
use App\Models\Service;
use App\Models\Category;
use App\Models\Feedback;
use App\Mail\ContactMail;
use App\Models\SkillItem;
use App\Models\Experience;
use App\Models\TyperTitle;
use Illuminate\Http\Request;
use App\Models\PortfolioItem;
use App\Models\BlogSectionSetting;
use App\Models\SkillSectionSetting;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Models\ContactSectionSetting;
use App\Models\FeedbackSectionSetting;
use App\Models\PortfolioSectionSetting;

class HomeController extends Controller
{
    public function index()
    {
        $hero            = Hero::firstOrNew([]);
        $typerTitles     = TyperTitle::all();
        $services        = Service::latest()->take(6)->get();
        $about           = About::firstOrNew([]);
        $portfolioTitle  = PortfolioSectionSetting::firstOrNew([]);
        $portfolioCategories = Category::all();
        $portfolioItems  = PortfolioItem::all();
        $skill           = SkillSectionSetting::firstOrNew([]);
        $skillItems      = SkillItem::all();
        $experience      = Experience::firstOrNew([]);
        $feedbacks       = Feedback::all();
        $feedbackTitle   = FeedbackSectionSetting::firstOrNew([]);
        $blogs           = Blog::latest()->take(5)->get();
        $blogTitle       = BlogSectionSetting::firstOrNew([]);
        $contactTitle    = ContactSectionSetting::firstOrNew([]);
        return view('frontend.pages.home' , compact('hero', 'typerTitles', 'services', 'about', 'portfolioTitle', 'portfolioCategories', 'portfolioItems', 'skill', 'skillItems', 'experience', 'feedbacks', 'feedbackTitle', 'blogs', 'blogTitle', 'contactTitle' ) );
    }


    public function showPortfolio($id){
        $portfolio = PortfolioItem::findOrFail($id);
        return view('frontend.pages.portfolio-details', compact('portfolio'));
    }

    public function showBlog($id)
    {
        $blog = Blog::findOrFail($id);
        $previousPost = Blog::where('id', '<', $blog->id)->orderBy('id', 'desc')->first();
        $nextPost = Blog::where('id', '>', $blog->id)->orderBy('id', 'asc')->first();
        return view('frontend.pages.blog-details', compact('blog', 'previousPost', 'nextPost'));
    }

    public function blog()
    {
        $blogs = Blog::latest()->paginate(9);
        return view('frontend.pages.blog', compact('blogs'));
    }

    public function portfolio()
    {
        $portfolioCategories = Category::all();
        $portfolioItems = PortfolioItem::latest()->get();
        return view('frontend.pages.portfolio', compact('portfolioCategories', 'portfolioItems'));
    }


    public function contact(Request $request)
    {
       $request->validate([
            'name' => ['required', 'max:200'],
            'subject' => ['required', 'max:300'],
            'email' => ['required', 'email'],
            'message' => ['required', 'max:2000'],
       ]);

       Mail::send(new ContactMail($request->all()));

       return response(['status' => 'success', 'message' => 'Mail Sended Successfully!']);

    }
}
