<?php 
/****************************************************
*
* @File: 	customfields.php
* @Package:	GetSimple
* @Action:	Plugin to allow additional options on pages. 	
*
*****************************************************/


# register plugin
register_plugin(
	'customfields',
	'Custom Fields',
	'1.2',
  	'Mike Swan',
  	'http://www.digimute.com/',
  	'Custom fields for pages',
	'plugins',
	'customfields_showform'
  );


add_action('index-pretemplate', 'getTagValues',array());    // add hook to make $tags values available to theme
add_action('edit-extras', 'createTagInputs',array());    // add hook to create new tag inputs on the edit screen.
add_action('changedata-save', 'saveTagValues',array());    // add hook to save  $tags values 
add_action('plugins-sidebar','createSideMenu',array('customfields','CustomFields Info')); // add a menu item to the sidebar


$tags=array();


/*******************************************************
 * @function get_theme_tags
 * 
 */

function getTagValues(){
	global $data_index;
	global $tags;
	while (list($key, $val) = each($tags)) {
		$tags[$key]['value']=(string)$data_index->{$key};
	}
}


/*******************************************************
 * @function getTagsFromXML
 * 
 */
function getTagsFromXML(){
	global $data_index;
	global $tags;
	
	if (file_exists(GSDATAOTHERPATH.'customfields.xml')){
		$file=GSDATAOTHERPATH."customfields.xml";
		/**
		if (file_exists('admin/plugins/customfields/config.xml')){
			$file="admin/plugins/customfields/config.xml";
		} elseif (file_exists('../admin/plugins/customfields/config.xml')) {
			$file="../admin/plugins/customfields/config.xml";
		}
		**/
		$i=0;	
		$thisfile = file_get_contents($file);
		$data = simplexml_load_string($thisfile);
		$components = $data->item;
		if (count($components) != 0) {
			foreach ($components as $component) {
				$key=$component->desc;
				//$tags['$key']['test']=$component->label;
				$tags[(string)$key] =$key;
				$tags[(string)$key]=array();
				$tags[(string)$key]['label']=(string)$component->label;
				$tags[(string)$key]['type']=(string)$component->type;
				// for furture use
				if ($component->type=="dropdown"){
					// do dropdown 	
					$tags[(string)$key]['options']=array();		
					$options=$component->option;	
					foreach ($options as $option) {
						$tags[(string)$key]['options'][]=(string)$option;
					}
				}
				$tags[(string)$key]['value']="";
				$i++;
			}
		} 
	}
}


/*******************************************************
 * @function saveTagValues
 * 
 */

function saveTagValues(){
	global $tags;
	global $note;
	global $xml;
	
	while (list($key, $val) = each($tags)) {
		if(isset($_POST['post-'.strtolower($key)])) { 
			$note = $xml->addChild(strtolower($key));
			$note->addCData($_POST['post-'.strtolower($key)]);	
		}	
	}	
}

/*******************************************************
 * @function create_inputs
 * 
 */
function createTagInputs(){
	global $tags;
	global $data_edit;
	$uri 		= @$_GET['id'];
	$path = GSDATAPAGESPATH;
	// get saved page data
	$file = $uri .'.xml';
	echo "File:".$file;
	$data_edit = getXML($path . $file);
	$class="";
	echo "<tr><td><h2>Custom Fields</h2></td></tr>";
	global $tags;
	while (list($key, $val) = each($tags)) {	
		echo "<tr>";
		$typ=$tags[$key]['type'];
		switch($typ){
			// draw a full width TextBox
			case "textfull":
				echo "<td colspan='2'>";
				echo "<b>".$tags[$key]['label'].":</b><br />";
				echo "<input class=\"text\" type=\"text\" id=\"post-".strtolower($key)."\" name=\"post-".strtolower($key)."\" value=\"".$data_edit->$key."\" /></td>"; 
			break; 
			case "text":
				echo "<td>";
				echo "<b>".$tags[$key]['label'].":</b><br />";
				echo "<input class=\"text short\" type=\"text\" id=\"post-".strtolower($key)."\" name=\"post-".strtolower($key)."\" value=\"".$data_edit->$key."\" /></td><td></td>"; 
			break; 
			case "dropdown":
				echo "<td>";
				echo "<b>".$tags[$key]['label'].":</b><br />";
				echo "<select id=\"post-".strtolower($key)."\" name=\"post-".strtolower($key)."\" class='".$class."'>";
				echo "<option value='".$data_edit->$key."'>".$data_edit->$key."</option>";
				foreach ($tags[$key]['options'] as $option) {
					echo "<option value='".$option."'>".$option."</option>";
				}
				echo "</select>";
				echo "</td>";
				break;

			}
		echo "</tr>";
			
	}		
}


/*******************************************************
 * @function get_theme_tags
 * 
 */
function getCustomField($tag){
	global $tags;
	echo $tags[$tag]['value'];
}

/*******************************************************
 * @function get_theme_tags
 * 
 */
function returnCustomField($tag){
	global $tags;
	return $tags[$tag]['value'];
}


function customfields_showform(){
	global $tags;
	getTagsFromXML();	
	$table = '<thead><tr><th>Name</th><th>Label</th><th style="width:100px;">Type</th></tr></thead><tbody>';
	$counter=0;
	while (list($key, $val) = each($tags)) {
		$table .= '<tr id="tr-'.$counter.'" >';
		$table .= '<td>'.$key.'</td>';
		$table .= '<td>'.$tags[$key]['label'].'</td>';
		$table .= '<td>'.$tags[$key]['type'].'</td>';
		$table .= '<input type="hidden" name="key[]" value="'. $key.'" />';
		$table .= '<input type="hidden" name="label[]" value="'.$tags[$key]['label'].'" />';
		$table .= '<input type="hidden" name="type[]" value="'. $tags[$key]['type'] .'" />';
		$table .= '</tr>';
		$counter++;
	}
	$table.="</tbody>";

echo <<<HED
<label>Custom Fields</label>
<p><br/><br/>This plugin allows Custom Fields on each page. <br/>
New fields can be accessed in your themes by using:  getCustomField(\$tag);  or   returnCustomField(\$tag);<br/></p>
<table  id="pluginTable">
$table
</table>
HED;
}

// this is required to initialize the $tags array for the admin backend. 
getTagsFromXML();
?>