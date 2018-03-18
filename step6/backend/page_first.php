 <?php


/**
	Adding content, a form and CKeditor for textarea
*	Defines page regions, blocks and elements
*	Includes first.css, first.js and render_first.js files
*	Includes htmlHead_first.php
*/
class Page_First
{
	protected $protecteds = array(	
		'pageTitle' => 'First Page',
		'lang' => 'En',		
		'cssFiles' => array(11 => 'first.css', 12 => 'color.css'),	
		'jsFiles' => array(11 => 'first.js', 12 => 'render_first.js', 101 => 'libraries/ckeditor/ckeditor.js'),
		'htmlHeadFile' => 'frontEnd/html/htmlHead.php',
		'addJsCodeBeforeLoad' => array(),
		'addJsCodeOnload' => array(),
		'emptyBody' => '',		
	);

	private $privates = array();
	public $publics = array();
	
	function __CONSTRUCT($params) {
		

		$htmlHead = '';
		$bodyAttributes = 'lang="EN"';
		$addJsCodeBeforeLoad = array();		

			// Page_first has 6 regions
		$regions = array();
		$count_regions = 6;
		for($i = 1; $i <= $count_regions; $i++) {
			$method = 'region' . $i;
			$regions[] = $this->$method();  
		}
			// passing regions to javascript
		// $this->protecteds['addJsCodeBeforeLoad'][11] = 'var regions = ' . json_encode($regions) . '; ';
			
		// for JavaScript form validation
		$requiredsPhp = array('fname', 'lname', 'comment', 'tokenid', 'token');
		$pregCheckPhp = array(
			'fname'	=> '^[a-zA-Z \-]{2,16}$',
			'lname'	=> '^[a-zA-Z \-]{2,16}$',			
			'token'	=> '^[a-zA-Z0-9\_\-\.]{11,64}$',
			'tokenid' => '^[a-zA-Z_\-]{2,16}$',
			'comment' => '',
		);
		//	'comment'	=> '^[a-zA-Z0-9 \~\!\@\#\$\%\^\&\*\(\)\-\_\=\+,.\/\\;:\[\]\{\}\<\>]{8,1600}$',
			
		unset($pregCheckPhp['token']);
		$pregCheckJson = json_encode($pregCheckPhp);
		$requiredsJson = json_encode($requiredsPhp);
		$this->protecteds['addJsCodeOnload'][101] = 'eVe.pregCheck_form_comment = ' . $pregCheckJson . '; ';
		$this->protecteds['addJsCodeOnload'][103] = 'eVe.requireds_form_comment = ' . $requiredsJson . '; ';

		
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
		$regionAttr = array('classes' => array(), 'attributes' => array());
		$blocks = array();
		
			// logo and site name
		$block = array();
		$elements = array();		
		$blockAttr = array('classes' => array(), 'attributes' => array('name' => "page_header", 
		'onclick' => 'alert(\'This is region 1, block 1\')'));
			$elements[] = array('tag' => 'img', 'attributes' => 'width=70 height=50', 'src' => 'frontend/images/profile2011.jpg');
			$elements[] = array('tag' => 'h1', 'classes' => array('sitename'), 'text' => 'eVe For Developers');
		$block = array('blockAttr' => $blockAttr, 'elements' => $elements);
		$blocks[] = $block;
		
		return array('regionAttr' => $regionAttr, 'blocks' => $blocks); 
		
	}
	
	
	/**
	*	Navigation region has 1 block
	*	
	*/
	protected function region2() {
		$regionAttr = array('classes' => array(), 'attributes' => array());		
		$blocks = array();
		
		$nav = $this->getNav1();
		
			// navigation links
		$block = array();
		$elements = array();			
		$blockAttr = array('classes' => array(), 'attributes' => array('name' => "navigation"));
		
			$elements[] = array('tag' => 'h3', 'text' => $nav['title']);
			foreach($nav['links'] as $text => $anchor) {
				$elements[] = array('tag' => 'a', 'href' => '#' . $anchor, 'text' => $text);
			}
			
		$block = array('blockAttr' => $blockAttr, 'elements' => $elements);
		$blocks[] = $block;
		
		return array('regionAttr' => $regionAttr, 'blocks' => $blocks); 
		
	
	}
	
	
	/**
	*	Content region has copied asis content

	*	
	*/
	protected function region3() {
		$regionAttr = array('classes' => array(), 'attributes' => array());
		$blocks = array();
		
		$block = array();
		$elements = array();
		$blockAttr = array('classes' => array(), 'attributes' => array('name' => "article"));
			$elements[] = array('tag' => 'h4', 'text' => 'Eve Step 6:');	
			$elements[] = array('tag' => 'p', 'text' => 'New:');
			$elements[] = array('tag' => 'p', 'text' => 'Security check  in the backend.');			
			$elements[] = array('tag' => 'p', 'text' => 'Security check incoming package.');
			$elements[] = array('tag' => 'p', 'text' => 'Validate request.');
			$elements[] = array('tag' => 'p', 'text' => ' => Check odt file for details.');
			$elements[] = array('tag' => 'hr');

			$content .= $this->getContent1();			
			$elements[] = array('tag' => 'asis', 'innerHTML' => $content);			
		$block = array('blockAttr' => $blockAttr, 'elements' => $elements);
		$blocks[] = $block;		

		return array('regionAttr' => $regionAttr, 'blocks' => $blocks); 
		
	
		
	}

	
	/**
	*	Content region has copied asis content

	*	
	*/
	protected function region4() {
		$regionAttr = array('classes' => array(), 'attributes' => array());
		$blocks = array();
		
		$content = $this->getForm1();
		
		$block = array();
		$elements = array();
		$blockAttr = array('classes' => array(), 'attributes' => array('name' => "form"));
			$elements[] = array('tag' => 'asis', 'innerHTML' => $content);
			
		$block = array('blockAttr' => $blockAttr, 'elements' => $elements);
		$blocks[] = $block;		

		return array('regionAttr' => $regionAttr, 'blocks' => $blocks); 
		
	
		
	}

	
	/**
	*	Comments go here.
	*	
	*/
	protected function region5() {
		$regionAttr = array('classes' => array(), 'attributes' => array());
		$blocks = array();
		
			// footer
		$block = array();
		$elements = array();			
		$blockAttr = array('classes' => array('comment'), 'attributes' => array('name' => "comments"));
		$block = array('blockAttr' => $blockAttr, 'elements' => $elements);
		$blocks[] = $block;
		
		return array('regionAttr' => $regionAttr, 'blocks' => $blocks); 
	}
	
	
	
	/**
	*	Footer region has 1 block
	*	
	*/
	protected function region6() {
		$regionAttr = array('classes' => array(), 'attributes' => array());
		$blocks = array();
		
			// footer
		$block = array();
		$elements = array();			
		$blockAttr = array('classes' => array(), 'attributes' => array('name' => "footer"));
			$elements[] = array('tag' => 'p', 'text' => 'Footer goes here.');
		$block = array('blockAttr' => $blockAttr, 'elements' => $elements);
		$blocks[] = $block;
		
		return array('regionAttr' => $regionAttr, 'blocks' => $blocks); 
	
	}
	
	
	/**
	*	
	*/
	public function getBody() {
		
		return $this->emptyBody;
	}
	
	
	/**
	*	returns data for navigation bock 
	*/
	public function getNav1() {

		
		return array('title' => 'Contents', 
			'links' => array('Sources of damage' => 'anchor1', 
					'Types of damage' => 'anchor2', 
					'Nuclear versus mitochondrial DNA damage' => 'anchor3', 
					'Senescence and apoptosis' => 'anchor4',  
					'DNA damage and mutation' => 'anchor5', 
			));
		
	}

	
	/**
	*	returns full html as string for main content bock 
	*/
	public function getContent1() {

		return '<h3>DNA repair from Wikipedia</h3><div id="mw-content-text" lang="en" dir="ltr" class="mw-content-ltr"><div role="note" class="hatnote">For the journal, see <a href="/wiki/DNA_Repair_(journal)" title="DNA Repair (journal)">DNA Repair (journal)</a>.</div>
<div class="thumb tright">
<div class="thumbinner" style="width:258px;"><a href="/wiki/File:Brokechromo.jpg" class="image"><img alt="" src="//upload.wikimedia.org/wikipedia/commons/b/b2/Brokechromo.jpg" width="256" height="183" class="thumbimage" data-file-width="256" data-file-height="183" /></a>
<div class="thumbcaption">DNA damage resulting in multiple broken chromosomes</div>
</div>
</div>
<p><b>DNA repair</b> is a collection of processes by which a <a href="/wiki/Cell_(biology)" title="Cell (biology)">cell</a> identifies and corrects damage to the <a href="/wiki/DNA" title="DNA">DNA</a> molecules that encode its <a href="/wiki/Genome" title="Genome">genome</a>. In human cells, both normal <a href="/wiki/Metabolism" title="Metabolism">metabolic</a> activities and environmental factors such as <a href="/wiki/Radiation" title="Radiation">radiation</a> can cause DNA damage, resulting in as many as 1 <a href="/wiki/Million" title="Million">million</a> individual <a href="/wiki/Molecular_lesion" title="Molecular lesion">molecular lesions</a> per cell per day.<sup id="cite_ref-lodish_1-0" class="reference"><a href="#cite_note-lodish-1">[1]</a></sup> Many of these lesions cause structural damage to the DNA molecule and can alter or eliminate the cell\'s ability to <a href="/wiki/Transcription_(genetics)" title="Transcription (genetics)">transcribe</a> the <a href="/wiki/Gene" title="Gene">gene</a> that the affected DNA encodes. Other lesions induce potentially harmful <a href="/wiki/Mutation" title="Mutation">mutations</a> in the cell\'s genome, which affect the survival of its daughter cells after it undergoes <a href="/wiki/Mitosis" title="Mitosis">mitosis</a>. As a consequence, the DNA repair process is constantly active as it responds to damage in the DNA structure. When normal repair processes fail, and when cellular <a href="/wiki/Apoptosis" title="Apoptosis">apoptosis</a> does not occur, irreparable DNA damage may occur, including double-strand breaks and DNA crosslinkages (interstrand crosslinks or ICLs).<sup id="cite_ref-acharya_2-0" class="reference"><a href="#cite_note-acharya-2">[2]</a></sup><sup id="cite_ref-Bjorksten_3-0" class="reference"><a href="#cite_note-Bjorksten-3">[3]</a></sup> This can eventually lead to malignant tumors, or <a href="/wiki/Cancer" title="Cancer">cancer</a> as per the <a href="/wiki/Knudson_hypothesis" title="Knudson hypothesis">two hit hypothesis</a>.</p>
<p>The rate of DNA repair is dependent on many factors, including the cell type, the age of the cell, and the extracellular environment. A cell that has accumulated a large amount of DNA damage, or one that no longer effectively repairs damage incurred to its DNA, can enter one of three possible states:</p>
<ol>
<li>an irreversible state of dormancy, known as <a href="/wiki/Senescence" title="Senescence">senescence</a></li>
<li>cell suicide, also known as <a href="/wiki/Apoptosis" title="Apoptosis">apoptosis</a> or <a href="/wiki/Programmed_cell_death" title="Programmed cell death">programmed cell death</a></li>
<li>unregulated cell division, which can lead to the formation of a <a href="/wiki/Tumor" class="mw-redirect" title="Tumor">tumor</a> that is <a href="/wiki/Cancer" title="Cancer">cancerous</a></li>
</ol>
<p>The DNA repair ability of a cell is vital to the integrity of its genome and thus to the normal functionality of that organism. Many genes that were initially shown to influence <a href="/wiki/Life_expectancy" title="Life expectancy">life span</a> have turned out to be involved in DNA damage repair and protection.<sup id="cite_ref-browner_4-0" class="reference"><a href="#cite_note-browner-4">[4]</a></sup></p>
<div class="thumb tright">
<div class="thumbinner" style="width:222px;">
<div id="mwe_player_0" class="PopUpMediaTransform" style="width:220px;" videopayload="&lt;div class=&quot;mediaContainer&quot; style=&quot;width:854px&quot;&gt;&lt;video id=&quot;mwe_player_1&quot; poster=&quot;//upload.wikimedia.org/wikipedia/commons/thumb/a/a1/Paul_Modrich.webm/854px--Paul_Modrich.webm.jpg&quot; controls=&quot;&quot; preload=&quot;none&quot; autoplay=&quot;&quot; style=&quot;width:854px;height:480px&quot; class=&quot;kskin&quot; data-durationhint=&quot;136.423&quot; data-startoffset=&quot;0&quot; data-mwtitle=&quot;Paul_Modrich.webm&quot; data-mwprovider=&quot;wikimediacommons&quot;&gt;&lt;source src=&quot;//upload.wikimedia.org/wikipedia/commons/transcoded/a/a1/Paul_Modrich.webm/Paul_Modrich.webm.480p.webm&quot; type=&quot;video/webm; codecs=&amp;quot;vp8, vorbis&amp;quot;&quot; data-title=&quot;SD WebM (480P)&quot; data-shorttitle=&quot;WebM 480P&quot; data-transcodekey=&quot;480p.webm&quot; data-width=&quot;854&quot; data-height=&quot;480&quot; data-bandwidth=&quot;1010752&quot; data-framerate=&quot;50&quot;/&gt;&lt;source src=&quot;//upload.wikimedia.org/wikipedia/commons/transcoded/a/a1/Paul_Modrich.webm/Paul_Modrich.webm.720p.webm&quot; type=&quot;video/webm; codecs=&amp;quot;vp8, vorbis&amp;quot;&quot; data-title=&quot;HD WebM (720P)&quot; data-shorttitle=&quot;WebM 720P&quot; data-transcodekey=&quot;720p.webm&quot; data-width=&quot;1280&quot; data-height=&quot;720&quot; data-bandwidth=&quot;1313904&quot; data-framerate=&quot;50&quot;/&gt;&lt;source src=&quot;//upload.wikimedia.org/wikipedia/commons/transcoded/a/a1/Paul_Modrich.webm/Paul_Modrich.webm.480p.ogv&quot; type=&quot;video/ogg; codecs=&amp;quot;theora, vorbis&amp;quot;&quot; data-title=&quot;SD Ogg video (480P)&quot; data-shorttitle=&quot;Ogg 480P&quot; data-transcodekey=&quot;480p.ogv&quot; data-width=&quot;854&quot; data-height=&quot;480&quot; data-bandwidth=&quot;2118664&quot; data-framerate=&quot;50&quot;/&gt;&lt;source src=&quot;//upload.wikimedia.org/wikipedia/commons/transcoded/a/a1/Paul_Modrich.webm/Paul_Modrich.webm.1080p.webm&quot; type=&quot;video/webm; codecs=&amp;quot;vp8, vorbis&amp;quot;&quot; data-title=&quot;Full HD WebM (1080P)&quot; data-shorttitle=&quot;WebM 1080P&quot; data-transcodekey=&quot;1080p.webm&quot; data-width=&quot;1920&quot; data-height=&quot;1080&quot; data-bandwidth=&quot;3468872&quot; data-framerate=&quot;50&quot;/&gt;&lt;source src=&quot;//upload.wikimedia.org/wikipedia/commons/a/a1/Paul_Modrich.webm&quot; type=&quot;video/webm; codecs=&amp;quot;vp8, vorbis&amp;quot;&quot; data-title=&quot;Original WebM file, 1,920 × 1,080 (8.35 Mbps)&quot; data-shorttitle=&quot;WebM source&quot; data-width=&quot;1920&quot; data-height=&quot;1080&quot; data-bandwidth=&quot;8354514&quot; data-framerate=&quot;50&quot;/&gt;&lt;source src=&quot;//upload.wikimedia.org/wikipedia/commons/transcoded/a/a1/Paul_Modrich.webm/Paul_Modrich.webm.160p.ogv&quot; type=&quot;video/ogg; codecs=&amp;quot;theora, vorbis&amp;quot;&quot; data-title=&quot;Low bandwidth Ogg video (160P)&quot; data-shorttitle=&quot;Ogg 160P&quot; data-transcodekey=&quot;160p.ogv&quot; data-width=&quot;284&quot; data-height=&quot;160&quot; data-bandwidth=&quot;184472&quot; data-framerate=&quot;15&quot;/&gt;&lt;source src=&quot;//upload.wikimedia.org/wikipedia/commons/transcoded/a/a1/Paul_Modrich.webm/Paul_Modrich.webm.240p.ogv&quot; type=&quot;video/ogg; codecs=&amp;quot;theora, vorbis&amp;quot;&quot; data-title=&quot;Small Ogg video (240P)&quot; data-shorttitle=&quot;Ogg 240P&quot; data-transcodekey=&quot;240p.ogv&quot; data-width=&quot;426&quot; data-height=&quot;240&quot; data-bandwidth=&quot;565320&quot; data-framerate=&quot;50&quot;/&gt;&lt;source src=&quot;//upload.wikimedia.org/wikipedia/commons/transcoded/a/a1/Paul_Modrich.webm/Paul_Modrich.webm.360p.webm&quot; type=&quot;video/webm; codecs=&amp;quot;vp8, vorbis&amp;quot;&quot; data-title=&quot;WebM (360P)&quot; data-shorttitle=&quot;WebM 360P&quot; data-transcodekey=&quot;360p.webm&quot; data-width=&quot;640&quot; data-height=&quot;360&quot; data-bandwidth=&quot;540616&quot; data-framerate=&quot;50&quot;/&gt;&lt;source src=&quot;//upload.wikimedia.org/wikipedia/commons/transcoded/a/a1/Paul_Modrich.webm/Paul_Modrich.webm.360p.ogv&quot; type=&quot;video/ogg; codecs=&amp;quot;theora, vorbis&amp;quot;&quot; data-title=&quot;Ogg video (360P)&quot; data-shorttitle=&quot;Ogg 360P&quot; data-transcodekey=&quot;360p.ogv&quot; data-width=&quot;640&quot; data-height=&quot;360&quot; data-bandwidth=&quot;1091688&quot; data-framerate=&quot;50&quot;/&gt;&lt;/video&gt;&lt;/div&gt;"><img alt="File:Paul Modrich.webm" style="width:220px;height:124px" src="//upload.wikimedia.org/wikipedia/commons/thumb/a/a1/Paul_Modrich.webm/220px-seek%3D1-Paul_Modrich.webm.jpg" /><a href="//upload.wikimedia.org/wikipedia/commons/a/a1/Paul_Modrich.webm" title="Play media" target="new"><span class="play-btn-large"><span class="mw-tmh-playtext">Play media</span></span></a></div>
<div class="thumbcaption">
<div class="magnify"><a href="/wiki/File:Paul_Modrich.webm" class="internal" title="Enlarge"></a></div>
Paul Modrich</div>
</div></div></div>';

	}

	
	/**
	*	Just a sample form
	*/
	public function getForm1() {
		$formId = 'form_comment';
		$form = '<h3>Add COMMENT</h3><form id="' . $formId . '" name="form_first" action="Form_First" >';
		$form .= ' <input type="hidden" name="tokenid" value="' . $formId . '">';		
		$form .= '<br> <label for="fname">First Name</label> <input name="fname" >';
		$form .= '<br> <label for="lname">Last Name</label> <input name="lname" >';
		$form .= '<br> <textarea name="comment" rows="10" cols="100">You can write your comments here.</textarea>';
		$form .= '<br> <input type="submit" value="Submit" >';	
		$form .= '</form>';

			// giving control of text area to ckeditor
		$this->protecteds['addJsCodeOnload'][11] = 'CKEDITOR.replace("comment"); '; 
		$token = generateToken($formId, 600);
		$this->protecteds['addJsCodeOnload'][102] = "eVe.token_form_comment = '" . $token . "'; ";
					
		return $form;
		
	}	
	
}

