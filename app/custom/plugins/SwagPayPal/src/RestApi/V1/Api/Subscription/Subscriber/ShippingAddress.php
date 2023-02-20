<?php
declare(strict_types=1);
/*
 * (c) shopware AG <info@shopware.com>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Swag\PayPal\RestApi\V1\Api\Subscription\Subscriber;

use OpenApi\Annotations as OA;
use Swag\PayPal\RestApi\PayPalApiStruct;
use Swag\PayPal\RestApi\V1\Api\Subscription\Subscriber\ShippingAddress\Address;
use Swag\PayPal\RestApi\V1\Api\Subscription\Subscriber\ShippingAddress\Name;

/**
 * @OA\Schema(schema="swag_paypal_v1_subscription_shipping_address")
 * @codeCoverageIgnore
 * @experimental
 *
 * This class is experimental and not officially supported.
 * It is currently not used within the plugin itself. Use with caution.
 */
class ShippingAddress extends PayPalApiStruct
{
    /**
     * @OA\Property(ref="#/components/schemas/swag_paypal_v1_subscription_shipping_address_name", nullable=true)
     */
    protected ?Name $name = null;

    /**
     * @OA\Property(ref="#/components/schemas/swag_paypal_v1_subscription_shipping_address_address", nullable=true)
     */
    protected ?Address $address = null;

    public function getName(): ?Name
    {
        return $this->name;
    }

    public function setName(?Name $name): void
    {
        $this->name = $name;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): void
    {
        $this->address = $address;
    }
}
