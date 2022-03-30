<?php 

namespace JHU\ColumnSplitter;

use \REDCap as REDCap;

/**
 * 
 */

class ColumnSplitter extends \ExternalModules\AbstractExternalModule
{
    protected $is_survey = 0;
    protected static $Tags = array('@COLUMNSPLIT'=>array('comparison'=>'gt'));

    public function redcap_data_entry_form($project_id, $record=null, $instrument, $event_id, $group_id=null, $repeat_instance=1) {
        echo "redcap_data_entry_form_top";
        $this->includeTagFunctions($instrument);
    }

    public function redcap_survey_page($project_id, $record=null, $instrument, $event_id, $group_id=null, $survey_hash=null, $response_id=null, $repeat_instance=1) {
        echo "redcap_survey_page_top";
        $this->is_survey = 1;
        $this->includeTagFunctions($instrument);
    }

    protected function includeTagFunctions($instrument) {
        $taggedFields = array();
        $tags = static::$Tags;
        $instrumentFields = \REDCap::getDataDictionary('array', false, true, $instrument);
        $pattern = '/('.implode('|', array_keys($tags)).')/';
        foreach ($instrumentFields as $fieldName => $fieldDetails) {
            $matches = array();
            if (preg_match($pattern, $fieldDetails['field_annotation'], $matches)) {
                $taggedFields[$fieldName] = $fieldDetails['field_annotation'];
            }
        }
        if (count($taggedFields)>0) {
            $this->includeJS($taggedFields);
        }
    }

    protected function includeJS($taggedFields) {
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
                console.log(taggedFields);
                console.log(taggedFields.length);
                Object.keys(taggedFields).forEach(function(fld) {
                        console.log(fld);
                        var txt = taggedFields[fld];
                        var newTxt = txt.split('@COLUMNSPLIT(');
                        for (var i = 1; i < newTxt.length; i++) {
                            var colums = newTxt[i].split(')')[0];
                        }
                        var fldname = '__chkn__' + fld;
                    SplitChoicesIntoColumns(fldname,colums);
                });
            });
            function SplitChoicesIntoColumns(ElementName, cols){
                var question = document.getElementsByName(ElementName); // get the choices of the question given
                var NumberOfChoices = question.length;  // establish how many choices are associated with this questionparent

                // initialize arrays to be used in the loops
                var questionparent = [];
                var choiceHTML = [];
                var FinalHTML = '<table class="msnopad"><tr>';
                // first loop goes through all the choices and gets their inner HTML
                for (i = 0; i < NumberOfChoices; i++)
                {
                    FinalHTML = FinalHTML + "<td>" + question[i].parentElement.innerHTML + "</td>";
                    if (i>0)
                    {
                        if (((i+1) % cols) === 0)
                        {
                            if (i == NumberOfChoices - 1)
                            {
                                FinalHTML = FinalHTML + "</tr>";
                            }
                            else
                            {
                                FinalHTML = FinalHTML + "</tr><tr>";
                            }
                        }
                    }
                    choiceHTML[i] = question[i].parentElement;
                }
                FinalHTML = FinalHTML + "</table>"

                // second loop goes through and removes all of the HTML code
                // this is done since if we were to remove any HTML in the first loop it would "damage" the HTML we are trying to retreive
                // this loop also creates the HTMl that we will be using to split the answers in "cols" columns

                for (x = 1; x < NumberOfChoices; x++)
                {
                    //choiceHTML[x].innerHTML = null;
                    choiceHTML[x].remove();
                }
                choiceHTML[0].innerHTML = FinalHTML;
            }
        </script>
        <?php
    }

}