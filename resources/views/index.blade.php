@extends('layouts.master')

@section('title')
	Trending Quotes
@endsection

@section('styles')
	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">
@endsection

@section('content')
	@if(count($errors) > 0)
		<section class="info-box fail">			
			@foreach($errors->all() as $error)
				{{ $error }}
			@endforeach			
		</section>
	@endif

	@if(Session::has('success'))
		<section class="info-box success">
			{{Session::get('success')}}
		</section>
	@endif
	<section class="quotes">		
		@if(!empty(Request::segment(1)))
			<section class="filter-bar">
				A filter has been set <a href="{{ route('index') }}">Show all quotes!</a>
			</section>
		@endif
		<h1>Latest Quotes</h1>
		@for($i = 0; $i < count($quotes); $i++)			
			<article class="quote">
				<div class="delete"><a href="{{ route('delete',['quote_id' => $quotes[$i]->id])}}">X</a></div>
				{{ $quotes[$i]->quote }}
				<div class="info">Created by <a href="{{ route('index', ['author_id' => $quotes[$i]->author->name]) }}">{{ $quotes[$i]->author->name }}</a> on {{ $quotes[$i]->created_at }}</div>
			</article>
		@endfor	
		<div class="pagination">
			@if($quotes->currentPage() !== 1)
				<a href="{{$quotes->previousPageUrl() }}"><span class="fa fa-caret-left"></span></a>
			@endif
			@if($quotes->currentPage() !== $quotes->lastPage() and $quotes->hasPages())
				<a href="{{$quotes->nextPageUrl() }}"><span class="fa fa-caret-right"></span></a>
			@endif
		</div>		
	</section>
	<section class="edit-quote">
		<h1>Add a Quote</h1>
		<form action="{{ route('create') }}" method="post">
			<div class="input-group">
				<label for="author">Your name</label>
				<input type="text" name="author" id="author" placeholder="Author name">
			</div>
			<div class="input-group">
				<label for="quote">Quote</label>
				<textarea name="quote" id="quote" placeholder="Quote" rows="5"></textarea>
			</div>
			<button type="submit" class="btn">Save</button>
			<input type="hidden" name="_token" value="{{Session::token() }}">
		</form>
	</section>	
@endsection