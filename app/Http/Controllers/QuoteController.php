<?php

	namespace App\Http\Controllers;

	use App\Author;
	use App\Quote;
	use Illuminate\Http\Request;

	class QuoteController extends Controller {
		public function getIndex($author = null) {
			if(!is_null($author)) {
				$a = Author::where(['name' => ucfirst($author)])->first();
				$quotes = $a->quotes()->orderBy('created_at', 'desc')->paginate(6);
			} else {
				$quotes = Quote::orderBy('created_at', 'desc')->paginate(6);
			}
			return view('index' ,['quotes' => $quotes]);
		}	

		public function getDeleteQuote($quote_id) {
			$quote = Quote::find($quote_id);
			$authorDeleted = false;

			if(count($quote->author->quotes) < 2) {
				$quote->author->delete();
				$authorDeleted = true;
			}
			$quote->delete();

			if($authorDeleted)
				$msg = 'Quote and author deleted!';
			else
				$msg = 'Quote deleted';

			return redirect()->route('index')->with(['success' => $msg]);
		}

		public function postQuote(Request $request) {
			$this->validate($request, [
				'author' =>'required|max:60|alpha',
				'quote' =>'required|max:500'
			]);

			$authorText = ucfirst($request['author']);
			$quoteText = $request['quote'];

			$author = Author::where('name', '=', $authorText)->first();
			if(!$author) {
				$author = new Author();
				$author->name = $authorText;
				$author->save();
			}

			$quote = new Quote();
			$quote->quote = $quoteText;
			$author->quotes()->save($quote);

			return redirect()->route('index')->with(['success' => 'Quote saved!']);
		}
	}