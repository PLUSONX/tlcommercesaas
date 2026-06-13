<div class="table-responsive">
    <table class="hoverable text-left">
        <tbody>
            <tr>
                <td>{{ translate('Product') }}</td>
                <td>
                    @if ($details->product_id && $details->product)
                        {{ $details->product->translation('name', getLocale()) }}
                    @elseif ($details->order_id && $details->order)
                        <a href="{{ route('plugin.tlcommercecore.orders.details', ['id' => $details->order_id]) }}"
                            target="_blank">
                            {{ translate('Order review') }} ({{ $details->order->order_code }})
                        </a>
                    @else
                        {{ translate('Order review') }}
                    @endif
                </td>
            </tr>
            <tr>
                <td>{{ translate('Customer') }}</td>
                <td>{{ $details->customer->name }}</td>
            </tr>
            <tr>
                <td>{{ translate('Rating') }}</td>
                <td>
                    <div class="product-rating-wrapper">
                        <i data-star="{{ $details->rating }}"
                            title="{{ $details->rating }}"></i><span>{{ $details->rating }}</span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>{{ translate('Review') }}</td>
                <td>
                    <p>{{ $details->review }}</p>
                </td>
            </tr>
            <tr>
                <td>{{ translate('Images') }}</td>
                <td>

                    <div class="row">
                        @if ($details->images != null)
                            @php
                                $images = substr($details->images, 1, -1);
                                $images = explode(',', $images);
                            @endphp
                            @foreach ($images as $image)
                                <div class="col-sm-6 col-md-4 mb-2">
                                    <img src="{{ getFilePath($image) }}">
                                </div>
                            @endforeach
                        @endif
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
