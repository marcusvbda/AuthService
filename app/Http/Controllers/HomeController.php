<?php

namespace App\Http\Controllers;

use App\Enums\DemandStatus;
use App\Http\Models\Customer;
use App\Http\Models\Demand;
use App\Http\Models\Partner;
use App\Http\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
	public function index(Request $request)
	{
		$user = Auth::user();
		$qtyDemands = $this->demandsQty($request, [DemandStatus::open->name, DemandStatus::inprogress->name]);
		return view('admin.home', compact('user', 'qtyDemands'));
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

	protected function demandsQty(Request $request, $status = false)
	{
		if ($status) {
			return Demand::whereIn('status', $status)->count();
		}
		return Demand::count();
	}
}
