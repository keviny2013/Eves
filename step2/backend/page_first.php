 <?php
/** 
*	@name			:	page_first.php
*	@owner			: 	eVe
*	@description	:	First page layout. Regions and blocks. 
*/


/**
*	Defines page regions, blocks and elements
*	Includes first.css, first.js and render_first.js files
*	Includes htmlHead_first.php
*/
class page_first 
{
	protected $protecteds = array(	
		'pageTitle' => 'First Page',
		'lang' => 'En',		
		'cssFiles' => array(11 => 'first.css', 12 => 'color.css'),	
		'jsFiles' => array(11 => 'first.js', 12 => 'render_first.js'),
		'htmlHeadFile' => 'frontEnd/html/htmlHead.php',
		'addJsCodeBeforeLoad' => array(),
		'addJsCodeOnload' => array(),
		'emptyBody' => '',		
	);

	private $privates = array();
	public $publics = array();
	
	function __CONSTRUCT() {
		

		$htmlHead = '';
		$bodyAttributes = 'lang="EN"';

			// Page_first has 6 regions
		$regions = array();
		$count_regions = 6;
		for($i = 1; $i <= $count_regions; $i++) {
			$method = 'region' . $i;
			$region = $this->$method();
			$regions[] = $region; 
		}
		
			// getting HTML head ($htmlHead) from $htmlHead
		require_once($this->protecteds['htmlHeadFile']);
		$this->emptyBody = $htmlHead . '<body ' . $bodyAttributes . '>';
		$this->emptyBody .= 'This is emptyBody';
		$this->emptyBody .= '<script>var regions = ' . json_encode($regions) . '; </script>';
		$this->emptyBody .= '</body></html>';
	
		return true;
	}
	
	
	/**
	*	Page header region has 1 block
	*	
	*/
	protected function region1() {
		$region = array();
			$region['classes'] = '';
			$region['attributes'] = array();
			
		$blocks = array();
		
				// block 1: logo and site name
			$block = array();
				$block['classes'] = '';
				$block['attributes'] = array('name' => "page_header", 'onclick' => 'alert(\'This is region 1, block 1\')');
				
				$elements = array();
					$element = array('tag' => 'p', 'text' => 'Logo goes here.');
					$elements[] = $element;
					$element = array('tag' => 'p', 'text' => 'Name goes here.');
					$elements[] = $element;	
					$element = array('tag' => 'p', 'text' => 'Main menu goes here.');
					$elements[] = $element;
					
			$block['elements'] = $elements;

			
		$blocks[] = $block;
		
		$region['blocks'] = $blocks;
		
		return $region;
		
	}
	
	
	/**
	*	Navigation region has 1 block
	*	
	*/
	protected function region2() {
		$region = array();
			$region['classes'] = '';
			$region['attributes'] = array();
			
		$blocks = array();
		
				// block 1: logo, site name, main menu go here
			$block = array();
				$block['classes'] = '';
				$block['attributes'] = array('name' => "navigation");
				
				$elements = array();
					$element = array('tag' => 'p', 'text' => 'My preferences goes here.');
					$elements[] = $element;
					
			$block['elements'] = $elements;

			
		$blocks[] = $block;
		
		$region['blocks'] = $blocks;
		
		return $region;
	
	}
	
	
	/**
	*	Content region has copied asis content

	*	
	*/
	protected function region3() {
		$region = array();
			$region['classes'] = '';
			$region['attributes'] = array();
			
		$blocks = array();
		
				// block 1: 
			$block = array();
				$block['classes'] = '';
				$block['attributes'] = array('name' => "article");
				
				$elements = array();
					$element = array('tag' => 'p', 'text' => 'Article goes here.');
					$elements[] = $element;
					$text = 'Eve Step 2: 
		Step 2:
			Using PHP to build page (regions) in the backend.
			Using $regions to build regions, blocks and elements
			Rendering $regions into HTML by Javascript

		Check odt file for details.';
					
					$element = array('tag' => 'pre', 'text' => $text);
					$elements[] = $element;

					
			$block['elements'] = $elements;

			
		$blocks[] = $block;
		
		$region['blocks'] = $blocks;
		
		return $region;		
	}

	
	/**
	*	Content region has copied asis content

	*	
	*/
	protected function region4() {
		$region = array();
			$region['classes'] = '';
			$region['attributes'] = array();
			
		$blocks = array();
		
				// block 1: 
			$block = array();
				$block['classes'] = '';
				$block['attributes'] = array('name' => "commentform");
				
				$elements = array();
					$element = array('tag' => 'p', 'text' => 'Comment form goes here.');
					$elements[] = $element;
					
			$block['elements'] = $elements;

			
		$blocks[] = $block;
		
		$region['blocks'] = $blocks;
		
		return $region;
		
	}

	
	/**
	*	Comments go here.
	*	
	*/
	protected function region5() {
		$region = array();
			$region['classes'] = '';
			$region['attributes'] = array();
			
		$blocks = array();
		
				// block 1: 
			$block = array();
				$block['classes'] = '';
				$block['attributes'] = array('name' => "comments");
				
				$elements = array();
					$element = array('tag' => 'p', 'text' => 'Comments goes here.');
					$elements[] = $element;
					
			$block['elements'] = $elements;

			
		$blocks[] = $block;
		
		$region['blocks'] = $blocks;
		
		return $region;
		
	}
	
	
	
	/**
	*	Footer region has 1 block
	*	
	*/
	protected function region6() {
		$region = array();
			$region['classes'] = '';
			$region['attributes'] = array();
			
		$blocks = array();
		
				// block 1: 
			$block = array();
				$block['classes'] = '';
				$block['attributes'] = array('name' => "footer");
				
				$elements = array();
					$element = array('tag' => 'p', 'text' => 'Footer goes here.');
					$elements[] = $element;
					
			$block['elements'] = $elements;

			
		$blocks[] = $block;
		
		$region['blocks'] = $blocks;
		
		return $region;
		
	}
	
	
	/**
	*	
	*/
	public function getBody() {
		
		return $this->emptyBody;
	}
	
	
}

