<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Order Invoice</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        body {
            font-size:12px;
            font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .lines {
            margin-top: 30px;
            margin-bottom: 30px;
        }
        .discount-seperator {
            color:#ccc;
        }
        .lines-heading {
            text-align: left;
            background-color: #ededed;
        }

        .lines-heading th {
            padding: 5px 10px;
            border: 1px solid #ccc;
        }

        .lines-body td {
            padding: 5px 10px;
            border: 1px solid #ededed;
        }

        .lines-footer {
            border-top:5px solid #f5f5f5;
            text-align:right;
        }

        .lines-footer td {
            padding: 10px;
            border: 1px solid #ededed;
        }

        .summary {
            margin-bottom: 40px;
        }

        .summary td {
            padding: 5px 10px;
        }

        .info {
            color:#0099e5;
        }

        .summary .total td {
            border-top: 1px solid #ccc;
        }


    </style>
</head>

<body>
    <div class="content">
        <div class="invoice-box">

            <table cellpadding="0" cellspacing="0" width="100%">
                <tr class="top">
                    <td>
                        <table width="100%">
                            <tr>
                                <td class="title" width="50%">
                                    <h1>{{ config('app.name') }}</h1>
                                </td>
                                <td align="right" width="50%">
                                    Invoice: @ {{ $order->reference }} <br>
                                    Created: {{ $order->created_at }}<br>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <table cellpadding="0" cellspacing="0" width="100%">
                <tr>
                    <td>
                        <table width="100%">
                            <tr>
                                <td align="left" width="25%">
                                    <h3>Client</h3>
                                     Nom : {{ $order->Address->last_name }} <br>
                                     Prénom : {{ $order->Address->first_name }}<br>
                                     Adresse mail: {{ $order->Address->contact_email }}<br>
                                    Numéro de téléphone : {{ $order->Address->contact_phone }}<br>
                                </td>
                                <td align="right" width="25%">
                                </td>
                                <td align="right" width="25%">
                                </td>

                                <td align="right" width="25%">
                                    <h3>Lieu de livraison</h3>
                                    Pays : {{ $order->Address->pays }}<br>
                                    Region : {{ $order->Address->region }}<br>
                                    Département : {{ $order->Address->departement }}<br>
                                    Commune : {{ $order->Address->commune }}<br>
                                    Details 1 : {{ $order->Address->line_one }}<br>
                                    @if($order->Address->line_two)
                                    Details 2 : {{ $order->Address->line_two }}<br>
                                    @endif
                                    @if($order->Address->line_three)
                                    Details 3 : {{ $order->Address->line_three }}<br>
                                    @endif
                                </td>

                            </tr>
                        </table>
                    </td>
                </tr>
            </table>

            <table cellpadding="0" cellspacing="0" width="100%" class="lines">
                <thead class="lines-heading">
                    <tr width="100%">
                        <th width="35%">
                            Produit
                        </th>
                        <th width="28%">
                            taille
                        </th>
                        <th width="10%">
                            Quantité
                        </th>
                        <th width="15%">
                            Prix Unitaire
                        </th>
                        <th width="15%">
                            somme
                        </th>
                        <th width="15%">
                            remise total
                        </th>
                        <th width="12%">
                            total produit
                        </th>
                    </tr>
                </thead>
                <tbody class="lines-body">
                  @foreach($order->orderables as $line)
                    @php
                        $option=json_decode($line->option);
                        $value=$option->value;
                        $size=$option->name;
                    @endphp
                    <tr>
                      <td>
                        {{ $line->orderable->name }}
                      </td>
                      <td>
                        {{ $size }} : {{$value}}
                      <td>
                        {{ $line->quantity }}
                      </td>
                      <td>
                        {{ $line->unit_price }}
                      </td>
                      <td>
                        {{ $line->sub_total }}
                      </td>
                      <td>
                        {{ $line->discount_total }}
                      </td>
                      <td>
                        {{ $line->total }}
                      </td>
                    </tr>
                  @endforeach
                </tbody>
                <tfoot class="lines-footer">
                    <tr>
                        <td colspan="4"></td>
                        <td colspan="2"><strong>montant total</strong></td>
                        <td>{{ $order->sub_total }}</td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td colspan="2"><strong>remises totales</strong></td>
                        <td>{{ $order->discount_total }}</td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td colspan="2"><strong>Livraison</strong></td>
                        <td>{{ $order->shipping_total }}</td>
                    </tr>

                    <tr>
                        <td colspan="4"></td>
                        <td colspan="2"><strong>montant final </strong></td>
                        <td>{{ $order->total }}</td>
                    </tr>
                </tfoot>
            </table>

            <h3><strong>A payer</strong> :
            {{ $order->total }} F cfa</h3>
            <br>

        </div>
    </div>
</body>
</html>
