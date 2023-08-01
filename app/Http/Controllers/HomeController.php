<?php

namespace App\Http\Controllers;

use App\Http\Models\Customer;
use App\Http\Models\Demand;
use App\Http\Models\Partner;
use App\Http\Models\Project;
use Illuminate\Http\Request;

class HomeController extends Controller
{
	public function index()
	{
		return view('admin.home');
	}

	public function getData($action, Request $request)
	{
		return $this->{$action}($request);
	}

	protected function customerQty()
	{
		return Customer::count();
	}

	protected function projectsQty()
	{
		return Project::count();
	}

	protected function partnersQty()
	{
		return Partner::count();
	}

	protected function demandsQty()
	{
		return Demand::count();
	}
}
