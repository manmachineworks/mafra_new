@php
    $product_url = $product->auction_product ? route('auction-product', $product->slug) : route('product', $product->slug);
    $discount_percentage = discount_in_percentage($product);

    $cart_added = [];
    $carts = get_user_cart();
    if ($carts && count($carts) > 0) {
        $cart_added = $carts->pluck('product_id')->toArray();
    }

    $brand_name = optional($product->brand)->getTranslation('name') ?? optional(optional($product->user)->shop)->name;

    $hover_image = null;
    if (!empty($product->photos)) {
        $photo_array = array_values(array_filter(explode(',', $product->photos)));
        if (count($photo_array) > 1) {
            $hover_image = get_image($photo_array[1]);
        } elseif (count($photo_array) === 1) {
            $hover_image = get_image($photo_array[0]);
        }
    }

    $unit_badge = $product->unit ?? null;
@endphp

<style>
.pharma-card{
    border-radius:18px;
    box-shadow:0 12px 30px rgba(0,0,0,.08);
    transition:transform .2s ease, box-shadow .2s ease;
}
.pharma-card:hover{ transform:translateY(-4px); box-shadow:0 16px 36px rgba(0,0,0,.12); }

/* Fixed image area to avoid layout shift */
.pharma-card .proimg-wrap{
    position:relative;
    width:100%;
    height:180px;
    border-radius:16px;
    background:#f5f7fa;
    overflow:hidden;
}
.pharma-card .proimg{
    width:100%;
    height:100%;
    object-fit:contain;
    border-radius:16px;
    display:block;
}

/* Hover image fade-in */
.pharma-card .hover-img{
    position:absolute;
    inset:0;
    opacity:0;
    transition:opacity .25s ease;
}
.pharma-card:hover .hover-img{ opacity:1; }

.pharma-card .wishlist-btn{
    width:36px;height:36px;border-radius:50%;
    background:#fff;display:flex;align-items:center;justify-content:center;
    box-shadow:0 6px 18px rgba(0,0,0,.12);
    transition:transform .2s ease, box-shadow .2s ease;
    border:0;
}
.pharma-card .wishlist-btn:hover{ transform:translateY(-2px); box-shadow:0 10px 22px rgba(0,0,0,.16); }

.pharma-card .pill-badge{
    border-radius:999px;
    background:linear-gradient(135deg,#1bb8a5,#16a085);
    color:#fff;font-weight:700;font-size:11px;
    padding:6px 12px;
    box-shadow:0 8px 18px rgba(22,160,133,.35);
}

.pharma-card .brand-text{
    letter-spacing:.06em;
    text-transform:uppercase;
    color:#7b8694;
    font-size:11px;
}
.pharma-card .product-title{ color:#161c2d; }
.pharma-card .price-current{ color:#111;font-size:17px;font-weight:800; }
.pharma-card .price-mrp{ color:#9aa3b5;text-decoration:line-through; }
.pharma-card .discount-text{ color:#1f9d55;font-size:13px;font-weight:700; }

.pharma-card .cta-btn{
    border-radius:999px;
    background:linear-gradient(135deg,#ff5f6d,#d91333);
    color:#fff;font-weight:800;
    width:100%;
    padding:10px 14px;
    box-shadow:0 14px 26px rgba(217,19,51,.35);
    transition:transform .15s ease, box-shadow .15s ease;
}
.pharma-card .cta-btn:hover{ transform:translateY(-1px); box-shadow:0 18px 32px rgba(217,19,51,.4); }
</style>

<div class="aiz-card-box pharma-card bg-white p-3 h-100">
    <div class="position-relative mb-3 text-center">
        <a href="{{ $product_url }}" class="d-block">
            <div class="proimg-wrap mx-auto">
                <img class="lazyload proimg"
                     src="{{ get_image($product->thumbnail) }}"
                     alt="{{ $product->getTranslation('name') }}"
                     title="{{ $product->getTranslation('name') }}"
                     onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">

                @if ($hover_image)
                    <img class="lazyload proimg hover-img"
                         src="{{ $hover_image }}"
                         alt="{{ $product->getTranslation('name') }}"
                         title="{{ $product->getTranslation('name') }}"
                         onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                @endif
            </div>
        </a>

        @if ($unit_badge)
            <div class="position-absolute pill-badge" style="left: 14px; bottom: -12px;">
                {{ $unit_badge }}
            </div>
        @endif

        @if ($product->auction_product == 0)
            <div class="position-absolute" style="top: 8px; right: 10px;">
                <button type="button"
                        class="wishlist-btn"
                        onclick="event.preventDefault(); event.stopPropagation(); addToWishList({{ $product->id }})"
                        aria-label="{{ translate('Add to wishlist') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="16" viewBox="0 0 18 16" fill="none">
                        <path d="M9 15s-6.5-3.7-8-8.6C-.6 2.4 3.6-.8 7.1 2.5L9 4.3l1.9-1.8C14.4-.8 18.6 2.4 17 6.4 15.5 11.3 9 15 9 15Z"
                              fill="#d23245" stroke="#fff" stroke-width="1"/>
                    </svg>
                </button>
            </div>
        @endif
    </div>

    <div class="pt-2">
        @if ($brand_name)
            <div class="brand-text mb-1 text-uppercase">{{ $brand_name }}</div>
        @endif

        <h3 class="product-title fw-700 fs-14 lh-1-5 mb-2 text-truncate-2">
            <a href="{{ $product_url }}" class="text-reset">{{ $product->getTranslation('name') }}</a>
        </h3>

        <div class="mb-1 d-flex align-items-center">
            <span class="price-current mr-2">{{ home_discounted_base_price($product) }}</span>
            <span class="price-mrp fs-12">{{ home_base_price($product) }}</span>
        </div>

        @if ($discount_percentage > 0)
            <div class="discount-text mb-3">( {{ translate('Save') }} {{ number_format($discount_percentage, 2) }}% )</div>
        @endif

        @if ($product->auction_product == 0)
            <a class="btn cta-btn @if (in_array($product->id, $cart_added)) active @endif"
               href="javascript:void(0)"
               onclick="showAddToCartModal({{ $product->id }})">
                {{ translate('Add To Cart') }}
            </a>
        @else
            @php
                $highest_bid = $product->bids->max('amount');
                $min_bid_amount = $highest_bid != null ? $highest_bid + 1 : $product->starting_bid;
            @endphp
            <a class="btn cta-btn"
               href="javascript:void(0)"
               onclick="bid_single_modal({{ $product->id }}, {{ $min_bid_amount }})">
                {{ translate('Place Bid') }}
            </a>
        @endif
    </div>
</div>
//-- End of file --//
