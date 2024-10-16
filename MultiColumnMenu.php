<?php

namespace JHU\MultiColumnMenu;

use \REDCap as REDCap;

/**
 * 
 */
class MultiColumnMenu extends \ExternalModules\AbstractExternalModule
{

	private $tag = '@COLUMNS';  //assign the tag name

    function getTags()  //Use Andy's helper class to get the action tags in use
    {
        if (!class_exists('\JHU\MultiColumnMenu\ActionTagHelper')) include_once('classes/ActionTagHelper.php');
        $action_tag_results = ActionTagHelper::getActionTags($this->tag);
        return $action_tag_results;
    }
    function redcap_survey_page($project_id)
    {
        if ($this->delayModuleExecution())
        { // Delay module execution
            $tag_results = $this->getTags();            // Get the action tags in use
            $TagArray = array();
            foreach ($tag_results as $tagname)
            {
                foreach ($tagname as $mdaKey => $mdaData)
                {
                    $TagArray[$mdaKey] = $mdaData["params"];// Store the tag name and parameters
                }
            }
            $this->includeJS($TagArray);// Run the JavaScript
        }// end delay
    }// end redcap_survey_page

    function redcap_data_entry_form($project_id,$record) // yes this is duplicate code, but I fell it is better to use the built in survey_page and entry_form instead of every_page_top
    {
        if ($this->delayModuleExecution()){// Delay module execution
            $tag_results = $this->getTags();// Get the action tags in use
            $TagArray = array();
            foreach ($tag_results as $tagname)
            {
                foreach ($tagname as $mdaKey => $mdaData) {
                    $TagArray[$mdaKey] = $mdaData["params"];// Store the tag name and parameters
                }
            }
            $this->includeJS($TagArray);// Run the JavaScript
        }// end delay
    }// end redcap_data_entry_form


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
            /* Grid container */
            .mcem-grid-container {
                display: grid;
                grid-gap: 10px;
                padding: 10px;
            }

            /* Vertical layout */
            .mcem-grid-container.mcem-vertical {
                grid-template-columns: repeat(var(--columns), 1fr);
            }

            /* Horizontal layout */
            .mcem-grid-container.mcem-horizontal {
                grid-template-columns: repeat(var(--columns), 1fr);
            }

            /* Each choice is part of the grid */
            .mcem-grid-item {
                display: flex;
                align-items: center;
            }

            /* Hidden elements remain in the grid, but are invisible */
            .hidden {
                display: none;
            }


        </style>
        <script type="text/javascript">
            window.addEventListener("load", function() {
                var taggedFields = JSON.parse('<?php echo json_encode($taggedFields); ?>');

                Object.keys(taggedFields).forEach(key => {
                    var columns = parseInt(taggedFields[key]) || 1;
                    if (columns < 1) columns = 1;

                    var checkboxFldname = '__chkn__' + key;
                    var checkboxElements = document.getElementsByName(checkboxFldname);
                    if (checkboxElements.length > 0) {
                        if (!checkboxElements[0].hasAttribute('data-layout-processed')) {
                            applyGridLayout(checkboxFldname, columns, 'checkbox');
                        }
                    }

                    var radioFldname = key + '___radio';
                    var radioElements = document.getElementsByName(radioFldname);
                    if (radioElements.length > 0) {
                        if (!radioElements[0].hasAttribute('data-layout-processed')) {
                            applyGridLayout(radioFldname, columns, 'radio');
                        }
                    }
                });
            });
            function applyGridLayout(ElementName, cols, fieldType) {
                var FieldChoices = document.getElementsByName(ElementName);
                var NumberOfChoices = FieldChoices.length;

                var visibleChoices = [];
                var hiddenChoices = [];

                // Separate visible and hidden choices
                for (var i = 0; i < NumberOfChoices; i++) {
                    var parentElement = FieldChoices[i].parentElement;
                    if (parentElement.classList.contains('hidden')) {
                        hiddenChoices.push(parentElement); // Store hidden elements
                    } else {
                        visibleChoices.push(parentElement); // Store visible elements
                    }
                }

                // Function to reorder choices for vertical layout
                function reorderChoices(choices, columns) {
                    let orderedChoices = new Array(choices.length);
                    let rows = Math.ceil(choices.length / columns);

                    for (let i = 0; i < choices.length; i++) {
                        let col = Math.floor(i / rows);
                        let row = i % rows;
                        let newIndex = row * columns + col;
                        orderedChoices[newIndex] = choices[i];
                        console.log(choices[i]);
                    }

                    return orderedChoices;
                }

                // Create or find container for the choices
                var container = FieldChoices[0].parentElement.closest('.mcem-grid-container');
                if (!container) {
                    container = document.createElement('div');
                    container.classList.add('mcem-grid-container');
                    FieldChoices[0].parentElement.parentElement.insertBefore(container, FieldChoices[0].parentElement);
                }

                // Mark the elements as processed
                FieldChoices[0].setAttribute('data-layout-processed', 'true');

                // Set the number of columns
                container.style.setProperty('--columns', cols);
                let orderedVisibleChoices = visibleChoices;
                // Ensure layout is vertical if required
                if (FieldChoices[0].parentElement.classList.contains('choicevert')) {
                    container.classList.add('mcem-vertical');
                    orderedVisibleChoices = reorderChoices(visibleChoices, cols);
                } else if (FieldChoices[0].parentElement.classList.contains('choicehoriz')) {
                    container.classList.add('mcem-horizontal');
                }

                // Clear the container before adding elements
                container.innerHTML = '';

                // Reorder visible choices for the vertical layout


                // Add visible choices in the correct order
                orderedVisibleChoices.forEach(function(choice, index) {
                    var gridItem = document.createElement('div');
                    gridItem.classList.add('mcem-grid-item');
                    gridItem.appendChild(choice);
                    container.appendChild(gridItem);
                });

                // Append hidden choices at the end
                hiddenChoices.forEach(function(hiddenElement) {
                    var gridItem = document.createElement('div');
                    gridItem.classList.add('mcem-grid-item', 'hidden'); // Maintain grid structure but hidden
                    gridItem.appendChild(hiddenElement);
                    container.appendChild(gridItem);
                });
            }

           
        </script>


        <?php
    }
}//end class