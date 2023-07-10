<style>
    table {
        width: 100%;
        border-collapse: collapse;
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        font-size: 10px;
    }

    th, td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }


    .style-1 {
        color: white;
        padding-left: 10pt;
        font-size: 14pt;
        font-family: "Arial";
        font-weight: bold;
        font-style: normal;
        text-decoration: none;
        text-align: left;
        word-spacing: 0pt;
        letter-spacing: 0pt;
        white-space: pre-wrap;
        background-color: #339933;
    }
    .style-2 {
        color: black;
        padding-right: 1pt;
        font-size: 8pt;
        font-family: "Arial";
        font-weight: bold;
        font-style: normal;
        text-decoration: none;
        text-align: left;
        word-spacing: 0pt;
        letter-spacing: 0pt;
        white-space: pre-wrap;
    }
    .style-3 {
        color: black;
        padding-right: 1pt;
        font-size: 8pt;
        font-family: "Arial Narrow";
        font-weight: normal;
        font-style: normal;
        text-decoration: none;
        text-align: right;
        word-spacing: 0pt;
        letter-spacing: 0pt;
        white-space: pre-wrap;
    }
</style>
<div>
<div class="col-md-12">
<div class="white-box">
	                            <h3><b> @if(!empty($key->office)){{$key->office->name}} @endif - Repayment Reports <span class="pull-right"> </span></h3>
	                            <hr>
	                            <div class="row">
	                                <div class="col-md-12">
										<div class="pull-left">
											<address>
                                            @if(!empty(\App\Models\Setting::where('setting_key','company_logo')->first()->setting_value))
            <img src="{{ asset('uploads/'.\App\Models\Setting::where('setting_key','company_logo')->first()->setting_value) }}"
                 class="img-responsive" width="90"/><br> 

        @endif
												<p class="text-muted m-l-6">
                                                 <b>@if(!empty($key->office)){{$key->office->name}} @endif <br> 
                                                 from: <b>{{$start_date}} to {{$end_date}} <br> 
                                                 <br>
												</p>
											</address>
										</div>
	                        <div class="white-box">
	                            <h3><b> Full Repayments <span class="pull-right"> </span></h3>
	                            <hr>
	                            <div class="row">
	                                <div class="col-md-12">
										<div class="pull-left">
	
										</div>

                                        <table class="table  table-condensed table-hover">
                    <tbody>
               
                    <tr class="">
                        <td><strong>{{trans_choice('general.id',1)}}</strong></td>
                        <td><strong>{{trans_choice('general.client',1)}}</strong></td>
                        <td><strong>{{trans_choice('general.loan',1)}}#</strong></td>
                        <td><strong>{{trans_choice('general.loan',1)}} {{trans_choice('general.officer',1)}}</strong> </td>
                        <td><strong>{{trans_choice('general.amount',1)}}</strong></td>
                        <td><strong>{{trans_choice('general.date',1)}}</strong></td>
                        <td><strong>{{trans_choice('general.channel',1)}}</strong></td>
                        <td><strong>{{trans_choice('general.office',1)}}</strong></td>
                        <td><strong>{{trans_choice('general.apply',1)}} {{trans_choice('general.to',1)}}</strong></td>
                    </tr>
                    <?php
                    $total_principal = 0;
                    $total_balance = 0;
                    $total_fees = 0;
                    $total_interest = 0;
                    $total_penalty = 0;
                    $decimals = 0;
                    $balance = 0;
                    ?>
                    @foreach($data as $key)
                        <?php
                        if (!empty($key->loan)) {
                            $decimals = $key->loan->decimals;
                        } else {
                            $decimals = 0;
                        }
                        $principal = $key->principal_derived;
                        $interest = $key->interest_derived;
                        $fees = $key->fees_derived;
                        $penalty = $key->penalty_derived;
                        $total_principal = $total_principal + $principal;
                        $total_interest = $total_interest + $interest;
                        $total_fees = $total_fees + $fees;
                        $total_penalty = $total_penalty + $penalty;
                        $total_balance = $total_balance + $balance;
                        ?>
                        <tr>
                            <td>{{$key->loan->client->external_id}}</td>
                            <td>
                                @if(!empty($key->loan))
                                    @if($key->loan->client_type=="client")
                                        @if(!empty($key->loan->client))
                                            @if($key->loan->client->client_type=="individual")
                                                {{$key->loan->client->first_name}} {{$key->loan->client->middle_name}} {{$key->loan->client->last_name}}
                                            @endif
                                            @if($key->loan->client->client_type=="business")
                                                {{$key->loan->client->full_name}}
                                            @endif
                                        @endif
                                    @endif
                                    @if($key->loan->client_type=="group")
                                        @if(!empty($key->loan->group))
                                            {{$key->loan->group->name}}
                                        @endif
                                    @endif
                                @endif
                            </td>
                            <td>{{$key->loan->id}}</td>
                            <td>
                                @if(!empty($key->loan))
                                    @if(!empty($key->loan->loan_officer))
                                        {{$key->loan->loan_officer->first_name}}  {{$key->loan->loan_officer->last_name}}
                                    @endif
                                @endif
                            </td>
                            
                            <td>{{number_format($principal+$interest+$fees+$penalty,$decimals)}}</td>
                            <td>{{$key->date}}</td>
                            <td>
                                @if(!empty($key->payment_detail))
                                    @if(!empty($key->payment_detail->type))
                                        {{$key->payment_detail->type->name}}
                                    @endif
                                @endif
                            </td>
                            <td>  @if(!empty($key->office))
                                    {{$key->office->name}}
                                @endif</td>
                            <td>
                            @if($key->payment_apply_to=="regular")
                            <span class="label label-success">Full Payment</span>
                            @endif       
                                
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tr>
    								<td class="thick-line"></td>
    								<td class="thick-line"></td>
                                    <td class="thick-line"></td>
    								<td class="thick-line"></td>
                                    <td class="thick-line"></td>
    								<td class="thick-line"></td>
                                    <td class="thick-line"></td>
                                    <td class="thick-line"></td>
    								<td class="thick-line text-center"><strong></strong></td>
    								<td class="thick-line text-right"></td>
    							</tr>
                    <tfoot>
                   
                    <tr>
                        <td colspan="3"></td>
                        <td><b>Total</b></td>
                        
                        <td>
                            <b>{{number_format($total_principal+$total_interest+$total_fees+$total_penalty,$decimals)}}</b>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                    </tfoot>
                </table>
                    </div>
                  </div>
                </div>

                <div class="col-md-12">
	                        <div class="white-box">
	                            <h3><b> Part Repayments <span class="pull-right"> </span></h3>
	                            <hr>
	                            <div class="row">
	                                <div class="col-md-12">
										<div class="pull-left">
	
										</div>

                                        <table class="table  table-condensed table-hover">
                    <tbody>
               
                    <tr class="">
                        <td><strong>{{trans_choice('general.id',1)}}</strong></td>
                        <td><strong>{{trans_choice('general.client',1)}}</strong></td>
                        <td><strong>{{trans_choice('general.loan',1)}}#</strong></td>
                        <td><strong>{{trans_choice('general.loan',1)}} {{trans_choice('general.officer',1)}}</strong> </td>
                        <td><strong>{{trans_choice('general.amount',1)}}</strong></td>
                        <td><strong>{{trans_choice('general.date',1)}}</strong></td>
                        <td><strong>{{trans_choice('general.channel',1)}}</strong></td>
                        <td><strong>{{trans_choice('general.apply',1)}} {{trans_choice('general.to',1)}}</strong></td>
                    </tr>
                    <?php
                    $total_principal = 0;
                    $total_fees = 0;
                    $total_interest = 0;
                    $total_penalty = 0;
                    $decimals = 0;
                    ?>
                    @foreach($part_data as $key)
                        <?php
                        if (!empty($key->loan)) {
                            $decimals = $key->loan->decimals;
                        } else {
                            $decimals = 0;
                        }
                        $principal = $key->principal_derived;
                        $interest = $key->interest_derived;
                        $fees = $key->fees_derived;
                        $penalty = $key->penalty_derived;
                        $total_principal = $total_principal + $principal;
                        $total_interest = $total_interest + $interest;
                        $total_fees = $total_fees + $fees;
                        $total_penalty = $total_penalty + $penalty;

                        ?>
                        <tr>
                            <td>{{$key->loan->client->external_id}}</td>
                            <td>
                                @if(!empty($key->loan))
                                    @if($key->loan->client_type=="client")
                                        @if(!empty($key->loan->client))
                                            @if($key->loan->client->client_type=="individual")
                                                {{$key->loan->client->first_name}} {{$key->loan->client->middle_name}} {{$key->loan->client->last_name}}
                                            @endif
                                            @if($key->loan->client->client_type=="business")
                                                {{$key->loan->client->full_name}}
                                            @endif
                                        @endif
                                    @endif
                                    @if($key->loan->client_type=="group")
                                        @if(!empty($key->loan->group))
                                            {{$key->loan->group->name}}
                                        @endif
                                    @endif
                                @endif
                            </td>
                            <td>{{$key->loan->id}}</td>
                            <td>
                                @if(!empty($key->loan))
                                    @if(!empty($key->loan->loan_officer))
                                        {{$key->loan->loan_officer->first_name}}  {{$key->loan->loan_officer->last_name}}
                                    @endif
                                @endif
                            </td>
                            
                            <td>{{number_format($principal+$interest+$fees+$penalty,$decimals)}}</td>
                            <td>{{$key->date}}</td>
                            <td>
                                @if(!empty($key->payment_detail))
                                    @if(!empty($key->payment_detail->type))
                                        {{$key->payment_detail->type->name}}
                                    @endif
                                @endif
                            </td>
                            <td>
                            @if($key->payment_apply_to=="part_payment")
                            <span class="label label-warning">Part Payment</span>
                            @endif     
                                
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tr>
    								<td class="thick-line"></td>
    								<td class="thick-line"></td>
                                    <td class="thick-line"></td>
    								<td class="thick-line"></td>
                                    <td class="thick-line"></td>
    								<td class="thick-line"></td>
    								<td class="thick-line text-center"><strong></strong></td>
    								<td class="thick-line text-right"></td>
    							</tr>
                    <tfoot>
                   
                    <tr>
                        <td colspan="3"></td>
                        <td><b>Total</b></td>
                        
                        <td>
                            <b>{{number_format($total_principal+$total_interest+$total_fees+$total_penalty,$decimals)}}</b>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                    </tfoot>
                </table>
                    </div>
                  </div>
                </div>
                

                <div class="col-md-12">
	                        <div class="white-box">
	                            <h3><b> Reloans <span class="pull-right"> </span></h3>
	                            <hr>
	                            <div class="row">
	                                <div class="col-md-12">
										<div class="pull-left">
	
										</div>

                                        <table class="table  table-condensed table-hover">
                    <tbody>
               
                    <tr class="">
                        <td><strong>{{trans_choice('general.id',1)}}</strong></td>
                        <td><strong>{{trans_choice('general.client',1)}}</strong></td>
                        <td><strong>{{trans_choice('general.loan',1)}}#</strong></td>
                        <td><strong>{{trans_choice('general.loan',1)}} {{trans_choice('general.officer',1)}}</strong> </td>
                        <td><strong>{{trans_choice('general.balance',1)}} B/F</strong></td>
                        <td><strong>{{trans_choice('general.interest',1)}} {{trans_choice('general.paid',1)}}</strong></td>
                        <td><strong>{{trans_choice('general.outstanding',1)}}</strong></td>
 
                        <td><strong>{{trans_choice('general.date',1)}}</strong></td>
                        <td><strong>{{trans_choice('general.status',1)}}</strong></td>
                    </tr>
                    <?php
                    $bf=0;
                    $amount_in_arrears = 0;
                    $total_principal = 0;
                    $total_fees = 0;
                    $total_interest = 0;
                    $total_penalty = 0;
                    $decimals = 0;
                    $total_outstanding=0;
                    $interest_sch=0;
                    $interest_paid=0;
                    $total_interest_paid=0;
                    $prev_balance = 0;
                    $total_prev_balance = 0;
                    ?>
                    @foreach($reloans_data as $key)
                        <?php
                        if (!empty($key->loan)) {
                            $decimals = $key->loan->decimals;
                        } else {
                            $decimals = 0;
                        }
                   
                        // $interest = \App\Models\LoanRepaymentSchedule::where('loan_id', $key->loan->id)->first();
                        
                        $bdr=0;
                        $bcr=0;
                        $badrcr=0;
                        $interest_paid =  \App\Models\LoanTransaction::where('loan_id',$key->loan_id)->where('payment_apply_to','reloan_payment')->sum('interest_derived');
                        $balance = \App\Helpers\GeneralHelper::loan_total_balance($key->loan_id);
                        $total_interest_paid = $total_interest_paid + $interest_paid;
                        $prev_balance = $balance - $interest_paid;
                        $total_bf = 0; 
                        $total_bf = $total_bf + $bf;
                        $total_prev_balance  = $total_prev_balance  + $prev_balance;
                        $bf =  \App\Models\LoanTransaction::where('loan_id',$key->loan_id)->where('date','<',$key->date)->sum('debit');
                        $bdr =  \App\Models\LoanTransaction::where('loan_id',$key->loan_id)->where('date','<',$key->date)->sum('debit');
                        $bcr =  \App\Models\LoanTransaction::where('loan_id',$key->loan_id)->where('date','<',$key->date)->sum('credit');
                        $badrcr=$bdr-$bcr;
                        $total_outstanding = $total_outstanding + $balance;
                        ?>
                        <tr>
                            <td>{{$key->loan->client->external_id}}</td>
                            <td>
                                @if(!empty($key->loan))
                                    @if($key->loan->client_type=="client")
                                        @if(!empty($key->loan->client))
                                            @if($key->loan->client->client_type=="individual")
                                                {{$key->loan->client->first_name}} {{$key->loan->client->middle_name}} {{$key->loan->client->last_name}}
                                            @endif
                                            @if($key->loan->client->client_type=="business")
                                                {{$key->loan->client->full_name}}
                                            @endif
                                        @endif
                                    @endif
                                    @if($key->loan->client_type=="group")
                                        @if(!empty($key->loan->group))
                                            {{$key->loan->group->name}}
                                        @endif
                                    @endif
                                @endif
                            </td>
                            <td>{{$key->loan->id}}</td>
                            <td>
                                @if(!empty($key->loan))
                                    @if(!empty($key->loan->loan_officer))
                                        {{$key->loan->loan_officer->first_name}}  {{$key->loan->loan_officer->last_name}}
                                    @endif
                                @endif
                            </td>
                            
                            <td>{{number_format($bf, $decimals)}}</td>
                            <td>{{number_format($key->interest_derived, $decimals)}}</td>
                            <td>{{number_format($balance,$decimals)}}</td>
                            <td>{{$key->date}}</td>
                            
                            
                           
                            <td>
                            @if($key->loan->status=="disbursed")
                            <span class="label label-primary">{{trans_choice('general.active',1)}}</span>
                            @endif
                            @if($key->loan->status=="closed")
                            <span class="label label-danger">{{trans_choice('general.closed',1)}}</span>
                            @endif
                            
                           
                             
                                 
                                
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tr>
    								<td class="thick-line"></td>
    								<td class="thick-line"></td>
                                    <td class="thick-line"></td>
    								<td class="thick-line"></td>
                                    <td class="thick-line"></td>
    								<td class="thick-line"></td>
                                    <td class="thick-line"></td>
    								<td class="thick-line text-center"><strong></strong></td>
    								<td class="thick-line text-right"></td>
    							</tr>
                    <tfoot>
                   
                    <tr>
                        <td colspan="3"></td>
                        <td><b>Total</b></td>
                        
                        <td>
                            <b></b>
                        </td>
                        <td>
                            <b>{{number_format($total_interest_paid,2)}}</b>
                        </td>
                      
                        <td>
                            <b>{{number_format($total_outstanding,2)}}</b>
                        </td>
                        <td colspan="3"></td>
                    </tr>
                    </tfoot>
                </table>
                    </div>
                  </div>
                </div>


                <div class="col-md-12">
	                        <div class="white-box">
	                            <h3><b> New Loans <span class="pull-right"> </span></h3>
	                            <hr>
	                            <div class="row">
	                                <div class="col-md-12">
										<div class="pull-left">
	
										</div>

                                        <table class="table">
  <thead>
    <tr>
      <th>NRC</th>
      <th >{{trans_choice('general.client',1)}} {{trans_choice('general.name',1)}}</th>
      <th >{{trans_choice('general.product',1)}}</th>
      <th>{{trans_choice('general.amount',1)}}</th>
      <th>{{trans_choice('general.loan',1)}} {{trans_choice('general.purpose',1)}}</th>
      <th>{{trans_choice('general.payment',1)}} {{trans_choice('general.method',1)}}</td>
    </tr>
  </thead>
  <tbody>
  <?php

$total_due = 0;
$total_principal = 0;
$total_principal_paid = 0;
$total_principal_outstanding = 0;
$total_interest = 0;
$total_interest_paid = 0;
$total_interest_outstanding = 0;
$total_fees = 0;
$total_fees_paid = 0;
$total_fees_outstanding = 0;
$total_penalty = 0;
$total_penalty_paid = 0;
$total_penalty_outstanding = 0;
$total_outstanding = 0;
$total_amount = 0;
$total_arrears = 0;
$total_loans = 0;
?>
  @foreach($new_loans as $key)
  <?php
    $amount = 0;
    $outstanding = 0;
    $principal = 0;
    $principal_paid = 0;
    $interest = 0;
    $interest_paid = 0;
    $fees = 0;
    $fees_paid = 0;
    $penalty = 0;
    $penalty_paid = 0;
    $amount_in_arrears = 0;
    $days_in_arrears = 0;
    $balance = 0;
    $percentage = 0;
    $late_count = 0;
    $timely_repayments = 0;
    $installments = 0;
    $total_repayments_due = 0;
    foreach ($key->repayment_schedules as $schedule) {
        $installments++;
        if (strtotime($schedule->due_date) < strtotime($end_date)) {
            $total_repayments_due++;
            $amount_in_arrears = $amount_in_arrears + (($schedule->principal - $schedule->principal_waived - $schedule->principal_written_off - $schedule->principal_paid) + ($schedule->interest - $schedule->interest_waived - $schedule->interest_written_off - $schedule->interest_paid) + ($schedule->fees - $schedule->fees_waived - $schedule->fees_written_off - $schedule->fees_paid) + ($schedule->penalty - $schedule->penalty_waived - $schedule->penalty_written_off - $schedule->penalty_paid));
        }
        if (!empty($schedule->from_date)) {
            if (strtotime($schedule->due_date) > strtotime($schedule->from_date) && strtotime($schedule->due_date) < strtotime($end_date)) {
                $timely_repayments = $timely_repayments + 1;
            }
        }
        $principal = $principal + $schedule->principal - $schedule->principal_waived - $schedule->principal_written_off;
        $interest = $interest + $schedule->interest - $schedule->interest_waived - $schedule->interest_written_off;
        $fees = $fees + $schedule->fees - $schedule->fees_waived - $schedule->fees_written_off;
        $penalty = $penalty + $schedule->penalty - $schedule->penalty_waived - $schedule->penalty_written_off;

        $principal_paid = $principal_paid + $schedule->principal_paid;
        $interest_paid = $interest_paid + $schedule->interest_paid;
        $fees_paid = $fees_paid + $schedule->fees_paid;
        $penalty_paid = $penalty_paid + $schedule->penalty_paid;
        $balance = $balance + (($schedule->principal - $schedule->principal_waived - $schedule->principal_written_off - $schedule->principal_paid) + ($schedule->interest - $schedule->interest_waived - $schedule->interest_written_off - $schedule->interest_paid) + ($schedule->fees - $schedule->fees_waived - $schedule->fees_written_off - $schedule->fees_paid) + ($schedule->penalty - $schedule->penalty_waived - $schedule->penalty_written_off - $schedule->penalty_paid));
        if ($amount_in_arrears > 0) {
            $late_count++;
            if ($late_count == 1) {
                $overdue_date = $schedule->due_date;
            }
        }
    }

    if ($amount_in_arrears > 0) {
        $date1 = new DateTime($overdue_date);
        $date2 = new DateTime($end_date);
        $days_in_arrears = $date2->diff($date1)->format("%a");
    }
    if ($total_repayments_due > 0) {
        $percentage = round($timely_repayments * 100 / $total_repayments_due, 2);
    }
    $amount = $principal + $penalty + $interest + $fees;
    $total_amount = $total_amount + $amount;
    $total_outstanding = $total_outstanding + $balance;
    $total_due = $total_due + $amount_in_arrears;
    $total_principal = $total_principal + $principal;
    $total_interest = $total_interest + $interest;
    $total_fees = $total_fees + $fees;

    $total_penalty = $total_penalty + $penalty;
    $total_principal_paid = $total_principal_paid + $principal_paid;
    $total_interest_paid = $total_interest_paid + $interest_paid;
    $total_fees_paid = $total_fees_paid + $fees_paid;
    $total_penalty_paid = $total_penalty_paid + $penalty_paid;

    $total_principal_outstanding = $total_principal_outstanding + $principal - $principal_paid;
    $total_interest_outstanding = $total_interest_outstanding + $interest - $interest_paid;
    $total_fees_outstanding = $total_fees_outstanding + $fees - $fees_paid;
    $total_penalty_outstanding = $total_penalty_outstanding + $penalty - $penalty_paid;
    $total_amount = $total_amount + $key->principal;
    $total_arrears = $total_arrears + $amount_in_arrears;
    //select appropriate schedules


    ?>
  <tr>
      
      <td>{{$key->external_id}}</td>
      <td>@if($key->client_type=="client")
                                    @if(!empty($key->client))
                                        @if($key->client->client_type=="individual")
                                            {{$key->client->first_name}} {{$key->client->middle_name}} {{$key->client->last_name}}
                                        @endif
                                        @if($key->client->client_type=="business")
                                            {{$key->client->full_name}}
                                        @endif
                                    @endif
                                @endif
                                @if($key->client_type=="group")
                                    @if(!empty($key->group))
                                        {{$key->group->name}}
                                    @endif
                                @endif</td>
                               
                                <td>@if(!empty($key->loan_product))
                                    {{$key->loan_product->name}}
                                @endif</td>
                                <td class="primary">{{number_format($key->principal, 2) }}</td>
                                <td>@if(!empty($key->loan_purpose))
                                    {{$key->loan_purpose->name}}
                                @endif</td>
                                <td>   <?php
                                $disbursement_detail = \App\Models\LoanTransaction::where('transaction_type', 'disbursement')->where('reversed', 0)->orderBy('date', 'asc')->first();
                                if (!empty($disbursement_detail)) {
                                    if (!empty($disbursement_detail->payment_detail)) {
                                        if (!empty($disbursement_detail->payment_detail->type)) {
                                            echo $disbursement_detail->payment_detail->type->name;
                                        }
                                    }
                                }
                                ?></td>
    </tr>
    
    @endforeach
                    </tbody>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th>{{number_format($total_principal,2)}}</th>
                        <th></th>
                        <th></th>
                        <th> </td>
                        <th></th>
                        <th></th>
                        <th> </td>
                        
                    </tr>
                    </table>
                    </div>
                  </div>
                </div>
                            