<?php
/*
Plugin Name: Note'n'Cite
Plugin URI: github.com/javajawa/Note-n-Cite
Description: Lightweight and simple reference & footnotes plugin
Version: 0.3
Author: Benedict Harcourt
Author URI: harcourtprogramming.co.uk
License: BSD 3-clause
*/

define('SEPARATE_CITE_NOTE', false);

require('notes.php');

class NoteAndCite
{
	function __construct()
	{
		add_shortcode('note', array(&$this, 'note'));
		add_shortcode('ref',  array(&$this, 'note'));
		add_shortcode('cite', array(&$this, 'cite'));
		add_shortcode('backref', array(&$this, 'backref'));

		add_filter('the_content', array(&$this, 'on_new_post'), 1, 1);
		add_filter('the_content', array(&$this, 'end_of_post'), 1000, 1);

		add_action('init', array(&$this, 'add_style'));
	}

	function add_style()
	{
		wp_enqueue_style('note-n-cite', plugin_dir_url(__FILE__) . 'note-n-cite.css');
	}

	private $citations;
	private $notes;
	private $named_entries;
	private $post;

	public function on_new_post($content)
	{
		global $post;
		$this->post = $post->ID;
		$this->named_entries = array();
		$this->notes = array();
		if (SEPARATE_CITE_NOTE)
			$this->citations = array();
		else
			$this->citations = &$this->notes;
		return $content;
	}

	function note($atts, $content)
	{
		if ($content == null)
			return '';

		// Calculate bullet number + name
		$num = count($this->notes) + 1;

		// Make sure inner tags have higher numbers
		$this->notes[$num] = null;

		$note = new Note($this->post, $num, do_shortcode($content));
		$this->notes[$num] = &$note;

		if (array_key_exists('name', $atts))
			$this->named_entries[$atts['name']] = &$note;

		return $note->getLink();
	}

	function cite($atts, $content = null)
	{
		// Calculate bullet number + name
		$num = count($this->citations) + 1;

		if ($content === null)
			$content = '';

		if (array_key_exists('href', $atts))
			$href = $atts['href'];
		else
			$href = null;

		$note = new Citation($this->post, $num, $content, $href);
		$this->citations[$num] = &$note;

		if (array_key_exists('name', $atts))
			$this->named_entries[$atts['name']] = &$note;

		return $note->getLink();
	}

	function backref($atts = array())
	{
		if (array_key_exists('name', $atts) && array_key_exists($atts['name'], $this->named_entries))
			return $this->named_entries[$atts['name']]->getLink();
		else
			return '';
	}

	function note_list()
	{
		if (count($this->notes) == 0)
			return '';

		$ret = '<ol class="footnotes">' . PHP_EOL;
		foreach ($this->notes as $note)
		{
			$ret .= "\t" . $note->getEntry() . PHP_EOL;
		}
		$ret .= '</ol>' . PHP_EOL;

		return $ret;
	}

	function cite_list()
	{
		if (count($this->citations) == 0)
			return '';

		$ret = '<ol class="citations">' . PHP_EOL;
		foreach ($this->citations as $note)
		{
			$ret .= "\t" . $note->getEntry() . PHP_EOL;
		}
		$ret .= '</ol>' . PHP_EOL;

		return $ret;
	}

	function end_of_post($content)
	{
		$content .= $this->note_list();
		if (SEPARATE_CITE_NOTE)
			$content .= $this->cite_list();

		return $content;
	}
}

new NoteAndCite();
