<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Order</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; background:#0b0b0e; color:#ffffff; margin:0; padding:24px;">
    <div style="max-width:720px; margin:0 auto; background:#111118; border:1px solid rgba(255,255,255,0.10); border-radius:16px; overflow:hidden;">
        <div style="padding:20px 24px; background:#800020;">
            <div style="font-size:18px; font-weight:700;">
                {{ $isCustomerCopy ? 'Order Confirmation' : 'New Order' }}
            </div>
            <div style="opacity:0.9; margin-top:4px;">
                Nool
            </div>
        </div>

        <div style="padding:20px 24px;">
            <h2 style="margin:0 0 12px 0; font-size:16px;">Customer</h2>
            <div style="line-height:1.7;">
                <div><strong>Name:</strong> {{ $customer['name'] }}</div>
                <div><strong>Phone:</strong> {{ $customer['phone'] }}</div>
                <div><strong>Address:</strong> {{ $customer['address'] }}</div>
                @if(!empty($customer['email']))
                    <div><strong>Email:</strong> {{ $customer['email'] }}</div>
                @endif
            </div>

            <h2 style="margin:18px 0 12px 0; font-size:16px;">Items</h2>

            <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
                <thead>
                <tr>
                    <th align="left" style="padding:10px 0; border-bottom:1px solid rgba(255,255,255,0.10);">Product</th>
                    <th align="right" style="padding:10px 0; border-bottom:1px solid rgba(255,255,255,0.10);">Qty</th>
                    <th align="right" style="padding:10px 0; border-bottom:1px solid rgba(255,255,255,0.10);">Price</th>
                    <th align="right" style="padding:10px 0; border-bottom:1px solid rgba(255,255,255,0.10);">Subtotal</th>
                </tr>
                </thead>
                <tbody>
                @foreach($cart as $id => $item)
                    @php
                        $qty = (int) ($item['quantity'] ?? 0);
                        $price = (float) ($item['price'] ?? 0);
                        $sub = $qty * $price;
                    @endphp
                    <tr>
                        <td style="padding:10px 0; border-bottom:1px solid rgba(255,255,255,0.06);">
                            {{ $item['name'] ?? ('#' . $id) }}
                        </td>
                        <td align="right" style="padding:10px 0; border-bottom:1px solid rgba(255,255,255,0.06);">
                            {{ $qty }}
                        </td>
                        <td align="right" style="padding:10px 0; border-bottom:1px solid rgba(255,255,255,0.06);">
                        EGP{{ number_format($price, 2) }}
                        </td>
                        <td align="right" style="padding:10px 0; border-bottom:1px solid rgba(255,255,255,0.06); color:#ffb3c2;">
                        EGP{{ number_format($sub, 2) }}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div style="display:flex; justify-content:flex-end; margin-top:14px;">
                <div style="text-align:right;">
                    <div style="opacity:0.8;">Total</div>
                    <div style="font-size:20px; font-weight:800; color:#ffb3c2;">
                        EGP{{ number_format($total, 2) }}
                    </div>
                </div>
            </div>

            @if($isCustomerCopy)
                <p style="margin:18px 0 0 0; opacity:0.85; line-height:1.6;">
                    Thanks! We received your order and will contact you as soon as possible.
                </p>
            @endif
        </div>
    </div>
</body>
</html>

