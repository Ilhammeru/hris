<?php

namespace Modules\Setting\Http\Controllers;

use App\Models\Menu;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        setTitle(__('setting::messages.dashboard'));
        return view('setting::menu.index');
    }

    /**
     * Function to get data and return datatable
     */
    public function menuAjax()
    {
        $data = Menu::all();
        return DataTables::of($data)
            ->editColumn('name', function($d) {
                return ucfirst($d->name);
            })
            ->editColumn('icon', function($d) {
                $icon = '';
                if ($d->icon == 'bullet') {
                    $icon = '<span class="bullet bullet-dot"></span>';
                } else {
                    $icon = '<i class="bi '. $d->icon .'"></i>';
                }
                return $icon;
            })
            ->editColumn('parent', function($d) {
                $parent = '-';
                if ($d->parent) {
                    $parent = Menu::select('name')->find($d->parent)->name;
                }
                return ucfirst($parent);
            })
            ->addColumn('action', function($d) {
                return '<a class="btn btn-secondary c-btn-sm" href="'. route('setting.menu.edit', $d->id) .'"><i class="bi bi-pen-fill p-0"></i></a>
                <button class="btn btn-secondary c-btn-sm" type="button" onclick="deleteItem('. $d->id .')"><i class="bi bi-trash-fill p-0"></i></button>';
            })
            ->rawColumns(['action', 'icon', 'parent'])
            ->make(true);
    }

    /**
     * Show create menu form
     * @return Renderable
     */
    public function createMenu()
    {
        setTitle(__('setting::messages.create_menu'));

        $parent_list = Menu::where('parent', null)
            ->get();
        $is_edit = false;
        $data = null;

        return view('setting::menu.create', compact('parent_list', 'is_edit', 'data'));
    }

    /**
     * Show edit form
     * @param int id
     * @return Renderable
     */
    public function editMenu($id)
    {
        try {
            setTitle(__('setting::messages.edit_menu'));

            $data = Menu::find($id);
            $parent_list = Menu::where('parent', null)
                ->get();
            $is_edit = true;
            return view('setting::menu.create', compact('data', 'is_edit', 'parent_list'));
        } catch (\Throwable $th) {
            $notify[] = ['Failed', 'Failed to show data'];
            return redirect()->back()->withNotify($notify);
        }
    }

    /**
     * Function to store menu
     * @return Response
     */
    public function menuStore(Request $request)
    {
        try {
            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'url' => 'required',
            ]);
            if ($validate->fails()) {
                $errors = $validate->errors()->all();
                return response()->json($errors, 500);
            }
    
            $menu = new Menu();
            $menu->name = $request->name;
            $menu->url = $request->url;
            $menu->slug = implode('-', explode(' ', strtolower($request->name)));
            $menu->parent = $request->parent;
            $menu->icon = 'bi-c-circle';
            $menu->role = '1|2|3';
            $menu->save();

            setUserMenu(auth()->user());
    
            return response()->json('success');
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('setting::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('setting::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('setting::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function updateMenu(Request $request, $id)
    {
        try {
            $validate = Validator::make($request->all(), [
                'name' => 'required',
                'url' => 'required',
            ]);
            if ($validate->fails()) {
                $errors = $validate->errors()->all();
                return response()->json($errors, 500);
            }
    
            $menu = Menu::find($id);
            $menu->name = $request->name;
            $menu->url = $request->url;
            $menu->slug = implode('-', explode(' ', strtolower($request->name)));
            $menu->parent = $request->parent;
            $menu->icon = 'bi-c-circle';
            $menu->role = '1|2|3';
            $menu->save();

            setUserMenu(auth()->user());
    
            return response()->json($menu);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function deleteMenu($id)
    {
        try {
            Menu::where('id', $id)
                ->delete();
            return response()->json(['message' => __('setting::messages.success_delete')]);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }
}
