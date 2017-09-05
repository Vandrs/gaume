<?php

namespace App\Enums;

use Lang;

class EnumTransactionStatus {

	const INITIALIZED = 0;
    const AWAITING_PAYMENT = 1;
    const REVIEW = 2;
    const PAID = 3;
    const AVAIBLE = 4;
    const DISPUTE = 5;
    const RETURNED = 6;
    const CANCELED = 7;
    const SELLER_CHARGEBACK = 8;
    const CONTESTATION = 9;

    public static function getLabel($id)
    {
    	return Lang::get('transaction.label.'.$id);
    }

}