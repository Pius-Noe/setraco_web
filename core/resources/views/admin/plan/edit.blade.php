@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-none-30">
        <div class="col-lg-12">
            <form action="{{ route('admin.plan.update', $plan->id) }}" method="POST">
                @csrf
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <label>@lang('Name')</label>
                                <input type="text" class="form-control form-control-lg" name="name"
                                    value="{{ $plan->name }}" required="">
                            </div>

                            <div class="form-group col-lg-4">
                                <label>@lang('Price')</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control form-control-lg" name="price"
                                        value="{{ getAmount($plan->price) }}" id="planAmount"
                                        aria-describedby="basic-addon2" step="any" required>
                                    <div class="input-group-text">
                                        {{ __($general->cur_text) }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group col-lg-4">
                                <label>@lang('Referral Bonus')</label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control form-control-lg" id="referralBonus"
                                        name="referral_bonus" value="{{ getAmount($plan->referral_bonus) }}"
                                        aria-describedby="basic-addon2" step="any" required="">
                                    <div class="input-group-text">
                                        {{ __($general->cur_text) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h4 class="text-center my-4">@lang('Level Commissions')</h4>

                        <div class="row">
                            @for ($i = 0; $i < $general->matrix_height; $i++)
                                <div class="form-group col-lg-3">
                                    <label>@lang('Level '){{ $i + 1 }}</label>
                                    <div class="input-group mb-3">
                                        <input type="number" class="form-control form-control-lg commissionAmount"
                                            name="level[{{ $i + 1 }}]"
                                            value="{{ getAmount(@$plan->level[$i]->amount) }}"
                                            aria-describedby="basic-addon2" step="any" required="">
                                        <div class="input-group-text">
                                            {{ __($general->cur_text) }}
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>

                        <div class="text-center mb-4">
                            <div id="adminProfit">
                                @if ($plan->price > $totalAmount)
                                    <strong class="text--success">@lang('Admin Benefit') : {{ showAmount($finalAmount) }}
                                        {{ __($general->cur_text) }}</strong>
                                @else
                                    <strong class="text--danger">@lang('Admin Loss') : {{ showAmount($finalAmount) }}
                                        {{ __($general->cur_text) }}</strong>
                                @endif
                            </div>
                            <div class="adminGain"></div>
                            <div class="adminLoss"></div>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Plan Update')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <x-back route="{{ route('admin.plan.index') }}" />
@endpush

@push('script')
    <script>
        "use strict";
        (function($) {
            function planPriceCommission() {
                $("#adminProfit").hide();
                var levelAmount = 0;
                var planAmount = $('#planAmount').val();
                var referralBonus = $('#referralBonus').val();

                $('.commissionAmount').each(function() {
                    if ($(this).val() != '') {
                        levelAmount += +$(this).val();
                    }
                })

                var totalAmount = Number(levelAmount) + Number(referralBonus);
                var currency = "{{ __($general->cur_text) }}";
                var finalAmount = planAmount - totalAmount;
                if (planAmount > totalAmount) {
                    $('.adminGain').html('<strong class="text--success">@lang('Admin Benefit') : ' + parseFloat(finalAmount)
                        .toFixed(2) +
                        ' ' + currency + '</strong>');
                    $('.adminLoss').empty();
                } else {
                    $('.adminLoss').html('<strong class="text--danger">@lang('Admin Loss') : ' + parseFloat(finalAmount)
                        .toFixed(2) +
                        ' ' + currency + '</strong>');
                    $('.adminGain').empty();
                }
            };

            $(document).on('keyup', '.commissionAmount, #planAmount, #referralBonus', function() {

                planPriceCommission();

            });
        })(jQuery);
    </script>
@endpush
