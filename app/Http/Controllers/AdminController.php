<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use Egulias\EmailValidator\Exception\ConsecutiveAt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_organizations()
    {
        $organizations = Organization::all();
        return $organizations;
    }
    public function get_users()
    {
        $users = User::all();
        return $users;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get_directors()
    {
        $directors = DB::table('users')
            ->join('organizations', 'users.organization_id', '=', 'organizations.id')
            ->select('users.*', 'organizations.org_name as organization_name')
            ->where('users.role_id', '=', '2')
            ->get();

        return $directors;
    }

    

    public function get_managers()
    {
        $managers = DB::table('users')
            ->join('organizations', 'users.organization_id', '=', 'organizations.id')
            ->select('users.*', 'organizations.org_name as organization_name')
            ->where('users.role_id', '=', '3')
            ->get();

        return $managers;
    }

    public function get_workers()
    {
        $workers = DB::table('users')
            ->join('organizations', 'users.organization_id', '=', 'organizations.id')
            ->select('users.*', 'organizations.org_name as organization_name')
            ->where('users.role_id', '=', '4')
            ->get();

            // $workers = User::where('role_id', 4)
            // ->with('organization')
            // ->get(['*', 'organizations.org_name as organization_name']);

        return $workers;
    }
    
    public function get_organization_members($organization_id)
    {
        $members = DB::table('users')
            ->where('organization_id', '=', $organization_id)
            ->get();
        return $members;
    }

    public function recent_users(){
        $recentUsers = User::orderBy('created_at', 'desc')->take(10)->get();
        return @$recentUsers;
    }

    public function user_data()
    {
        $organization_count = Organization::count();
        $total_users = User::count();
        $total_admins = User::where('role_id', '=', '1')->count();
        $total_directors = User::where('role_id', '=', '2')->count();
        $total_managers = User::where('role_id', '=', '3')->count();
        $total_workers = User::where('role_id', '=', '4')->count();

        // $results = User::selectRaw('COUNT(*) as total_users, 
        //                     COUNT(CASE WHEN role_id = 1 THEN 1 END) as total_admins,
        //                     COUNT(CASE WHEN role_id = 2 THEN 1 END) as total_directors,
        //                     COUNT(CASE WHEN role_id = 3 THEN 1 END) as total_managers,
        //                     COUNT(CASE WHEN role_id = 4 THEN 1 END) as total_workers')
        //         ->get();

        $data = [
            'organization_count' => $organization_count,
            'total_users' => $total_users,
            'total_admins' => $total_admins,
            'total_directors' => $total_directors,
            'total_managers' => $total_managers,
            'total_workers' => $total_workers,
        ];

        return response()->json($data);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }
        
        if($id == 2){
            $org = Organization::where('id',$user->organization_id)->first(); // Use `first()` instead of `get()` as we only need one organization object
            $users = User::where('organization_id','=',$org->id)->get();
            foreach ($users as $user) {
                $user->delete();
            }
            $org->delete();
            return response()->json(['message' => 'Organization and its users have been deleted.']);
        }
        $user->delete();
        return response()->json(['message' => 'User deleted successfully.']);
    }
}