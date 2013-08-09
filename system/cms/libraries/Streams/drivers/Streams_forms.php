<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Forms Driver
 *
 * @author  	Parse19
 * @package  	PyroCMS\Core\Libraries\Streams\Drivers
 */ 
 
class Streams_forms extends CI_Driver {

	private $CI;

	// --------------------------------------------------------------------------

	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		$this->CI =& get_instance();
	}

	/**
	 * Add a stream form
	 * @param string $namespace 
	 * @param string $stream    
	 * @param string $slug
	 * @param array  $form_structure   An array of the form structure / tabs
	 * @return mixed
	 */
	public function add_form($namespace, $stream, $slug, $form_structure = array())
	{
		return $this->CI->forms_m->createForm(
			array(
				'namespace' => $namespace,
				'stream' => $stream,
				'slug' => $slug,
				'form_structure' => json_encode($form_structure)
				)
			);
	}

	/**
	 * Add multiple forms at once
	 * @param array 	Array of valid forms
	 * @return bool
	 */
	public function add_forms($forms)
	{
		foreach ($forms as $k => $form) {
			self::add_form(
				$form['namespace'],
				$form['stream'],
				$form['slug'],
				$form['form_structure']
				);
		}

		return true;
	}

	/**
	 * Update a stream form
	 * @param string $namespace 
	 * @param string $stream    
	 * @param string $slug
	 * @param string $order_by  The field slug to order entries by
	 * @param string $sort      ASC or DESC
	 * @param array  $search    An array of field slugs to include in entries search
	 * @param array  $filters   An array of field slugs to include in the entries columns
	 * @return bool
	 */
	public function update_form($stream, $namespace, $slug, $order_by = 'id', $sort = 'ASC', $search = array(), $filters = array())
	{
		return $this->CI->forms_m->updateForm(
			array(
				'namespace' => $namespace,
				'stream' => $stream,
				'slug' => $slug,
				'form_structure' => json_encode($form_structure),
				)
			);
	}

	/**
	 * Get forms for a stream
	 * @param  string $namespace
	 * @param  string $stream
	 * @return array            An array of forms
	 */
	public function get_forms($stream, $namespace)
	{
		$forms = $this->CI->forms_m->findForms($stream, $namespace);

		foreach ($forms as &$form) {

			// Unsnerialize our codez
			$form->form_structure = json_decode($form->form_structure);

			// Format tabs
			$form->tabs = self::format_form_tabs($form);
		}

		return $forms;
	}

	/**
	 * Get a form for a stream by slug
	 * @param  string $stream
	 * @param  string $namespace
	 * @param  string $slug
	 * @return array            An array of forms
	 */
	public function get_form($stream, $namespace, $slug)
	{
		$form = $this->CI->forms_m->findForm($stream, $namespace, $slug);

		// Unsnerialize our codez
		$form->form_structure = json_decode($form->form_structure);

		// Format tabs
		$form->tabs = self::format_form_tabs($form);

		return $form;
	}

	/**
	 * Format tabs for entry_form
	 * @param  object $form 
	 * @return array       Tabs formatted per docs ready for entry_form
	 */
	public function format_form_tabs($form)
	{
		$tabs = array();

		foreach ($form->form_structure as $k => $tab) {
			
			# Add a tab
			$tabs[] = array(
				'title' => $tab->title,
				'id' => isset($tab->id) ? $tab->id : slugify($tab->title),
				'slug' => isset($tab->slug) ? $tab->slug : slugify($tab->title),
				'fields' => $tab->fields,
				);
		}

		return $tabs;
	}
}