<!DOCTYPE html>
<html lang="en">

<head>
    <title>{{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <!-- External CSS libraries -->
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/invoice/css/bootstrap.min.css') }}">
    <!-- Google fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Custom Stylesheet -->
    <link type="text/css" rel="stylesheet" href="{{ asset('assets/invoice/css/style.css') }}">
</head>

<body>
    <div class="invoice-16 invoice-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="invoice-inner-9" id="invoice_wrapper">
                        <div class="invoice-top">
                            <div class="row">
                                <div class="col-lg-6 col-sm-6">
                                    <div class="logo">
                                        <h1>{{ config('app.name') }}</h1>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-sm-6">
                                    <div class="invoice">
                                        <h1>Invoice # <span></span></h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="invoice-info">
                            <div class="row">
                                <div class="col-sm-6 mb-50">
                                    <div class="invoice-number">
                                        <h4 class="inv-title-1">Invoice date:</h4>
                                        <p class="invo-addr-1">
                                            {{ Carbon\Carbon::now()->format('M d, Y') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6 mb-50">
                                    <h4 class="inv-title-1">Customer</h4>
                                    <p class="inv-from-1">{{ $request->name }}</p>
                                    <p class="inv-from-1">{{ $request->phone }}</p>

                                    <p class="inv-from-2">{{ $request->address }}</p>
                                    <p class="inv-from-1">{{ $user->zip }}</p>
                                    <p class="inv-from-2">{{ $request->city }}</p>
                                </div>
                                <div class="col-sm-6 text-end mb-50">
                                    <h4 class="inv-title-1">Store</h4>
                                    <p class="inv-from-1">{{ config('app.name') }}</p>
                                    <p class="inv-from-1">(+62) 123 123 123</p>
                                    <p class="inv-from-1">email@example.com</p>
                                    <p class="inv-from-2">Angeles City, Philippines</p>
                                </div>
                            </div>
                        </div>
                        <div class="order-summary">
                            <div class="table-outer">
                                <table class="default-table invoice-table">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Item</th>
                                            <th class="text-center">Price</th>
                                            <th class="text-center">Size</th>
                                            <th class="text-center">Quantity</th>
                                            <th class="text-center">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($carts as $item)
                                        <tr>
                                            <td class="text-center">{{ $item->name }}</td>
                                            <td class="text-center">₱ {{ $item->price }}</td>
                                            <td class="text-center">{{ $item->options['size'] }}</td>
                                            
                                            <td class="text-center">{{ $item->qty }}</td>
                                            <td class="text-center">₱ {{ $item->subtotal }}</td>
                                        </tr>
                                        @endforeach
                                        <tr>
                                            <td colspan="4" class="text-end"><strong>Subtotal</strong></td>
                                            <td class="text-center">
                                                <strong>₱ {{ Cart::subtotal() }}</strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end"><strong>Tax</strong></td>
                                            <td class="text-center">
                                                <strong>₱ {{ Cart::tax() }}</strong>
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td colspan="4" class="text-end"><strong>Shipping Fee:</strong></td>
                                            <td class="text-center">
                                                <strong>₱ {{ number_format( $order::computeDeliveryFeeByCartQty(Cart::count()), 2) }} </strong>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end"><strong>Total</strong></td>
                                            <td class="text-center">
                                                <strong>₱ {{ number_format( (float)Cart::total(2, '.', '') + $order::computeDeliveryFeeByCartQty(Cart::count()), 2) }}</strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{-- <div class="invoice-informeshon-footer">
                                <ul>
                                    <li><a href="#">www.website.com</a></li>
                                    <li><a href="mailto:sales@hotelempire.com">info@example.com</a></li>
                                    <li><a href="tel:+088-01737-133959">+62 123 123 123</a></li>
                                </ul> --}}
                        {{-- </div>--}}
                    </div>

                    <div class="invoice-btn-section clearfix d-print-none">
                        <a href="{{ route('reseller.showCart') }}" class="btn btn-warning">
                            {{ __('Back') }}
                        </a>

                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal">
                            {{ __('Pay Now') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="modal" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        {{ __('Pay Order') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form action="{{ route('reseller.storeOrder') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">

                                    <x-input.index label="Customer" name="customer" value="{{ $request->name }}" disabled />
                                </div>

                                <div class="mb-3">

                                    <x-input.index label="Total Amount" name="total_amount" value="{{ number_format( (str_replace(',', '', $total) + $order::computeDeliveryFeeByCartQty(Cart::count()))  , 2, '.') }}" disabled />
                                </div>

                            </div>

                            <div class="col-lg-6">
                                <div class="mb-3">
                                    <label for="payment_type" class="form-label required">
                                        {{ __('Payment Method') }}
                                    </label>

                                    <select class="form-control @error('payment_type') is-invalid @enderror" id="payment_type" name="payment_type">
                                        <option disabled="">Select a payment:</option>
                                        <option value="GCash" selected>GCash</option>
                                    </select>

                                    @error('payment_type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <img style="width: 300px" class="emb-2" src="{{ asset('assets/img/'.$gcash) }}" />
                                </div>
                                
                                
                            </div>

                            <div class="col-lg-12">
                                <h5 class="modal-title">GCash Payment Information:</h5>
                            </div>

                            <div class="col-lg-12">
                                <label for="reference_number" class="form-label required">
                                    {{ __('Reference Number') }}
                                </label>

                                <input type="text" id="reference_number" name="reference_number" class="form-control @error('reference_number') is-invalid @enderror" value="{{ old('reference_number') }}" required>

                                @error('pay')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="col-lg-12">
                                <label for="account_number" class="form-label required">
                                    {{ __('From Account Number') }}
                                </label>

                                <input type="text" id="account_number" name="account_number" class="form-control @error('account_number') is-invalid @enderror" value="{{ old('account_number') }}" required>

                                @error('account_number')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            
                            <div class="col-lg-12">
                                <label for="account_number" class="form-label required">
                                    {{ __('From Account Name') }}
                                </label>

                                <input type="text" id="account_name" name="account_name" class="form-control @error('account_name') is-invalid @enderror" value="{{ old('account_name') }}" required>

                                @error('account_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="col-lg-12">
                                <br/><br/>
                                For any issues/concern you can contact us via support@dropsynch.com
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn me-auto" data-bs-dismiss="modal">
                            {{ __('Cancel') }}
                        </button>
                        <button class="btn btn-primary" type="submit">
                            {{ __('Submit Payment Information') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>

</html>