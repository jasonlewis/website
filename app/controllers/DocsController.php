<?php 

use JasonLewis\Website\Documents\DocumentCollection;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DocsController extends BaseController {

	/**
	 * Document collection instance.
	 * 
	 * @var \JasonLewis\Website\Documents\DocumentCollection
	 */
	protected $documents;

	/**
	 * Create a new docs controller instance.
	 * 
	 * @param  \JasonLewis\Website\Documents\DocumentCollection  $documents
	 * @return void
	 */
	public function __construct(DocumentCollection $documents)
	{
		$this->documents = $documents;
	}

	/**
	 * Show the documents for a project.
	 * 
	 * @param  string  $project
	 * @param  int  $version
	 * @param  string  $page
	 * @return void
	 */
	public function getDocs($project, $version, $page = 'index')
	{
		if ( ! $doc = $this->documents->get("{$project}/{$version}/{$page}"))
		{
			throw new NotFoundHttpException;
		}

		$this->layout->title = $doc->getMeta('title');
		$this->layout->nest('nav', "docs.nav.{$project}");
		$this->layout->nest('content', 'docs.read', compact('doc'));
	}

}