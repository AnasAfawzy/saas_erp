{{-- مثال لاستخدام CurrencyHelper مع العملاء --}}
<div class="card">
    <div class="card-header">
        <h5>أرصدة العملاء</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>اسم العميل</th>
                        <th>الرصيد - بدون ألوان</th>
                        <th>الرصيد - مع ألوان</th>
                        <th>الرصيد - Blade Directive</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- @foreach ($customers as $customer) --}}
                    <tr>
                        <td>عميل تجريبي 1</td>
                        <td>{{ format_balance(1500.75) }}</td>
                        <td>
                            @php
                                $balanceData = format_customer_balance(1500.75, true);
                            @endphp
                            <span class="{{ $balanceData['class'] }}" title="{{ $balanceData['label'] }}">
                                {{ $balanceData['text'] }}
                            </span>
                        </td>
                        <td>@customerBalance(1500.75)</td>
                    </tr>
                    <tr>
                        <td>عميل تجريبي 2</td>
                        <td>{{ format_balance(-750.25) }}</td>
                        <td>
                            @php
                                $balanceData = format_customer_balance(-750.25, true);
                            @endphp
                            <span class="{{ $balanceData['class'] }}" title="{{ $balanceData['label'] }}">
                                {{ $balanceData['text'] }}
                            </span>
                        </td>
                        <td>@customerBalance(-750.25)</td>
                    </tr>
                    {{-- @endforeach --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- مثال لاستخدام CurrencyHelper مع الموردين --}}
<div class="card mt-4">
    <div class="card-header">
        <h5>أرصدة الموردين</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>اسم المورد</th>
                        <th>الرصيد - Blade Directive</th>
                        <th>الرصيد - مع العملة</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>مورد تجريبي 1</td>
                        <td>@supplierBalance(2500.5)</td>
                        <td>@currency(2500.5)</td>
                    </tr>
                    <tr>
                        <td>مورد تجريبي 2</td>
                        <td>@supplierBalance(-1200.75)</td>
                        <td>@currency(-1200.75)</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- مثال لاستخدام مع أنواع مختلفة من الأرقام --}}
<div class="card mt-4">
    <div class="card-header">
        <h5>أمثلة متنوعة</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <h6>تنسيق الأرقام:</h6>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>رقم عادي:</span>
                        <span>@number(1234567.891234)</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>رقم بخانتين:</span>
                        <span>{{ format_number(1234567.891234, 2) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>رقم بدون خانات عشرية:</span>
                        <span>{{ format_number(1234567.891234, 0) }}</span>
                    </li>
                </ul>
            </div>
            <div class="col-md-6">
                <h6>تنسيق العملات:</h6>
                <ul class="list-group">
                    <li class="list-group-item d-flex justify-content-between">
                        <span>مع رمز العملة:</span>
                        <span>@currency(1234.56)</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>بدون رمز العملة:</span>
                        <span>{{ format_currency(1234.56, false) }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between">
                        <span>رصيد الحساب:</span>
                        <span>@accountBalance(1234.56)</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
