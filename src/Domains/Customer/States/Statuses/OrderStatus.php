<?php

declare(strict_types=1);

namespace Domains\Customer\States\Statuses;

use Spatie\Enum\Laravel\Enum;

/**
 * @method static self pending()
 * @method static self declined()
 * @method static self completed()
 * @method static self cancelled()
 * @method static self refunded()
 */
final class OrderStatus extends Enum
{
}
