<?php

namespace App\Http\Controllers\Backend\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Offer;
use App\Models\Product;
use App\Models\Store;
use App\Models\Submit;
use App\Models\Vote;
use App\Repositories\Backend\Category\CategoryRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
/**
 * Class RoleController.
 */
class ApicategoryController extends Controller
{
    protected $categories;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categories = $categoryRepository;
    }

    public function loadDeals(Request $request)
    {
        $input = $request->all();
        $token = $input['token'];

        try{
            $user = JWTAuth::toUser($token);
        }
        catch (JWTException $e)
        {
            return response()->json(['result' => false, 'message' => 'Incorrect Credential Info.']);
        }

        $votes = DB::table('votes')->where('user_id', $user->id)->get();

        $myDeals = new Collection();

        foreach ($votes as $vote)
        {
            $deal = Offer::where('product_id', $vote->product_id)->first();
            if($deal != null)
            {
                $productinfo = Product::where('id', $deal->product_id)->first();
                $brandinfo = Brand::where('id', $productinfo->brand)->first();
                $productinfo['brandinfo'] = $brandinfo;
                $deal['product'] = $productinfo;
                $myDeals->push($deal);
            }
        }
        return response()->json(['result' => true, 'deals' => $myDeals]);
    }

    public function loadProductByCategory(Request $request)
    {
        $input = $request->all();
        $token = $input['token'];
        $currentPage = $input['curpage'];
        $totalPerPage = $input['totalperpage'];
        $category = $input['categoryid'];

        try{
            $user = JWTAuth::toUser($token);
        }
        catch (JWTException $e)
        {
            return response()->json(['result' => false, 'message' => 'Incorrect Credential Info.']);
        }

        Paginator::currentPageResolver(function() use ($currentPage) {
            return $currentPage;
        });

        $votes = Vote::where('user_id', $user->id)->get();
        $products = Product::where('category', $category);//->paginate(2);

        $products = $products->where(function($productQuery) use ($votes)
        {
            foreach($votes as $vote)
            {
                $productQuery->where('id', '<>', $vote->product_id);
            }
        });
        $products = $products->paginate($totalPerPage);

        //$products = DB::table('products')->where('category',$category)->paginate($totalPerPage);

        foreach ($products as $product)
        {
            /*$product->voted = 0;
            $votes = Vote::where('user_id', $user->id)->where('product_id', $product->id)->get();
            if($votes != null && $votes->count() > 0)
            {
                $product->voted = 1;
            }*/

            $brandinfo = Brand::where('id', $product->brand)->first();
            $product->brandinfo = $brandinfo;
        }

        return response()->json(['result' => true, 'products' => $products]);
    }

    public function loadBrands(Request $request)
    {
        $input = $request->all();
        $token = $input['token'];

        try{
            $user = JWTAuth::toUser($token);
        }
        catch (JWTException $e)
        {
            return response()->json(['result' => false, 'message' => 'Incorrect Credential Info.']);
        }

        $brands = Brand::orderby('name', 'ASC')->get();

        $curchar = "";
        $brandByChar = new Collection();
        $brandByChar['FirstChar'] = $curchar;
        $brandByChar['brands'] = new Collection();

        $returnBrands = new Collection();
        foreach ($brands as $brand)
        {
            $brandname = $brand['name'];
            $firstchar = strtoupper(substr($brandname, 0,1));
            if($curchar != $firstchar)
            {
                if($brandByChar['brands']->count() > 0)
                {
                    $returnBrands->push($brandByChar);
                }
                $curchar = $firstchar;
                $brandByChar = new Collection();
                $brandByChar['FirstChar'] = $curchar;
                $brandByChar['brands'] = new Collection();
            }
            $brandByChar['brands']->push($brand);
        }

        if($brandByChar['brands']->count() > 0)
        {
            $returnBrands->push($brandByChar);
        }

        return response()->json(['result' => true, 'branddata' => $returnBrands]);
    }

    public function loadsubCategory(Request $request)
    {
        $input = $request->all();
        $token = $input['token'];
        $mainCategoryId = $input['categoryId'];
        try{
            $user = JWTAuth::toUser($token);
        }
        catch (JWTException $e)
        {
            return response()->json(['result' => false, 'message' => 'Incorrect Credential Info.']);
        }

        $categories = Category::where('parent', $mainCategoryId)->orderBy('sort', 'ASC')->get();

        if($categories != null)
        {
            foreach ($categories as $category)
            {
                $category['hasChild'] = 0;
                $subCategories = Category::where('parent', $category->id)->get();
                if($subCategories != null)
                {
                    if($subCategories->count() > 0)
                    {
                        $category['hasChild'] = 1;
                    }
                }
            }
        }
        return response()->json(['result' => true, 'categories' => $categories]);
    }

    public function loadCategory(Request $request)
    {
        $input = $request->all();
        $token = $input['token'];

        try{
            $user = JWTAuth::toUser($token);
        }
        catch (JWTException $e)
        {
            return response()->json(['result' => false, 'message' => 'Incorrect Credential Info.']);
        }

        $maincategories = Category::where('parent', 0)->orderBy('sort', 'ASC')->get();
        if($maincategories != null)
        {
            foreach ($maincategories as $maincategory)
            {
                $maincategory['hasChild'] = 0;
                $subCategories = Category::where('parent', $maincategory->id)->get();
                if($subCategories != null)
                {
                    if($subCategories->count() > 0)
                    {
                        $maincategory['hasChild'] = 1;
                    }
                }
            }
        }
        return response()->json(['result' => true, 'categories' => $maincategories]);
    }

    public function loadmyList(Request $request)
    {
        $input = $request->all();
        $token = $input['token'];

        try{
            $user = JWTAuth::toUser($token);
        }
        catch (JWTException $e)
        {
            return response()->json(['result' => false, 'message' => 'Incorrect Credential Info.']);
        }

        $votes = DB::table('votes')->where('user_id', $user->id)->get();


        foreach ($votes as $vote)
        {
            $productinfo = Product::where('id', $vote->product_id)->first();
            $brandinfo = Brand::where('id', $productinfo->brand)->first();
            $productinfo->brandinfo = $brandinfo;
            $vote->product = $productinfo;
        }
        return response()->json(['result' => true, 'myvotes' => $votes]);
    }

    public function unvoteOnProduct(Request $request)
    {
        $input = $request->all();
        $token = $input['token'];
        $voteid = $input['voteid'];

        $vote = Vote::where('id', $voteid)->delete();

        return response()->json(['result' => true]);
    }

    public function voteOnProduct(Request $request)
    {
        $input = $request->all();
        $token = $input['token'];
        $productid = $input['productid'];


        try{
            $user = JWTAuth::toUser($token);
        }
        catch (JWTException $e)
        {
            return response()->json(['result' => false, 'message' => 'Incorrect Credential Info.']);
        }

        $userid = $user->id;
        $productinfo = Product::where('id', $productid)->first();

        $vote = Vote::create([
            'product_id' => $productid,
            'user_id' => $userid,
            'category' => $productinfo->category,
            'store' => $productinfo->store
        ]);

        return response()->json(['result' => true]);
    }

    public function submitProduct(Request $request)
    {
        $input = $request->all();
        $token = $input['token'];
        $categoryid = $input['categoryid'];
        $url = $input['url'];
        $description = $input['description'];

        try{
            $user = JWTAuth::toUser($token);
        }
        catch (JWTException $e)
        {
            return response()->json(['result' => false, 'message' => 'Incorrect Credential Info.']);
        }

        $userid = $user->id;

        $submit = Submit::create([
            'user_id' => $userid,
            'category_id' => $categoryid,
            'submit_url' => $url,
            'updated' => 0,
            'description' => $description
        ]);

        return response()->json(['result' => true]);
    }

    function addPromoClicks(Request $request)
    {
        $input = $request->all();
        $token = $input['token'];
        $offerid = $input['offerid'];
        try{
            $user = JWTAuth::toUser($token);
        }
        catch (JWTException $e)
        {
            return response()->json(['result' => false, 'message' => 'Incorrect Credential Info.']);
        }
        $visits = Promovisit::where('userid', $user->id)->where('offerid', $offerid)->first();
        $offer = Offer::where('id', $offerid)->first();
        if($visits == null)
        {
            $provisit = Promovisit::create([
                'userid' => $user->id,
                'offerid' => $offerid,
                'seller' => $offer->seller
            ]);
        }
        return response()->json(['result' => true]);
    }

    function addProductShares(Request $request)
    {
        $input = $request->all();
        $token = $input['token'];
        $productid = $input['productid'];

        try{
            $user = JWTAuth::toUser($token);
        }
        catch (JWTException $e)
        {
            return response()->json(['result' => false, 'message' => 'Incorrect Credential Info.']);
        }
        $shares = Share::where('userid', $user->id)->where('product_id', $productid)->first();
        $product = Product::where('id', $productid)->first();
        $store = Store::where('id', $product->store)->first();
        $seller = $store->seller;

        //if($visits == null)
        //{
        $share = Share::create([
            'user_id' => $user->id,
            'product_id' => $productid,
            'seller' => $seller
        ]);
        //}
        return response()->json(['result' => true]);
    }
}
