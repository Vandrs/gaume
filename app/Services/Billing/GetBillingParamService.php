<?php 

namespace App\Services\Billing;

use App\Enums\EnumBillingParam;
use App\Models\BillingParam;

class GetBillingParamService
{
	public static function getParam($param)
	{
		return BillingParam::query()
						   ->where('code', '=', $param)
						   ->firstOrFail();
	}
}