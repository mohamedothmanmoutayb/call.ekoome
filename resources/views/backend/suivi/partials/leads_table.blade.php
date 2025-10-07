<div class="table-responsive border rounded-1 mt-3">
    <table class="table text-nowrap customize-table mb-0 align-middle">
        <thead class="text-dark fs-4">
            <tr>
                <th>
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="selectall custom-control-input" id="chkCheckAll" required>
                        <label class="custom-control-label" for="chkCheckAll"></label>
                    </div>
                </th>
                <th>ID</th>
                <th>Shipping Company</th>
                <th>Tracking N°</th>
                <th>Products</th>
                <th>Name</th>
                <th>City</th>
                <th>Phone</th>
                <th>Lead Value</th>
                <th>Status Suivi</th>
                <th>Date Confirmed</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $counter = 1;
            ?>
            @if (!$leads->isEmpty())
                @foreach ($leads as $key => $v_lead)
                    <tr class="accordion-toggle data-item" data-id="{{ $v_lead['id'] }}">
                        <td>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="ids" class="custom-control-input checkBoxClass"
                                    value="{{ $v_lead['id'] }}" id="pid-{{ $counter }}">
                                <label class="custom-control-label" for="pid-{{ $counter }}"></label>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $v_lead['n_lead'] }}</span>
                        </td>
                        <td>
                            <p>{{ $v_lead->shippingcompany->name ?? 'N/A' }}</p>
                        </td>
                        <td>
                            <p>{{ $v_lead['tracking'] }}</p>
                        </td>
                        <td>
                            @foreach ($v_lead['product'] as $v_product)
                                {{ Str::limit($v_product['name'], 20) }}
                            @endforeach
                        </td>
                        <td>{{ $v_lead['name'] }}</td>
                        <td>
                            @if (!empty($v_lead['id_city']))
                                @if (!empty($v_lead['cities'][0]['name']))
                                    @foreach ($v_lead['cities'] as $v_city)
                                        {{ $v_city['name'] }}
                                        <br>
                                    @endforeach
                                @else
                                    {{ $v_lead['city'] }}
                                    <br>
                                @endif
                            @else
                                {{ $v_lead['city'] }}
                                <br>
                            @endif   
                            {{ Str::limit($v_lead['address'], 10) }}
                        </td>
                        <td><a href="tel:{{ $v_lead['phone'] }}">{{ $v_lead['phone'] }}</a></td>
                        <td>{{ $v_lead['lead_value'] }}</td>
                        <td>
                            @if ($v_lead['status_suivi'])
                                <span class="badge bg-info">{{ $v_lead['status_suivi'] }}</span>
                            @else
                                <span class="badge bg-warning">No Status</span>
                            @endif
                        </td>
                        <td>{{ $v_lead['created_at'] }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle show" type="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i
                                        class="icon-settings"></i></button>
                                <div class="dropdown-menu" bis_skin_checked="1"
                                    style="position: absolute; inset: auto auto 0px 0px; margin: 0px; transform: translate3d(184px, -325.203px, 0px);"
                                    data-popper-placement="top-start">

                                    <a class="dropdown-item seehystory" id="seehystory" data-id="{{ $v_lead['id'] }}">
                                        History</a>
                                    <a class="dropdown-item " href="{{ route('leads.edit', $v_lead['id']) }}"
                                        id="">Details</a>
                                    <a class="dropdown-item " href="{{ route('leads.details', $v_lead['id']) }}"
                                        id=""> order</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php $counter = $counter + 1; ?>
                @endforeach
            @else
                <tr>
                    <td colspan="12">
                        <img src="{{ asset('public/Calling-cuate.png') }}"
                            style="margin-left: auto; margin-right: auto; display: block;" width="500" />
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

{{ $leads->withQueryString()->links('vendor.pagination.courier') }}
