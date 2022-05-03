<?php

namespace JHU\MultiColumnMenu;

use \REDCap as REDCap;

/**
 * 
 */
class MultiColumnMenu extends \ExternalModules\AbstractExternalModule
{

	private $tag = '@COLUMNS';  //assign the tag name

    function getTags()  //Use Andy's helper class to ge the action tags in use
    {
        if (!class_exists('\JHU\MultiColumnMenu\ActionTagHelper')) include_once('classes/ActionTagHelper.php');
        $action_tag_results = ActionTagHelper::getActionTags($this->tag);
        //print "<pre>" . print_r($action_tag_results,true) . "</pre>"; // print if needed for debug
        return $action_tag_results;
    }
    function redcap_survey_page($project_id)
    {
        $tag_results = $this->getTags();
        $TagArray = array();
        foreach ($tag_results as $tagname)
        {
            foreach ($tagname as $mdaKey => $mdaData) {
                $TagArray[$mdaKey] = $mdaData["params"];
            }
        }
        $this->includeJS($TagArray);
    }

    function redcap_data_entry_form($project_id,$record) // yes this is duplicate code, but I fell it is better to use the built in survey_page and entry_form instead of every_page_top
    {
        $tag_results = $this->getTags();
        $TagArray = array();
    foreach ($tag_results as $tagname)
    {
        foreach ($tagname as $mdaKey => $mdaData) {
            $TagArray[$mdaKey] = $mdaData["params"];
        }
    }
        $this->includeJS($TagArray);
    }

    protected function includeJS($taggedFields) {     // PHP function that will run all the needed JavaScript

        ?>
        <style>
            table.msnopad {
                text-align:left;
                padding:2px;
                column-gap: 4px;
                width: 100%;
                vertical-align: middle;
            }
            table.msnopad td {
                font-size: 90%;
                white-space:nowrap;
            }
        </style>
        <script type="text/javascript">
            window.addEventListener("load", function() {
                var taggedFields = JSON.parse('<?php echo json_encode($taggedFields); ?>');
                Object.keys(taggedFields).forEach(key => {

                     // Pull the number of columns specified in the action tag (parseInt() converts the value to Integer or NaN if empty or invalid)
                    var columns = parseInt(taggedFields[key]); 

                    // Default to 1 column if an invalid column count was entered in the action tag (0, -1, letters ...).
                    // NOTE: If non-numeric, parseInt (above) sets it to NaN.
                    if (columns < 1 || isNaN(columns)) {
                        columns = 1;
                    }

                    var fldname = '__chkn__' + key;
                    SplitChoicesIntoColumns(fldname,columns);
                    var fldname = key + '___radio';
                    SplitChoicesIntoColumns(fldname,columns);
                });
            });
            function SplitChoicesIntoColumns(ElementName, cols) {

                var FieldChoices = document.getElementsByName(ElementName); // Pull the choices for this field
                var NumberOfChoices = FieldChoices.length;  // Get number of choices associated with this field

                // No need to do anything if there is only one choice! (or if the parameter is invalid, which sets columns=1, above)
                if (NumberOfChoices > 1) {
                    // initialize arrays to be used in the loops
                    var ChoicesArray = [];

                    // initialize
                    var Choices_NewHTML = '<table class="msnopad">';

                    var rows = 0;
                    var ArrayPos = 0;

                    rows = Math.ceil(NumberOfChoices / cols);

                    // Now that we know now many columns and rows are involved, we can start building the HTML using two "for" loops (one for rows and one for columns)

                    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    // This code orders the options VERTICALLY (e.g. 1, 2, 3 will be in COLUMN one, 4, 5, 6 in column 2, etc...)
                    if(FieldChoices[0].parentElement.className === 'choicevert') {
                        for (r = 0; r < rows; r++) {
                            Choices_NewHTML = Choices_NewHTML + "<tr>";
                            for (c = 0; c < cols; c++)
                            {
                                ArrayPos = (c * rows) + r;

                                // The nesteed for() loops will hit every possible position (every row/column position).
                                // However, the choices may not totally fill the last row/column (e.g. a 5x5 table with only 23 choices)
                                // Before adding it, check if the current ArrayPos (row/column position) is associated with an actual choice.
                                if (ArrayPos < NumberOfChoices)  // May run out of elements (selectable options) BEFORE filling up the last column.
                                {
                                    Choices_NewHTML = Choices_NewHTML + "<td>" + FieldChoices[ArrayPos].parentElement.innerHTML + "</td>";
                                    ChoicesArray[ArrayPos] = FieldChoices[ArrayPos].parentElement; // add existing element to new array
                                }
                            }
                            Choices_NewHTML = Choices_NewHTML + "</tr>";
                        }
                    }
                    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    // This code orders the options HORIZONTALLY (e.g. 1, 2, 3 will be in ROW one, 4, 5, 6 in column 2, etc...)
                    if(FieldChoices[0].parentElement.className === 'choicehoriz') {
                        for (r = 0; r < rows; r++) {
                            Choices_NewHTML = Choices_NewHTML + "<tr>";
                            for (c = 0; c < cols; c++) {
                                ArrayPos = (r * cols) + c;

                                // The nesteed for() loops will hit every possible position (every row/column position).
                                // However, the choices may not totally fill the last row/column (e.g. a 5x5 table with only 23 choices)
                                // Before adding it, check if the current ArrayPos (row/column position) is associated with an actual choice.
                                if (ArrayPos < NumberOfChoices) {
                                    Choices_NewHTML = Choices_NewHTML + "<td>" + FieldChoices[ArrayPos].parentElement.innerHTML + "</td>";
                                    ChoicesArray[ArrayPos] = FieldChoices[ArrayPos].parentElement; // add existing element to new array
                                }
                            }
                            Choices_NewHTML = Choices_NewHTML + "</tr>";
                        }
                    }
                    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                    //close html table
                    Choices_NewHTML = Choices_NewHTML + "</table>"

                    // loop through and remove original document elements (all but the first, which we leverage below)
                    for (x = 1; x < NumberOfChoices; x++) {
                        ChoicesArray[x].remove();
                    }

                    // Add new element list (with reordered positions) to replace the old elements (which were just dropped).
                    ChoicesArray[0].innerHTML = Choices_NewHTML;
                }
            }
        </script>
        <?php
    }
}//end class