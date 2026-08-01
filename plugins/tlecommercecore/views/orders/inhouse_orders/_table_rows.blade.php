@if ($orders->count() > 0)
    @foreach ($orders as $key => $order)
        @php
            $shippingCourierOrder = \Plugin\TlcommerceCore\Models\ShippingCourierOrders::where('order_id', $order->id)->first();
            $deliveryStatuses = config('tlecommercecore.order_delivery_status');
            $currentDelivery = (int) $order->delivery_status;
            $deliveryBadgeMap = [
                $deliveryStatuses['pending'] => ['class' => 'badge-pending', 'label' => translate('Pending'), 'icon' => 'fa-check-circle'],
                $deliveryStatuses['processing'] => ['class' => 'badge-processing', 'label' => translate('Processing'), 'icon' => 'fa-exclamation-circle'],
                $deliveryStatuses['ready_to_ship'] => ['class' => 'badge-ready', 'label' => translate('Ready To Ship'), 'icon' => 'fa-exclamation-circle'],
                $deliveryStatuses['shipped'] => ['class' => 'badge-shipped', 'label' => translate('Shipped'), 'icon' => 'fa-exclamation-circle'],
                $deliveryStatuses['delivered'] => ['class' => 'badge-delivered', 'label' => translate('Delivered'), 'icon' => 'fa-exclamation-circle'],
                $deliveryStatuses['cancelled'] => ['class' => 'badge-cancelled', 'label' => translate('Cancelled'), 'icon' => 'fa-exclamation-circle'],
            ];
            $currentDeliveryBadge = $deliveryBadgeMap[$currentDelivery] ?? ['class' => 'badge-pending', 'label' => translate('Pending'), 'icon' => 'fa-check-circle'];
        @endphp
        <tr data-order-id="{{ $order->id }}">
            <!-- <td style="width: 10px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                <div class="d-flex align-items-center ">
                    <label class="position-relative m-0">
                        <input type="checkbox" name="items[]" class="item-id"
                            value="{{ $order->id }}">
                        <span class="checkmark"></span>
                    </label>
                </div>
            </td> -->
            <td style="width: 50px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                <a title="View Details" class="order-link"
                    href="{{ route('plugin.tlcommercecore.orders.details', ['id' => $order->id]) }}">
                    {{ $order->order_code }}
                    <!-- @if ($order->read_at == null)
                        <span class="badge" style="background-color: #FF5A1F; color: #fff;">{{ translate('New') }}</span>
                    @endif -->
                </a>

            </td>

            <td style="width: 100px;">
                <div style="width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"
                    title="{{ $order->created_at }}">
                    {{ date('d M, Y', strtotime($order->created_at)) }}
                </div>
            </td>

            <!-- <td  style="width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                {{ $order->created_at }}</td> -->

            <td style="width: 50px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                @if ($order->customer_name != null)
                    <a
                        href="{{ route('plugin.tlcommercecore.customers.details', ['id' => $order->customer_id]) }}">
                        {{ $order->customer_name }}
                    </a>
                @else
                    {{ $order->guest_customer }}
                    <!-- <span class="badge badge-info">{{ translate('Guest') }}</span> -->
                    <span class="badge font-12" style="background-color: #FF5A1F; color: #fff;">{{ translate('Guest') }}</span>
                @endif
            </td>
            <td class="text-center" style="width: 20px;">{{ $order->total_product }}</td>
            <td class="text-center" style="width: 20px;">{!! currencyExchange($order->total_payable_amount) !!}</td>

            <td class="text-center">
                @php
                    $paidStatus = config('tlecommercecore.order_payment_status.paid');
                    $unpaidStatus = config('tlecommercecore.order_payment_status.unpaid');
                    $isPaid = (int) $order->payment_status === (int) $paidStatus;
                @endphp
                <div class="payment-status-inline"
                    data-order-id="{{ $order->id }}"
                    data-status="{{ $order->payment_status }}">
                    <span class="payment-status-label badge-payment {{ $isPaid ? 'badge-paid' : 'badge-unpaid' }}"
                        title="{{ translate('Click to change') }}">
                        @if ($isPaid)
                            <i class="fa fa-check-circle mr-1"></i> {{ translate('Paid') }}
                        @else
                            <i class="fa fa-exclamation-circle mr-1"></i> {{ translate('Unpaid') }}
                        @endif
                    </span>
                    <select class="payment-status-select theme-input-style" style="display:none;">
                        <option value="{{ $paidStatus }}" @selected($isPaid)>{{ translate('Paid') }}</option>
                        <option value="{{ $unpaidStatus }}" @selected(!$isPaid)>{{ translate('Unpaid') }}</option>
                    </select>
                    <span class="payment-status-spinner ajax-inline-spinner" style="display:none;"></span>
                </div>
            </td>

            <td class="text-center">
                <div class="delivery-status-inline"
                    data-order-id="{{ $order->id }}"
                    data-status="{{ $currentDelivery }}"
                    data-has-courier="{{ $shippingCourierOrder ? '1' : '0' }}">
                    <span class="delivery-status-label badge-payment {{ $currentDeliveryBadge['class'] }}"
                        title="{{ translate('Click to change') }}">
                        <i class="fa {{ $currentDeliveryBadge['icon'] }} mr-1"></i> {{ $currentDeliveryBadge['label'] }}
                    </span>
                    <select class="delivery-status-select theme-input-style" style="display:none;">
                        <option value="{{ $deliveryStatuses['pending'] }}" @selected($currentDelivery === (int) $deliveryStatuses['pending'])>{{ translate('Pending') }}</option>
                        <option value="{{ $deliveryStatuses['processing'] }}" @selected($currentDelivery === (int) $deliveryStatuses['processing'])>{{ translate('Processing') }}</option>
                        <option value="{{ $deliveryStatuses['ready_to_ship'] }}" @selected($currentDelivery === (int) $deliveryStatuses['ready_to_ship'])>{{ translate('Ready To Ship') }}</option>
                        <option value="{{ $deliveryStatuses['shipped'] }}" @selected($currentDelivery === (int) $deliveryStatuses['shipped'])>{{ translate('Shipped') }}</option>
                        <option value="{{ $deliveryStatuses['delivered'] }}" @selected($currentDelivery === (int) $deliveryStatuses['delivered'])>{{ translate('Delivered') }}</option>
                        <option value="{{ $deliveryStatuses['cancelled'] }}" @selected($currentDelivery === (int) $deliveryStatuses['cancelled'])>{{ translate('Cancelled') }}</option>
                    </select>
                    <span class="delivery-status-spinner ajax-inline-spinner" style="display:none;"></span>
                </div>
            </td>


            <td>
                @if ($order->delivery_status == config('tlecommercecore.order_delivery_status.pending'))
                    <button class="btn-success order-accept-btn w-50" style="border-radius: 6px; height: 30px;"
                        data-order="{{ $order->id }}" title="Accept order">
                        <i style="font-size: 18px;" class="icofont-check-circled"></i>
                    </button>
                @endif
                @if ($order->delivery_status == config('tlecommercecore.order_delivery_status.pending'))
                    <button class="btn-danger order-cancel-btn w-50" style="border-radius: 6px; height: 30px;"
                        data-order="{{ $order->id }}" title="Cancel order">
                        <i style="font-size: 18px;"
                            class="icofont-delete"></i>
                    </button>
                @endif
                @if ($order->delivery_status == config('tlecommercecore.order_delivery_status.processing')
                    ||
                    $order->delivery_status == config('tlecommercecore.order_delivery_status.delivered')
                    ||
                    $order->delivery_status == config('tlecommercecore.order_delivery_status.cancelled')
                )
                    <div class="text-center">

                    <button class="btn-orange status-details-btn w-100" title="Update order status"
                        data-order="{{ $order->id }}"
                        style="border-radius: 6px; height: 30px;">
                        <i class="icofont-ui-edit"></i>
                    </button>

                    <!-- <button class="btn-info status-details-btn w-75" title="Update order status"
                        data-order="{{ $order->id }}">
                        <i class="icofont-ui-edit"></i>
                    </button> -->

                    </div>

                @endif

                @if ($order->delivery_status == config('tlecommercecore.order_delivery_status.ready_to_ship')
                    ||
                    $order->delivery_status == config('tlecommercecore.order_delivery_status.shipped')
                    )

                <button class="btn-orange status-details-btn w-50" title="Update order status"
                        data-order="{{ $order->id }}"
                        style="border-radius: 6px; height: 30px;">
                        <x-lucide-pencil width="20" height="30" />
                </button>

                @if ($shippingCourierOrder == null)

                <button class="btn-orange shipping-modal-open-button w-50" title="Ship Order"
                        data-order="{{ $order->id }}"
                        style="border-radius: 6px; height: 30px;">
                        <x-lucide-motorbike width="20" height="30" />
                </button>

                @endif

                @if ($shippingCourierOrder != null)

                <button class="btn-orange track-order-button w-50" title="Track Order"
                        data-order="{{ $order->id }}"
                        style="border-radius: 6px; height: 30px;">
                        <x-lucide-locate width="20" height="30" />
                </button>

                @endif

                @endif
            </td>

            <!-- <td>
                <div class="dropdown-button">
                    <a href="#" class="d-flex align-items-center justify-content-center"
                        data-toggle="dropdown">
                        <div class="menu-icon mr-0">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a
                            href="{{ route('plugin.tlcommercecore.orders.details', ['id' => $order->id]) }}">{{ translate('Details') }}</a>
                    </div>
                </div>
            </td> -->
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="8">
            <p class="alert alert-danger text-center">{{ translate('Nothing found') }}</p>
        </td>
    </tr>
@endif
