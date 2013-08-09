<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * PyroStreams Forms Model
 *
 * @package		PyroCMS\Core\Modules\Streams Core\Models
 * @author		Parse19
 * @copyright	Copyright (c) 2011 - 2012, Parse19
 * @license		http://parse19.com/pyrostreams/docs/license
 * @link		http://parse19.com/pyrostreams
 */
class Forms_m extends CI_Model
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
		$this->table = 'data_forms';
	}

	/**
	 * Insert a form to the database
	 * @param  array  $form The form array
	 *                      prepped and ready for the db
	 * @return mixed       	false or the ID of the new row
	 */
	public function createForm($form = array())
	{
		return $this->pdb->table($this->table)->insertGetId($form);
	}

	/**
	 * Update a form in the database
	 * @param  array  $form The form array
	 * @return bool
	 */
	public function updateForm($form = array())
	{
		return $this->pdb
					->table($this->table)
					->where('namespace', $form['namespace'])
					->where('stream', $form['stream'])
					->where('slug', $form['slug'])
					->update($form);
	}

	/**
	 * Return forms for a given stream
	 * @param  string $namespace 
	 * @param  string $stream    
	 * @return array            Results
	 */
	public function findForms($stream, $namespace)
	{
		return $this->pdb
					->table($this->table)
					->where('namespace', $namespace)
					->where('stream', $stream)
					->get();
	}

	/**
	 * Return a form for a given stream by slug
	 * @param  string $namespace 
	 * @param  string $stream    
	 * @param  string $slug    
	 * @return array            Results
	 */
	public function findForm($stream, $namespace, $slug)
	{
		return $this->pdb
					->table($this->table)
					->where('namespace', $namespace)
					->where('stream', $stream)
					->where('slug', $slug)
					->first();
	}
}