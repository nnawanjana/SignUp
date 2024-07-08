<?php

App::uses('Component', 'Controller');

class HtmlNodeFactoryComponent extends Component{

	public $doc;
	private $inputNode;
	private $radioNode;
	private $selectNode;
	private $checkbxNode;


	private $input_class = "form-control";
	private $label_class = "control-label";
	private $span_class = "";
	private $select_class = "select[name] form-control selectpicker show-menu-arrow form-control";
	private $radio_label_class = "radio-inline control-label";
	private $checkbx_label_class = "checkbox-inline control-label";
	private $text_calendar_class = "calender-icon date-picker form-control";


   public function generateNodeString($instructions){

	   /***
		* $instructions["element_class"];
		* $instructions["element_id"];
		* $instructions["element_name"];
		* $instructions["element_label_value"];
		* $instructions["element_placeholder"];
		* $instructions["node_element"];
	   **/

		$this->doc = new DOMDocument();

		//$node = $this -> doc->importNode($instructions["node_element"], true);

		$this->doc->loadXml($instructions["node_element"]);
		$xpath = new DOMXPath($this->doc);
		$nodes = $xpath->evaluate("input", $this -> doc);

		$node = $nodes -> item(0);

		$nodeName = strtoupper($node -> nodeName);

		$output = array("label_string" => "", "node_string" => "", "nodeName" => $nodeName, "nodeType" => $node -> getAttribute("type"));

		if ($nodeName == "INPUT"){

			$nodeType = $node -> getAttribute("type");

			if (strtoupper($nodeType) == "TEXT"){
				$label_string = "<label class='" . $this -> label_class . "'>" . $instructions["element_label_value"] . "</label>";
				$node -> setAttribute("name", $instructions["element_id"]);
				$node -> setAttribute("placeholder", $instructions["element_placeholder"]);
				$node -> setAttribute("class", $this -> input_class);
				$output["label_string"] = $label_string;
				$output["node_string"] = $this -> doc -> saveHTML($node);
			}

			if (strtoupper($nodeType) == "TEL"){
				$label_string = "<label class='" . $this -> label_class . "'>" . $instructions["element_label_value"] . "</label>";
				$node -> setAttribute("name", $instructions["element_id"]);
				$node -> setAttribute("placeholder", $instructions["element_placeholder"]);
				$node -> setAttribute("class", $this -> input_class);
				$output["label_string"] = $label_string;
				$output["node_string"] = $this -> doc -> saveHTML($node);
			}

			if (strtoupper($nodeType) == "EMAIL"){
				$label_string = "<label class='" . $this -> label_class . "'>" . $instructions["element_label_value"] . "</label>";
				$node -> setAttribute("name", $instructions["element_id"]);
				$node -> setAttribute("placeholder", $instructions["element_placeholder"]);
				$node -> setAttribute("class", $this -> input_class);
				$output["label_string"] = $label_string;
				$output["node_string"] = $this -> doc -> saveHTML($node);
			}

			if (strtoupper($nodeType) == "CALENDAR"){
				$label_string = "<label class='" . $this -> label_class . "'>" . $instructions["element_label_value"] . "</label>";
				$node -> setAttribute("type", "text");
				$node -> setAttribute("name", $instructions["element_id"]);
				$node -> setAttribute("class", $this -> text_calendar_class);
				$node -> setAttribute("data-date-format", "dd/mm/yyyy");

				$output["label_string"] = $label_string;
				$output["node_string"] = $this -> doc -> saveXML($node);
			}

			if (strtoupper($nodeType) == "SELECT"){
				$count = 0;

				$options = $xpath->evaluate("option", $node);
				$select = $this -> doc -> createElement("select");
				$select -> setAttribute("name", $instructions["element_id"]);
				$select -> setAttribute("class", str_replace('[name]', $instructions["element_name"], $this -> select_class));
				foreach ($options as $option) {
					$select -> appendChild($option);
				}

				$label_string = "<label class='" . $this -> label_class . "'>" . $instructions["element_label_value"] . "</label>";
				$output["label_string"] = $label_string;
				$output["node_string"] = $this -> doc -> saveXML($select);
			}

			if (strtoupper($nodeType) == "CHECKBX"){
				//create a new element
				$label_string = "<label class='" . $this -> label_class . "'>" . $instructions["element_label_value"] . "</label>";
				$chkbx_node = $this -> doc-> createElement("input");
				$chkbx_node -> setAttribute("type", "checkbox");
				$chkbx_node -> setAttribute("name", $instructions["element_id"]);
				$output["label_string"] = $label_string;
				$output["node_string"] = "<label class='" . $this -> checkbx_label_class . "'>" . $this -> doc -> saveXML($chkbx_node) . $instructions["element_label_value"] . "</label>";
			}


			if (strtoupper($nodeType) == "CHECKBX_PLUS"){
				//create a new element

				$label_string = "<span class='" . $this -> span_class . "'>" . $instructions["element_label_value"] . "</span>";
				$chkbx_node = $this -> doc-> createElement("input");
				$chkbx_node -> setAttribute("type", "checkbox");
				$chkbx_node -> setAttribute("name", $instructions["element_id"]);
				$output["label_string"] = $label_string;
				$output["node_string"] = "<label class='" . $this -> checkbx_label_class . "'>" . $this -> doc -> saveXML($chkbx_node) . $node -> getAttribute('associated_label') . "</label>";
			}


			if (strtoupper($nodeType) == "RADIO"){
				$label_string = "<label class='" . $this -> label_class . ' ' . $instructions["element_id"]."'>" . $instructions["element_label_value"] . "</label>";
				$yes_node = $this -> doc-> createElement("input");
				$yes_node -> setAttribute("type", "radio");
				$yes_node -> setAttribute("value", "Yes");
				$yes_node -> setAttribute("name", $instructions["element_id"]);
				$yes_string = "<label class='" . $this -> radio_label_class . "'>" . $this -> doc -> saveXML($yes_node) . "Yes</label>";

				$no_node = $this -> doc-> createElement("input");
				$no_node -> setAttribute("type", "radio");
				$no_node -> setAttribute("value", "No");
				$no_node -> setAttribute("name", $instructions["element_id"]);
				$no_string = "<label class='" . $this -> radio_label_class . "'>" . $this -> doc -> saveXML($no_node) . "No</label>";

				$output["label_string"] = $label_string;
				$output["node_string"] = $yes_string . $no_string;
			}

			if (strtoupper($nodeType) == "RADIO_PLUS"){
				$label_string = "<label class='" . $this -> label_class . "'>" . $instructions["element_label_value"] . "</label>";
				$yes_node = $this -> doc-> createElement("input");
				$yes_node -> setAttribute("type", "radio");
				$yes_node -> setAttribute("value", $node -> getAttribute("value1"));
				$yes_node -> setAttribute("name", $instructions["element_id"]);
				$yes_string = "<label class='" . $this -> radio_label_class . "'>" . $this -> doc -> saveXML($yes_node) . $node -> getAttribute("value1") ."</label>";

				$no_node = $this -> doc-> createElement("input");
				$no_node -> setAttribute("type", "radio");
				$no_node -> setAttribute("value", $node -> getAttribute("value2"));
				$no_node -> setAttribute("name", $instructions["element_id"]);
				$no_string = "<label class='" . $this -> radio_label_class . "'>" . $this -> doc -> saveXML($no_node) . $node -> getAttribute("value2")."</label>";

				$output["label_string"] = $label_string;
				$output["node_string"] = $yes_string . $no_string;
			}

			if (strtoupper($nodeType) == "RADIO_ACTION_TRIGGER"){
				$label_string = "<label id='" .$instructions["element_id_prefix"]. $instructions["element_id"] . "' class='" . $instructions["element_class"] . " " . $this -> label_class . " " .$instructions["element_id"]. " c_" .$instructions["element_name"]."'>" . $instructions["element_label_value"] . "</label>";
				$yes_node = $this -> doc-> createElement("input");
				$yes_node -> setAttribute("type", "radio");
				$yes_node -> setAttribute("value", "Yes");
				$yes_node -> setAttribute("name", $instructions["element_id"]);
				if ($instructions["element_trigger"]["required"]){
					$yes_node -> setAttribute("onclick", $instructions["element_trigger"]["methodName"] . "(this,'" .  $instructions["element_trigger"]["invocation"] . "', '" . $instructions["element_trigger"]["action"] . "');");
				}
				$yes_node -> setAttribute("class",$instructions["element_class"]. " c_".$instructions["element_name"]);

				$yes_string = "<label class='" . $instructions["element_class"] . " " . $this -> radio_label_class . "'>" . $this -> doc -> saveXML($yes_node) . "Yes</label>";

				$no_node = $this -> doc-> createElement("input");
				$no_node -> setAttribute("type", "radio");
				$no_node -> setAttribute("value", "No");
				$no_node -> setAttribute("name", $instructions["element_id"]);
				if ($instructions["element_trigger"]["required"]){
					$no_node -> setAttribute("onclick", $instructions["element_trigger"]["methodName"] . "(this,'" .  $instructions["element_trigger"]["invocation"] . "', '" . $instructions["element_trigger"]["action"] . "');");
				}

				$no_node -> setAttribute("class", $instructions["element_class"]. " c_".$instructions["element_name"]);

				$no_string = "<label class='" . $instructions["element_class"] . " " . $this -> radio_label_class . "'>" . $this -> doc -> saveXML($no_node) . "No</label>";

				$output["label_string"] = $label_string;
				$output["node_string"] = $yes_string . $no_string;
			}

			if (strtoupper($nodeType) == "RADIO_ACTION_TRIGGER_PLUS"){
				$label_string = "<label id='" .$instructions["element_id_prefix"]. $instructions["element_id"] . "' class='" . $instructions["element_class"] . " " . $this -> label_class . $instructions["element_id"]."'>" . $instructions["element_label_value"] . "</label>";
				$yes_node = $this -> doc-> createElement("input");
				$yes_node -> setAttribute("type", "radio");
				$yes_node -> setAttribute("value", $node -> getAttribute("value1"));
				$yes_node -> setAttribute("name", $instructions["element_id"]);
				if ($instructions["element_trigger"]["required"]){
					$yes_node -> setAttribute("onclick", $instructions["element_trigger"]["methodName"] . "(this,'" .  $instructions["element_trigger"]["invocation"] . "', '" . $instructions["element_trigger"]["action"] . "');");
				}
				$yes_node -> setAttribute("class",$instructions["element_class"]);

				$yes_string = "<label class='" . $instructions["element_class"] . " " . $this -> radio_label_class . "'>" . $this -> doc -> saveXML($yes_node) . $node -> getAttribute("value1")."</label>";

				$no_node = $this -> doc-> createElement("input");
				$no_node -> setAttribute("type", "radio");
				$no_node -> setAttribute("value", $node -> getAttribute("value2"));
				$no_node -> setAttribute("name", $instructions["element_id"]);
				if ($instructions["element_trigger"]["required"]){
					$no_node -> setAttribute("onclick", $instructions["element_trigger"]["methodName"] . "(this,'" .  $instructions["element_trigger"]["invocation"] . "', '" . $instructions["element_trigger"]["action"] . "');");
				}

				$no_node -> setAttribute("class", $instructions["element_class"]);

				$no_string = "<label class='" . $instructions["element_class"] . " " . $this -> radio_label_class . "'>" . $this -> doc -> saveXML($no_node) . $node -> getAttribute("value2")."</label>";

				$output["label_string"] = $label_string;
				$output["node_string"] = $yes_string . $no_string;
			}


			if (strtoupper($nodeType) == "TEXT_HORIZONTAL"){
				//create a new element
				$label_string = "<label id='".$instructions["element_id_prefix"] . $instructions["element_id"] . "'>" . $instructions["element_label_value"] . "</label>";
				$node -> setAttribute("type", "text");
				$node -> setAttribute("name", $instructions["element_id"]);
				$node -> setAttribute("placeholder", $instructions["element_placeholder"]);
				$node -> setAttribute("class", $this -> input_class);
				$output["label_string"] = $label_string;
				$output["node_string"] = $this -> doc -> saveHTML($node);
			}

			if (strtoupper($nodeType) == "SELECT_HORIZONTAL"){
				//create a new element
				$label_string = "<label>" . $instructions["element_label_value"] . "</label>";
				$count = 0;

				$options = $xpath->evaluate("option", $node);
				$select = $this -> doc -> createElement("select");
				$select -> setAttribute("name", $instructions["element_id"]);
				$select -> setAttribute("class", $node -> getAttribute('class'));
				foreach ($options as $option) {
					$select -> appendChild($option);
				}

				$output["label_string"] = $label_string;
				$output["node_string"] = $this -> doc -> saveHTML($select);
			}

			if (strtoupper($nodeType) == "SHOW_HIDE_STATIC_TEXT"){
				//used for plan options
				$label_string = "<label id='".$instructions["element_id_prefix"]. $instructions["element_id"] . "' class='" . $instructions["element_class"] . " " . $this -> label_class . " " . $node -> getAttribute("class").' '.$instructions["element_id"]. " c_" .$instructions["element_name"]."'>" . $instructions["element_label_value"] . "</label>";
				$output["label_string"] = $label_string;
				$output["node_string"] = "<span class='". $node -> getAttribute("class")." to-be-removed'></span>";
			}

			if (strtoupper($nodeType) == "STATIC_TEXT"){
				//to be used for generic use
				$label_string = "<p id='".$instructions["element_id_prefix"]. $instructions["element_id"] . "' class='". $node -> getAttribute("class"). ' '.$instructions["element_id"]. " c_" .$instructions["element_name"]."'>" . $instructions["element_label_value"] . " </p>";
				$output["label_string"] = $label_string;
				$output["node_string"] ="<span class='". $node -> getAttribute("class")." to-be-removed'></span>";
			}

		}

		return $output;
   }
}
