{{-- @extends('layouts/admin_layout') --}}
@extends( 'layouts/darpan_nav' )

@section('content')


		<div class="pageheader" id="menu-margin">
			<h4 class="mb-0">List of Position Holder Award Applicant
            <a href="{{ url('darpan') }}"  class="btn btn-sm  btn-outline-primary ms-2 float-end " ><span class="icons icon-list"></span> Dashboard</a>
			</h4>

		</div>
		<div class="card">
			<div class="card-body">
				<div class="mb-4">
					<form action="{{ url('darpan/district_position') }}" class="needs-validation" method="get" autocomplete="off" id="formHostelMaster" novalidate>
						@csrf
						<div class="row">
						<div class="col-md-3">
								<div id="list1" class="dropdown-check-list">
									<label for="city_filter">District</label>
									<select class="form-control" id="city_filter" name="city_filter">
										<option value="all">--All--</option>
										@foreach($cities as $citi)
										<option  {{$city == ($citi->id) ? 'Selected':''}} value="{{ $citi->id }}">{{ $citi->city }}</option>
										</option>
										@endforeach
									</select>
								</div>
							</div>
							<div class="col-md-2">
								<div class="form-group">
									<label for="year_filter">Year</label>
									<select class="form-control" id="year_filter" name="year_filter">
										<option value="all">--All--</option>
										@for ($x = 2017; $x <= date("Y"); $x++)
										<option {{$year ==$x ? 'Selected':''}} value="{{ $x }}">{{ $x }}</option>
										</option>
										@endfor
									</select>
								</div>
							</div>
                            <div class="col-md-3">
								<div class="form-group">
									<label for="month_filter">Month</label>
									<select class="form-control" id="month_filter" name="month_filter">
                                        <option value="all">--All--</option>
                                        <option value="1" @if($month==1) selected @endif>January</option>
                                        <option value="2" @if($month==2) selected @endif>February</option>
                                        <option value="3" @if($month==3) selected @endif>March</option>
                                        <option value="4" @if($month==4) selected @endif>April</option>
                                        <option value="5" @if($month==5) selected @endif>May</option>
                                        <option value="6" @if($month==6) selected @endif>June</option>
                                        <option value="7" @if($month==7) selected @endif>July</option>
                                        <option value="8" @if($month==8) selected @endif>August</option>
                                        <option value="9" @if($month==9) selected @endif>September</option>
                                        <option value="10" @if($month==10) selected @endif>October</option>
                                        <option value="11" @if($month==11) selected @endif>November</option>
                                        <option value="12" @if($month==12) selected @endif>December</option>
									</select>
								</div>
							</div>
							<div class="col-md-2 d-grid">
								<label class="form-label">&nbsp;</label>
								<button class="btn btn-primary form-group" type="submit">Search</button>
							</div>
							<div class="col-md-2 d-grid">
								<label class="form-label">&nbsp;</label>
								<a href="{{url('darpan/direct')}}" class="btn btn-primary form-group" type="submit">Reset</a>
							</div>
						</div>
					</form>
				</div>


					<div class="table-responsive">
						<table  id="dataTable" class="table table-striped table-bordered datatable">
							<thead>
								<tr>
                                    <th>S.No</th>
									<th>District Name</th>
									<th align="center">Total Application Received</th>
									<th align="center">Applications Pending</th>
									<th align="center">Applications Accepted</th>
									<th align="center">Applications Rejected</th>
								</tr>
							</thead>
							<tbody>
								@foreach($data as $key=>$list)
								<tr>
                                    <td>{{$key + 1}}</td>
									<td>{{$list->district}}</td>
									<td align="center"><a href="{{ url('darpan/position/'.$list->district) }}">{{$list->total}}</a></td>
									<td align="center"><a href="{{ url('darpan/position/1/'.$list->district) }}">{{$list->total_pending}}</a></td>
									<td align="center"><a href="{{ url('darpan/position/2/'.$list->district) }}">{{$list->total_accepted}}</a></td>
									<td align="center"><a href="{{ url('darpan/position/3/'.$list->district) }}">{{$list->total_rejected}}</a></td>

								</tr>
								@endforeach
                                <tr>
                                    <td>{{ count($data) + 1 }}</td>
                                    <td align="center">Total</td>
                                    <td align="center">{{ $data_total->total }}</td>
                                    <td align="center">{{ $data_total->total_pending }}</td>
                                    <td align="center">{{ $data_total->total_accepted }}</td>
                                    <td align="center">{{ $data_total->total_rejected }}</td>
                                </tr>
							</tbody>
						</table>

					</div>
			</div>
		</div>



@endsection
