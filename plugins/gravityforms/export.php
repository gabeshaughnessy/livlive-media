<?php

class GFExport{
    private static $min_import_version = "1.3.12.3";

    public static function maybe_export(){
        if(isset($_POST["export_lead"])){
            check_admin_referer("rg_start_export", "rg_start_export_nonce");
            $form_id=$_POST["export_form"];
            $form = RGFormsModel::get_form_meta($form_id);

            $filename = sanitize_title_with_dashes($form["title"]) . "-" . date("Y-m-d") . ".csv";
            $charset = get_option('blog_charset');
            header('Content-Description: File Transfer');
            header("Content-Disposition: attachment; filename=$filename");
            header('Content-Type: text/plain; charset=' . $charset, true);
            ob_clean();
            GFExport::start_export($form);

            die();
        }
        else if(isset($_POST["export_forms"])){
            check_admin_referer("gf_export_forms", "gf_export_forms_nonce");
            $selected_forms = $_POST["gf_form_id"];
            if(empty($selected_forms)){
                echo "<div class='error' style='padding:15px;'>" . __("Please select the forms to be exported", "gravityforms") . "</div>";
                return;
            }

            $forms = RGFormsModel::get_forms_by_id($selected_forms);

            //removing the inputs for checkboxes (choices will be used during the import)
            foreach($forms as &$form){
                foreach($form["fields"] as &$field){
                    $inputType = RGFormsModel::get_input_type($field);

                    unset($field["pageNumber"]);

                    if($inputType == "checkbox")
                        unset($field["inputs"]);
                    else if($inputType != "address")
                        unset($field["addressType"]);
                    else if($inputType != "date")
                        unset($field["calendarIconType"]);
                    else if($field["type"] == $field["inputType"])
                        unset($field["inputType"]);


                    if(in_array($inputType, array("checkbox", "radio", "select")) && !$field["enableChoiceValue"]){
                        foreach($field["choices"] as &$choice)
                            unset($choice["value"]);
                    }
                }
            }

            require_once("xml.php");

             $options = array(
                "version" => GFCommon::$version,
                "forms/form/id" => array("is_hidden" => true),
                "forms/form/nextFieldId" => array("is_hidden" => true),
                "forms/form/notification/routing" => array("array_tag" => "routing_item"),
                "forms/form/useCurrentUserAsAuthor" => array("is_attribute" => true),
                "forms/form/postAuthor" => array("is_attribute" => true),
                "forms/form/postCategory" => array("is_attribute" => true),
                "forms/form/postStatus" => array("is_attribute" => true),
                "forms/form/postAuthor" => array("is_attribute" => true),
                "forms/form/labelPlacement" => array("is_attribute" => true),
                "forms/form/confirmation/type" => array("is_attribute" => true),
                "forms/form/lastPageButton/type" => array("is_attribute" => true),
                "forms/form/pagination/type" => array("is_attribute" => true),
                "forms/form/pagination/style" => array("is_attribute" => true),
                "forms/form/button/type" => array("is_attribute" => true),
                "forms/form/button/conditionalLogic/actionType" => array("is_attribute" => true),
                "forms/form/button/conditionalLogic/logicType" => array("is_attribute" => true),
                "forms/form/button/conditionalLogic/rules/rule/fieldId" => array("is_attribute" => true),
                "forms/form/button/conditionalLogic/rules/rule/operator" => array("is_attribute" => true),
                "forms/form/button/conditionalLogic/rules/rule/value" => array("allow_empty" => true),
                "forms/form/fields/field/id" => array("is_attribute" => true),
                "forms/form/fields/field/type" => array("is_attribute" => true),
                "forms/form/fields/field/inputType" => array("is_attribute" => true),
                "forms/form/fields/field/displayOnly" => array("is_attribute" => true),
                "forms/form/fields/field/size" => array("is_attribute" => true),
                "forms/form/fields/field/isRequired" => array("is_attribute" => true),
                "forms/form/fields/field/noDuplicates" => array("is_attribute" => true),
                "forms/form/fields/field/inputs/input/id" => array("is_attribute" => true),
                "forms/form/fields/field/inputs/input/name" => array("is_attribute" => true),
                "forms/form/fields/field/formId" => array("is_hidden" => true),
                "forms/form/fields/field/allowsPrepopulate" => array("is_attribute" => true),
                "forms/form/fields/field/adminOnly" => array("is_attribute" => true),
                "forms/form/fields/field/enableChoiceValue" => array("is_attribute" => true),
                "forms/form/fields/field/conditionalLogic/actionType" => array("is_attribute" => true),
                "forms/form/fields/field/conditionalLogic/logicType" => array("is_attribute" => true),
                "forms/form/fields/field/conditionalLogic/rules/rule/fieldId" => array("is_attribute" => true),
                "forms/form/fields/field/conditionalLogic/rules/rule/operator" => array("is_attribute" => true),
                "forms/form/fields/field/conditionalLogic/rules/rule/value" => array("allow_empty" => true),
                "forms/form/fields/field/previousButton/type" => array("is_attribute" => true),
                "forms/form/fields/field/nextButton/type" => array("is_attribute" => true),
                "forms/form/fields/field/nextButton/conditionalLogic/actionType" => array("is_attribute" => true),
                "forms/form/fields/field/nextButton/conditionalLogic/logicType" => array("is_attribute" => true),
                "forms/form/fields/field/nextButton/conditionalLogic/rules/rule/fieldId" => array("is_attribute" => true),
                "forms/form/fields/field/nextButton/conditionalLogic/rules/rule/operator" => array("is_attribute" => true),
                "forms/form/fields/field/nextButton/conditionalLogic/rules/rule/value" => array("allow_empty" => true),
                "forms/form/fields/field/choices/choice/isSelected" => array("is_attribute" => true),
                "forms/form/fields/field/choices/choice/text" => array("allow_empty" => true),
                "forms/form/fields/field/choices/choice/value" => array("allow_empty" => true),
                "forms/form/fields/field/rangeMin" => array("is_attribute" => true),
                "forms/form/fields/field/rangeMax" => array("is_attribute" => true),
                "forms/form/fields/field/calendarIconType" => array("is_attribute" => true),
                "forms/form/fields/field/dateFormat" => array("is_attribute" => true),
                "forms/form/fields/field/dateType" => array("is_attribute" => true),
                "forms/form/fields/field/nameFormat" => array("is_attribute" => true),
                "forms/form/fields/field/phoneFormat" => array("is_attribute" => true),
                "forms/form/fields/field/addressType" => array("is_attribute" => true),
                "forms/form/fields/field/hideCountry" => array("is_attribute" => true),
                "forms/form/fields/field/hideAddress2" => array("is_attribute" => true),
                "forms/form/fields/field/displayTitle" => array("is_attribute" => true),
                "forms/form/fields/field/displayCaption" => array("is_attribute" => true),
                "forms/form/fields/field/displayDescription" => array("is_attribute" => true),
                "forms/form/fields/field/displayAllCategories" => array("is_attribute" => true),
                "forms/form/fields/field/postCustomFieldName" => array("is_attribute" => true)
            );

            $serializer = new RGXML($options);

            $xml .= $serializer->serialize("forms", $forms);

            if ( !seems_utf8( $xml ) )
                $value = utf8_encode( $xml );

            $filename = "gravityforms-export-" . date("Y-m-d") . ".xml";
            header('Content-Description: File Transfer');
            header("Content-Disposition: attachment; filename=$filename");
            header('Content-Type: text/xml; charset=' . get_option('blog_charset'), true);
            echo $xml;
            die();
        }
    }

    public static function export_page(){
        if(!GFCommon::ensure_wp_version())
            return;

        echo GFCommon::get_remote_message();

        $view = RGForms::get("view");
        switch($view){
            case "import_form" :
                self::import_form_page();
            break;

            case "export_form" :
                self::export_form_page();
            break;

            default:
                self::export_lead_page();
            break;
        }


    }

    public static function export_links(){
        $view = RGForms::get("view");

        ?>
        <ul class="subsubsub">
            <li><a href="?page=gf_export&view=export_entry" class="<?php echo $view=="export_entry" || empty($view) ? 'current' : ''; ?>"><?php _e("Export Entries", "gravityforms"); ?></a> | </li>
            <li><a href="?page=gf_export&view=export_form" class="<?php echo $view=="export_form" ? 'current' : ''; ?>"><?php _e("Export Forms", "gravityforms"); ?></a> | </li>
            <li><a href="?page=gf_export&view=import_form" class="<?php echo $view=="import_form" ? 'current' : ''; ?>"><?php _e("Import Forms", "gravityforms"); ?></a></li>
        </ul>
        <br style="clear:both"/>
        <br/>
        <?php
    }

    public static function import_file($filepath){

        $xmlstr = file_get_contents($filepath);

        require_once("xml.php");

        $options = array(
                        "page" => array("unserialize_as_array" => true),
                        "form"=> array("unserialize_as_array" => true),
                        "field"=> array("unserialize_as_array" => true),
                        "rule"=> array("unserialize_as_array" => true),
                        "choice"=> array("unserialize_as_array" => true),
                        "input"=> array("unserialize_as_array" => true),
                        "routing_item"=> array("unserialize_as_array" => true),
                        "routin"=> array("unserialize_as_array" => true) //routin is for backwards compatibility
                        );
        $xml = new RGXML($options);
        $forms = $xml->unserialize($xmlstr);

        if(!$forms)
            return 0;   //Error. could not unserialize XML file
        else if(version_compare($forms["version"], self::$min_import_version, "<"))
            return -1;  //Error. XML version is not compatible with current Gravity Forms version

        //cleaning up generated object
        self::cleanup($forms);

        foreach($forms as $key => $form){
            $title = $form["title"];
            $count = 2;
            while(!RGFormsModel::is_unique_title($title)){
                $title = $form["title"] . "($count)";
                $count++;
            }

            //inserting form
            $form_id = RGFormsModel::insert_form($title);

            //updating form meta
            $form["title"] = $title;
            $form["id"] = $form_id;
            RGFormsModel::update_form_meta($form_id, $form);
        }
        return sizeof($forms);

    }

    public static function import_form_page(){
        if(isset($_POST["import_forms"])){
            check_admin_referer("gf_import_forms", "gf_import_forms_nonce");

            if(!empty($_FILES["gf_import_file"]["tmp_name"])){

                $count = self::import_file($_FILES["gf_import_file"]["tmp_name"]);

                if($count == 0 ){
                    ?>
                    <div class="error" style="padding:10px;"><?php _e("Forms could not be imported. Please make sure your XML export file is in the correct format.", "gravityforms"); ?></div>
                    <?php
                }
                else if($count == "-1"){
                    ?>
                    <div class="error" style="padding:10px;"><?php _e("Forms could not be imported. Your XML export file is not compatible with your current version of Gravity Forms.", "gravityforms"); ?></div>
                    <?php
                }
                else
                {
                    $form_text = $count > 1 ? __("forms", "gravityforms") : __("form", "gravityforms");
                    ?>
                    <div class="updated" style="padding:10px;"><?php echo sprintf(__("Gravity Forms imported %d {$form_text} successfully", "gravityforms"), $count); ?></div>
                    <?php
                }
            }
        }

        ?>
        <link rel="stylesheet" href="<?php echo GFCommon::get_base_url()?>/css/admin.css"/>
        <div class="wrap">
            <img alt="<?php _e("Gravity Forms", "gravityforms") ?>" style="margin: 15px 7px 0pt 0pt; float: left;" src="<?php echo GFCommon::get_base_url() ?>/images/gravity-import-icon-32.png"/>
            <h2><?php _e("Import Forms", "gravityforms") ?></h2>





            <p class="textleft"><?php
            self::export_links();

            _e("Select the Gravity Forms XML file you would like to import. When you click the import button below, Gravity Forms will import the forms.", "gravityforms");
            ?></p>
            <div class="hr-divider"></div>




            <form method="post" enctype="multipart/form-data" style="margin-top:10px;">
                <?php echo wp_nonce_field("gf_import_forms", "gf_import_forms_nonce"); ?>
                <table class="form-table">
                  <tr valign="top">

                       <th scope="row"><label for="gf_import_file"><?php _e("Select File", "gravityforms");?></label></th>
                        <td><input type="file" name="gf_import_file" id="gf_import_file"/></td>
                  </tr>
            </table>
            <br /><br />
                <input type="submit" value="<?php _e("Import", "gravityforms")?>" name="import_forms" class="button-primary" />

            </form>
        </div>
        <?php
    }

    public static function export_form_page(){

        ?>
        <link rel="stylesheet" href="<?php echo GFCommon::get_base_url()?>/css/admin.css"/>
        <div class="wrap">
            <img alt="<?php _e("Gravity Forms", "gravityforms") ?>" style="margin: 15px 7px 0pt 0pt; float: left;" src="<?php echo GFCommon::get_base_url() ?>/images/gravity-export-icon-32.png"/>
            <h2><?php _e("Export Forms", "gravityforms") ?></h2>
            <?php
            self::export_links();
            ?>

            <p class="textleft"><?php _e("Select the forms you would like to export. When you click the download button below, Gravity Forms will create a XML file for you to save to your computer. Once you've saved the download file, you can use the Import tool to import the forms.", "gravityforms"); ?></p>
			<div class="hr-divider"></div>
            <form method="post" style="margin-top:10px;">
                <?php echo wp_nonce_field("gf_export_forms", "gf_export_forms_nonce"); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row"><label for="export_fields"><?php _e("Select Forms", "gravityforms"); ?></label> <?php gform_tooltip("export_select_forms") ?></th>
                        <td>
                            <ul id="export_form_list">
                                <?php
                                $forms = RGFormsModel::get_forms(null, "title");
                                foreach($forms as $form){
                                    ?>
                                    <li>
                                        <input type="checkbox" name="gf_form_id[]" id="gf_form_id_<?php echo absint($form->id)?>" value="<?php echo absint($form->id)?>"/>
                                        <label for="gf_form_id_<?php echo absint($form->id)?>"><?php echo esc_html($form->title) ?></label>
                                    </li>
                                    <?php
                                }
                                ?>
                            <ul>
                        </td>
                   </tr>
                </table>

                 <br/><br/>
                <input type="submit" value="<?php _e("Download Export File", "gravityforms")?>" name="export_forms" class="button-primary" />
            </form>
        </div>
        <?php
    }

    public static function export_lead_page(){
        ?>
        <script type='text/javascript' src='<?php echo GFCommon::get_base_url()?>/js/jquery-ui/ui.datepicker.js?ver=<?php echo GFCommon::$version ?>'></script>
        <script type="text/javascript">
            function SelectExportForm(formId){
                if(!formId)
                    return;

                var mysack = new sack("<?php echo admin_url("admin-ajax.php")?>" );
                mysack.execute = 1;
                mysack.method = 'POST';
                mysack.setVar( "action", "rg_select_export_form" );
                mysack.setVar( "rg_select_export_form", "<?php echo wp_create_nonce("rg_select_export_form") ?>" );
                mysack.setVar( "form_id", formId);
                mysack.encVar( "cookie", document.cookie, false );
                mysack.onError = function() { alert('<?php echo esc_js(__("Ajax error while selecting a form", "gravityforms")) ?>' )};
                mysack.runAJAX();

                return true;
            }

            function EndSelectExportForm(aryFields){
                if(aryFields.length == 0)
                {
                    jQuery("#export_field_container, #export_date_container, #export_submit_container").hide()
                    return;
                }

                var fieldList = "<li><input type='checkbox' onclick=\"jQuery('.gform_export_field').attr('checked', this.checked); jQuery('#gform_export_check_all').html(this.checked ? '<strong><?php _e("Deselect All", "gravityforms") ?></strong>' : '<strong><?php _e("Select All", "gravityforms") ?></strong>'); \"> <label id='gform_export_check_all'><strong><?php _e("Select All", "gravityforms") ?></strong></label></li>";
                for(var i=0; i<aryFields.length; i++){
                    fieldList += "<li><input type='checkbox' id='export_field_" + i + "' name='export_field[]' value='" + aryFields[i][0] + "' class='gform_export_field'> <label for='export_field_" + i + "'>" + aryFields[i][1] + "</label></li>";
                }
                jQuery("#export_field_list").html(fieldList);
                jQuery("#export_date_start, #export_date_end").datepicker({dateFormat: 'yy-mm-dd'});

                jQuery("#export_field_container, #export_date_container, #export_submit_container").hide().show();
            }
        </script>
        <link rel="stylesheet" href="<?php echo GFCommon::get_base_url()?>/css/admin.css"/>

        <div class="wrap">
            <img alt="<?php _e("Gravity Forms", "gravityforms") ?>" style="margin: 15px 7px 0pt 0pt; float: left;" src="<?php echo GFCommon::get_base_url() ?>/images/gravity-export-icon-32.png"/>
            <h2><?php _e("Export Form Entries", "gravityforms") ?></h2>
            <?php
            self::export_links();
            ?>

            <p class="textleft"><?php _e("Select a form below to export entries. Once you have selected a form you may select the fields you would like to export and an optional date range. When you click the download button below, Gravity Forms will create a CSV file for you to save to your computer.", "gravityforms"); ?></p>
            <div class="hr-divider"></div>
            <form method="post" style="margin-top:10px;">
                <?php echo wp_nonce_field("rg_start_export", "rg_start_export_nonce"); ?>
                <table class="form-table">
                  <tr valign="top">

                       <th scope="row"><label for="export_form"><?php _e("Select A Form", "gravityforms"); ?></label> <?php gform_tooltip("export_select_form") ?></th>
                        <td>

                          <select id="export_form" name="export_form" onchange="SelectExportForm(jQuery(this).val());">
                            <option value=""><?php _e("Select a form", "gravityforms"); ?></option>
                            <?php
                            $forms = RGFormsModel::get_forms(null, "title");
                            foreach($forms as $form){
                                ?>
                                <option value="<?php echo absint($form->id) ?>"><?php echo esc_html($form->title) ?></option>
                                <?php
                            }
                            ?>
                        </select>

                        </td>
                    </tr>
                  <tr id="export_field_container" valign="top" style="display: none;">
                       <th scope="row"><label for="export_fields"><?php _e("Select Fields", "gravityforms"); ?></label> <?php gform_tooltip("export_select_fields") ?></th>
                        <td>
                            <ul id="export_field_list">
                            <ul>
                        </td>
                   </tr>
                  <tr id="export_date_container" valign="top" style="display: none;">
                       <th scope="row"><label for="export_date"><?php _e("Select Date Range", "gravityforms"); ?></label> <?php gform_tooltip("export_date_range") ?></th>
                        <td>
                            <div>
                                <span style="width:150px; float:left; ">
                                    <input type="text" id="export_date_start" name="export_date_start" style="width:90%"/>
                                    <strong><label for="export_date_start" style="display:block;"><?php _e("Start", "gravityforms"); ?></label></strong>
                                </span>

                                <span style="width:150px; float:left;">
                                    <input type="text" id="export_date_end" name="export_date_end" style="width:90%"/>
                                    <strong><label for="export_date_end" style="display:block;"><?php _e("End", "gravityforms"); ?></label></strong>
                                </span>
                                <div style="clear: both;"></div>
                                <?php _e("Date Range is optional, if no date range is selected all entries will be exported.", "gravityforms"); ?>
                            </div>
                        </td>
                   </tr>
                </table>
                <ul>
                    <li id="export_submit_container" style="display:none; clear:both;">
                        <br/><br/>
                        <input type="submit" name="export_lead" value="<?php _e("Download Export File", "gravityforms"); ?>" class="button-primary"/>
                        <span id="please_wait_container" style="display:none; margin-left:15px;">
                            <img src="<?php echo GFCommon::get_base_url()?>/images/loading.gif"> <?php _e("Exporting entries. Please wait...", "gravityforms"); ?>
                        </span>

                        <iframe id="export_frame" width="1" height="1" src="about:blank"></iframe>
                    </li>
                </ul>
            </form>
        </div>
        <?php


    }

    public static function start_export($form){

        $form_id = $form["id"];
        $fields = $_POST["export_field"];
        $start_date = $_POST["export_date_start"];
        $end_date = $_POST["export_date_end"];

        //adding default fields
        array_push($form["fields"],array("id" => "id" , "label" => __("Entry Id", "gravityforms")));
        array_push($form["fields"],array("id" => "date_created" , "label" => __("Entry Date", "gravityforms")));
        array_push($form["fields"],array("id" => "ip" , "label" => __("User IP", "gravityforms")));
        array_push($form["fields"],array("id" => "source_url" , "label" => __("Source Url", "gravityforms")));
        array_push($form["fields"],array("id" => "payment_status" , "label" => __("Payment Status", "gravityforms")));
        array_push($form["fields"],array("id" => "payment_date" , "label" => __("Payment Date", "gravityforms")));
        array_push($form["fields"],array("id" => "transaction_id" , "label" => __("Transaction Id", "gravityforms")));

        $entry_count = RGFormsModel::get_lead_count($form_id, "", null, null, $start_date, $end_date);

        $page_size = 200;
        $offset = 0;

        //Adding BOM marker for UTF-8
        $lines= chr(239) . chr(187) . chr(191);

        //writing header
        foreach($fields as $field_id){
            $field = RGFormsModel::get_field($form, $field_id);
            $value = '"' . str_replace('"', '""', GFCommon::get_label($field, $field_id)) . '"';
            $lines .= "$value,";
        }
        $lines = substr($lines, 0, strlen($lines)-1) . "\n";

        //paging through results for memory issues
        while($entry_count > 0){
            $leads = RGFormsModel::get_leads($form_id,"date_created", "DESC", "", $offset, $page_size, null, null, false, $start_date, $end_date);

            foreach($leads as $lead){
                foreach($fields as $field_id){
                    $long_text = "";
                    if(strlen($lead[$field_id]) >= GFORMS_MAX_FIELD_LENGTH)
                        $long_text = RGFormsModel::get_field_value_long($lead["id"], $field_id);

                    $value = !empty($long_text) ? $long_text : $lead[$field_id];

                    $lines .= '"' . str_replace('"', '""', $value) . '",';
                }
                $lines = substr($lines, 0, strlen($lines)-1);
                $lines.= "\n";
            }

            $offset += $page_size;
            $entry_count -= $page_size;

            if ( !seems_utf8( $lines ) )
                $lines = utf8_encode( $lines );

            echo $lines;
            $lines = "";
        }
    }

    private function cleanup(&$forms){
        unset($forms["version"]);

        //adding checkboxes "inputs" property based on "choices". (they were removed from the export
        //to provide a cleaner xml format
        foreach($forms as &$form){
            if(!is_array($form["fields"]))
                continue;

            foreach($form["fields"] as &$field){
                $input_type = RGFormsModel::get_input_type($field);
                if(in_array($input_type, array("checkbox", "radio", "select"))){

                    //creating inputs array for checkboxes
                    if($input_type == "checkbox" && !isset($field["inputs"]))
                        $field["inputs"] = array();

                    for($i=1, $count = sizeof($field["choices"]); $i<=$count; $i++){
                        if(!RGForms::get("enableChoiceValue", $field))
                            $field["choices"][$i-1]["value"] = $field["choices"][$i-1]["text"];

                        if($input_type == "checkbox")
                            $field["inputs"][] = array("id" => $field["id"] . "." . $i, "label" => $field["choices"][$i-1]["text"]);
                    }

                }
            }
        }
    }
}
?>