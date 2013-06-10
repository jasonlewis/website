<?php

class CodeController extends BaseController {

	/**
	 * Show the code index page.
	 * 
	 * @return void
	 */
	public function getIndex()
	{
		$this->layout->title = 'Code';
		$this->layout->nest('content', 'pages.code');
	}

	/**
	 * Show the Basset overview page.
	 * 
	 * @return void
	 */
	public function getBasset()
	{
		$this->layout->title = 'Basset';
		$this->layout->nest('content', 'pages.code.basset');
	}

}