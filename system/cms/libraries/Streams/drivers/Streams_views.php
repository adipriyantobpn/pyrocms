<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Views Driver
 *
 * @author  	Parse19
 * @package  	PyroCMS\Core\Libraries\Streams\Drivers
 */ 
 
class Streams_views extends CI_Driver {

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
	 * Add a stream view
	 * @param string $namespace 
	 * @param string $stream    
	 * @param string $slug
	 * @param string $title     Supports lang: key prefixing
	 * @param string $order_by  The field slug to order entries by
	 * @param string $sort      ASC or DESC
	 * @param array  $search    An array of field slugs to include in entries search
	 * @param array  $filters   An array of field slugs to include in the entries columns
	 * @return mixed
	 */
	public function add_view($namespace, $stream, $slug, $title, $order_by = 'id', $sort = 'ASC', $search = array(), $filters = array(), $columns = array())
	{
		return $this->CI->views_m->createView(
			array(
				'namespace' => $namespace,
				'stream' => $stream,
				'slug' => $slug,
				'title' => $title,
				'order_by' => $order_by,
				'sort' => $sort,
				'search' => serialize($search),
				'filters' => serialize($filters),
				'columns' => serialize($columns)
				)
			);
	}

	/**
	 * Add multiple views at once
	 * @param array 	Array of valid views
	 * @return bool
	 */
	public function add_views($views)
	{
		foreach ($views as $k => $view) {
			self::add_view(
				$view['namespace'],
				$view['stream'],
				$view['slug'],
				$view['title'],
				isset($view['order_by']) ? $view['order_by'] : 'id',
				isset($view['sort']) ? $view['sort'] : 'ASC',
				isset($view['search']) ? $view['search'] : array(),
				isset($view['filters']) ? $view['filters'] : array(),
				isset($view['columns']) ? $view['columns'] : array()
				);
		}

		return true;
	}

	/**
	 * Update a stream view
	 * @param string $namespace 
	 * @param string $stream    
	 * @param string $slug
	 * @param string $title     Supports lang: key prefixing
	 * @param string $order_by  The field slug to order entries by
	 * @param string $sort      ASC or DESC
	 * @param array  $search    An array of field slugs to include in entries search
	 * @param array  $filters   An array of field slugs to include in the entries columns
	 * @return bool
	 */
	public function update_view($stream, $namespace, $slug, $title, $order_by = 'id', $sort = 'ASC', $search = array(), $columns = array(), $filters = array())
	{
		return $this->CI->views_m->updateView(
			array(
				'namespace' => $namespace,
				'stream' => $stream,
				'slug' => $slug,
				'title' => $title,
				'order_by' => $order_by,
				'sort' => $sort,
				'search' => serialize($search),
				'columns' => serialize($columns),
				'filters' => serialize($filters)
				)
			);
	}

	/**
	 * Get views for a stream
	 * @param  string $namespace 
	 * @param  string $stream    
	 * @return array            An array of views
	 */
	public function get_views($stream, $namespace)
	{
		$views = $this->CI->views_m->findViews($stream, $namespace);

		foreach ($views as &$view) {

			// Unsnerialize our codez
			$view->search = unserialize($view->search);
			$view->columns = unserialize($view->columns);
			$view->filters = unserialize($view->filters);

			$view->query_string = self::format_query_string($stream, $view);
		}

		return $views;
	}

	/**
	 * Get a view for a stream by slug
	 * @param  string $namespace 
	 * @param  string $stream    
	 * @param  string $slug    
	 * @return array            An array of views
	 */
	public function get_view($stream, $namespace, $slug)
	{
		$view = $this->CI->views_m->findView($stream, $namespace, $slug);

		// Unsnerialize our codez
		$view->search = unserialize($view->search);
		$view->columns = unserialize($view->columns);
		$view->filters = unserialize($view->filters);

		$view->query_string = self::format_query_string($stream, $view);

		return $view;
	}

	/**
	 * Format the filters array into a query_string
	 * @param  string $stream 
	 * @param  object $view   A view object
	 * @return string         The formatted query_string
	 */
	public function format_query_string($stream, $view)
	{
 		$query_string = '?'.$stream.'-view='.$view->id;

		// Append columns
		if (! empty($view->columns)) {

			$query_string .= '&'.$stream.'-column[]='.implode('&'.$stream.'-column[]=', $view->columns);
		}

		// Append order_by
		$query_string .= '&order-'.$stream.'='.$view->order_by;

		// Append sorting
		$query_string .= '&sort-'.$stream.'='.$view->sort;

		// Append filters
		if (! empty($view->filters)) {

			foreach ($view->filters as $filter)
			{
				$query_string .= '&f-'.$stream.'-filter[]='.$filter['filter'].'&f-'.$stream.'-condition[]='.$filter['condition'].'&f-'.$stream.'-value[]='.$this->CI->parser->parse_string($filter['default_value'], $this->CI, true);
			}
		}

		return $query_string;
	}
}