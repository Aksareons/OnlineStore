<?php
namespace App\Domain\Order\Payment;

use App\Domain\Order\Invoice;
use App\Domain\Shared\Money;

interface PaymentMethodInterface
{
    public function pay(Money $paidAmount): void;
}
