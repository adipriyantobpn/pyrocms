<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Views Model
 *
 * @package		PyroCMS\Core\Modules\Streams Core\Models
 * @author		Parse19
 * @copyright	Copyright (c) 2011 - 2012, Parse19
 * @license		http://parse19.com/pyrostreams/docs/license
 * @link		http://parse19.com/pyrostreams
 */
class Views_m extends CI_Model
{
	public $table;

    // --------------------------------------------------------------------------
    // Caches
    // --------------------------------------------------------------------------
    // This is data stored in the class at runtime
    // and saved/checked so we don't keep going back to
    // the database.
    // --------------------------------------------------------------------------

	public function __construct()
	{
		$this->table = 'data_views';
	}

	/**
	 * Insert a view to the database
	 * @param  array  $view The view array
	 *                      prepped and ready for the db
	 * @return mixed       	false or the ID of the new row
	 */
	public function createView($view = array())
	{
		return $this->pdb->table($this->table)->insertGetId($view);
	}

	/**
	 * Update a view in the database
	 * @param  array  $view The view array
	 * @return bool
	 */
	public function updateView($view = array())
	{
		return $this->pdb
					->table($this->table)
					->where('namespace', $view['namespace'])
					->where('stream', $view['stream'])
					->where('slug', $view['slug'])
					->update($view);
	}

	/**
	 * Return views for a given stream
	 * @param  string $namespace 
	 * @param  string $stream    
	 * @return array            Results
	 */
	public function findViews($stream, $namespace)
	{
		return $this->pdb
					->table($this->table)
					->where('namespace', $namespace)
					->where('stream', $stream)
					->get();
	}

	/**
	 * Return a view for a given stream by slug
	 * @param  string $namespace 
	 * @param  string $stream    
	 * @param  string $slug    
	 * @return array            Results
	 */
	public function findView($stream, $namespace, $slug)
	{
		return $this->pdb
					->table($this->table)
					->where('namespace', $namespace)
					->where('stream', $stream)
					->where('slug', $slug)
					->first();
	}
}