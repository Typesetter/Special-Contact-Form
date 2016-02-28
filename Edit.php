<?php

namespace Addon\SCF{

	defined('is_running') or die('Not an entry point...');

	define('ckDefault', "toolbar : \n[\n['Bold', 'Italic', 'Underline', '-', 'Undo', 'Redo', '-', 'NumberedList', 'BulletedList', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', '-',\n'Format', '-', 'Link', '-', 'About']\n],\ntoolbarLocation : 'bottom',\ntoolbarStartupExpanded:'true',\nuiColor : '#eeeeee',\nheight:'12em'\n");

	class Edit extends \Addon\SCF\Form{


		function menu(){
			global $config, $addonRelativeCode,$page,$langmessage,$languages, $addonPathCode,$addonPathData,$title;
			$page->head_js[] = $addonRelativeCode.'/jquery.tablednd.0.7.min.js';
			$page->head_js[] = $addonRelativeCode.'/javascript.js';
			$page->head .= '<style type="text/css"> #dataTable tbody tr:hover > td {background-color:#7fff7f;} </style>';

			echo '<div style="float:right">'.$langmessage['language'];
			$this->Select_Languages();
			echo '</div>';

			echo '<div style="font-size:16px; margin-bottom:1.5em;">'.$langmessage['Settings'].'</div>'."\n";

	/*begin 1*/	echo '<div onclick="$(this).next(\'div\').toggle()" style="cursor:pointer"><b> 1. </b>'.$this->SCF_LANG['form_fields'].'</div>';
			echo '<div style="display:none"><br/>';
			echo '<form action="'.\common::GetUrl($title).'" method="post">'."\n";

			echo '<table id="dataTable" class="dataTable" cellspacing="1" cellpadding="1" style="width:97%;border:1px solid black">';
			echo '<colgroup>
					<col style="width:10%"/>
					<col style="width:5%"/>
					<col style="width:10%"/>
					<col style="width:10%"/>
					<col style="width:34%"/>
					<col style="width:34%"/>
				</colgroup>';
			echo '<thead><tr><td><input type="button" value="'.$langmessage['add'].'" onclick="addRow(\'dataTable\',\''.$langmessage['delete'].'\',\''.$this->SCF_LANG['item'].'\',\''.$this->SCF_LANG['input'].'\',\''.$this->SCF_LANG['checkbox'].'\',\''.$this->SCF_LANG['radiobox'].'\',\''.$this->SCF_LANG['selectbox'].'\',\''.$this->SCF_LANG['textarea'].'\',\''.$this->SCF_LANG['file'].'\')" /></td>
				<td style="color:blue"> ID </td><td>'.$langmessage['label'].'</td><td>'.$this->SCF_LANG['type'].'</td>
				<td><a href="'.$addonRelativeCode.'/validations.html" name="ajax_box">'.$this->SCF_LANG['validations'].'</a></td><td>'.$this->SCF_LANG['options'].'</td>
				</tr></thead>';
			echo '<tbody>';
			foreach ($this->items as $i => $value){
				echo '<tr id="row'.$i.'">';
				echo '<td> <input type="button" value="'.$langmessage['delete'].'" onclick="deleteRow(this)" />  </td>'; // add-remove dialog
				echo '<td>'.$i.'</td>';
				echo '<td> <input name="label'.$i.'" style="width:120px;" type="text" value="'.$value['label'].'" /> </td>';
				echo '<td><select name="type'.$i.'" onchange="checkSelectedType(this)" >
					<option value="input"    '.($value['type']=='input'?'selected="selected"':'').'>'.$this->SCF_LANG['input'].'</option>
					<option value="checkbox" '.($value['type']=='checkbox'?'selected="selected"':'').'>'.$this->SCF_LANG['checkbox'].'</option>
					<option value="radio"    '.($value['type']=='radio'?'selected="selected"':'').'>'.$this->SCF_LANG['radiobox'].'</option>
					<option value="select"   '.($value['type']=='select'?'selected="selected"':'').'>'.$this->SCF_LANG['selectbox'].'</option>
					<option value="textarea" '.($value['type']=='textarea'?'selected="selected"':'').'>'.$this->SCF_LANG['textarea'].'</option>
					<option value="file"     '.($value['type']=='file'?'selected="selected"':'').'>'.$this->SCF_LANG['file'].'</option>
					</select>';
				echo '</td>';
				echo '<td> <input name="valid'.$i.'" type="text" value="'.$value['valid'].'" /></td>';
				echo '<td> <input name="multi_values'.$i.'" type="text" value="'.$value['multi_values'].'" '.($value['type']=='radio' || $value['type']=='select' ? '':'style="display:none;"').' /> </td>';
				echo '</tr>';
			}
			echo '</tbody></table>';
			echo '<input id="maxval" name="maxval" type="hidden" value="'.count($this->items).'" />';
			echo '<br/>';
			echo '<table cellspacing="1" cellpadding="1" style="width:97%;border:1px solid black">';
			echo '<colgroup><col style="width:30%"/><col style="width:10%"/><col style="width:60%"/></colgroup>';
			echo '<tbody>';

			echo '<tr>';
			echo '<td>'.$this->SCF_LANG['name_id'].':</td>';
			echo '<td><input name="id_sendername" type="text" value="'.$this->data['id_sendername'].'" /></td>'."\n";
			echo '<td>- '.$this->SCF_LANG['must_be_input'].'</td>';
			echo '</tr>';

			echo '<tr>';
			echo '<td>'.$this->SCF_LANG['email_id'].':</td>';
			echo '<td><input name="id_senderemail" type="text" value="'.$this->data['id_senderemail'].'" /></td>'."\n";
			echo '<td>- '.$this->SCF_LANG['must_be_input'].'</td>';
			echo '</tr>';

			echo '<tr>';
			echo '<td>'.$this->SCF_LANG['subject_id'].':</td>';
			echo '<td><input name="id_sendersubject" type="text" value="'.$this->data['id_sendersubject'].'" /></td>'."\n";
			echo '<td>- '.$this->SCF_LANG['must_be_input_select'].'</td>';
			echo '</tr>';

			echo '<tr>';
			echo '<td>'.$this->SCF_LANG['message_id'].':</td>';
			echo '<td><input name="id_sendermessage" type="text" value="'.$this->data['id_sendermessage'].'" /></td>'."\n";
			echo '<td>- '.$this->SCF_LANG['must_be_textarea'].'</td>';
			echo '</tr>';

			echo '<tr>';
			echo '<td>'.$this->SCF_LANG['ta_params'].'</td>';
			echo '<td><input name="message_ta_params" type="text" value="'.str_replace('"','&quot;',$this->data['message_ta_params']).'" /></td>'."\n";
			echo '</tr>';

			echo '<tr>';
			echo '<td> <input name="msg_sendcopytosender" type="text" value="'.str_replace('"','&quot;', $this->data['msg_sendcopytosender']).'" /></td>';
			echo '<td> <input name="sendcopytosender1" type="checkbox" '.($this->data['sendcopytosender']?'checked="checked"' : '').'/></td>'."\n";
			echo '<td>-</td>'."\n";
			echo '</tr>';

			echo '</tbody></table><br/>';
			echo '<input type="submit" name="save_fields" value="'.$langmessage['save'].'" />'."\n";
	/*end1*/	echo '</form></div><br/>';

	/*begin 2*/	echo '<div onclick="$(this).next(\'div\').toggle()" style="cursor:pointer"><b> 2. </b>'.$this->SCF_LANG['antispam'].'</div>';
			echo '<div style="display:none">';
			echo '<form action="'.\common::GetUrl($title).'" method="post"><br/>'."\n";

			echo '<input id="aspam1" name="aspam" type="radio" value="math" '.($this->data['aspam']=='math'?'checked="checked"':'').'/> <label for="aspam1">'.$this->SCF_LANG['math_pr'].'</label><br/>'."\n";
			echo '<input id="aspam2" name="aspam" type="radio" value="capt" '.($this->data['aspam']=='capt'?'checked="checked"':'').'/> <label for="aspam2">'.$this->SCF_LANG['captcha_pr'].'</label><br/>'."\n";
			echo '<input id="aspam3" name="aspam" type="radio" value="rhcapt" '.($this->data['aspam']=='rhcapt'?'checked="checked"':'').'/> <label for="aspam3">'.$this->SCF_LANG['rhcaptcha_pr'].'</label><br/>'."\n";
			echo '<input id="aspam4" name="aspam" type="radio" value="none" '.($this->data['aspam']=='none'?'checked="checked"':'').'/> <label for="aspam4">'.$langmessage['None'].'</label><br/>'."\n";

			echo '<p>'.$this->SCF_LANG['math_pr'].'</p>';
			echo '<input id="Math_show1" name="Math_show1" type="checkbox" '.($this->data['Math']&1?'checked="checked"':'').'/> <label for="Math_show1"> A + B </label><br/>';
			echo '<input id="Math_show2" name="Math_show2" type="checkbox" '.($this->data['Math']&2?'checked="checked"':'').'/> <label for="Math_show2"> A - B </label><br/>';
			echo '<input id="Math_show4" name="Math_show4" type="checkbox" '.($this->data['Math']&4?'checked="checked"':'').'/> <label for="Math_show4"> A * B </label><br/>';
			echo '<input id="Math_show8" name="Math_show8" type="checkbox" '.($this->data['Math']&8?'checked="checked"':'').'/> <label for="Math_show8"> AbC </label>';
			echo '<input id="msg_enter_letter" name="msg_enter_letter" type="text" value="'.str_replace('"','&quot;', $this->data['msg_enter_letter']).'" /><br/>'."\n";
			echo '<input id="Math_show16" name="Math_show16" type="checkbox" '.($this->data['Math']&16?'checked="checked"':'').'/> <label for="Math_show16"> AACAAAA </label>';
			echo '<input id="msg_enter_unique" name="msg_enter_unique" type="text" value="'.str_replace('"','&quot;', $this->data['msg_enter_unique']).'" /><br/>'."\n";
			echo '<p>'.$this->SCF_LANG['captcha_pr'].'</p>';
			echo $this->SCF_LANG['rc_theme'].': <select name="captcha_rctheme">';
			echo '<option value="red"'. ($this->data['Captcha']['rctheme']=='red' ? 'selected="selected"':'') .'> red </option>';
			echo '<option value="white"'. ($this->data['Captcha']['rctheme']=='white' ? 'selected="selected"':'') .'> white </option>';
			echo '<option value="blackglass"'. ($this->data['Captcha']['rctheme']=='blackglass' ? 'selected="selected"':'') .'> blackglass </option>';
			echo '<option value="clean"'. ($this->data['Captcha']['rctheme']=='clean' ? 'selected="selected"':'') .'> clean </option>';
			echo '</select><br/>';
			echo '<span style="color:green">'.$langmessage['recaptcha_public'].':</span> '.$this->keypublic.'<br/>';
			echo '<span style="color:green">'.$langmessage['recaptcha_private'].':</span> '.$this->keyprivate.'<br/>';
			echo '<span style="color:green">'.$langmessage['recaptcha_language'].':</span> '.\common::ConfigValue('recaptcha_language','').'<br/><br/>';
			echo '<span style="color:green">'.$this->SCF_LANG['green_settings'].' '.\common::Link('Admin_Configuration','&#187;','',' name="admin_box" title="'.$langmessage['configuration'].'"').'</span>';
			echo '<br/><br/>';
			echo '<input type="submit" name="save_antispams" value="'.$langmessage['save'].'" />'."\n";
	/*end2*/	echo '</form></div><br/>';

	/*begin 3*/	echo '<div onclick="$(this).next(\'div\').toggle()" style="cursor:pointer"><b> 3. </b>'.$this->SCF_LANG['email_settings'].'</div>';
			echo '<div style="display:none">';
			echo '<form action="'.\common::GetUrl($title).'" method="post">'."\n";
			echo '<span style="color:green">'.$this->SCF_LANG['will_deliver'].'</span> <input name="Receiver" type="text" value="'.( isset($config["toemail"]) ? str_replace('"','&quot;',$config["toemail"]) : '' ).'" size="25" /> = <input name="ReceiverName" type="text" value="'.( isset($config["toname"]) ? str_replace('"','&quot;',$config["toname"]) : '' ).'" size="25" /> '.\common::Link('Admin_Configuration','&#187;','','name="admin_box" title="'.$langmessage['configuration'].'"').'<br/>'."\n";
			echo $this->SCF_LANG['wordwrap'].' <input name="WordWrap" type="text" value="'.$this->data['WordWrap'].'" /><br/>'."\n";
			echo $this->SCF_LANG['charset'].': <input name="CharSet" type="text" value="'.$this->data['CharSet'].'" /><br/>'."\n";
			echo '<a href="http://php.net/manual/en/features.file-upload.errors.php" target="_blank">'.$this->SCF_LANG['max_filesize'].'</a>: '.ini_get('upload_max_filesize').'B<br/><br/>'."\n";
			echo $langmessage['mail_method'];
			echo ' : <input id="method1" name="method" type="radio" value="smtp" '.($this->data['method']=='smtp'?'checked="checked"':'').'/> <label for="method1">SMTP server</label> '."\n";
			echo '<input id="method2" name="method" type="radio" value="mail" '.($this->data['method']=='mail'?'checked="checked"':'').'/> <label for="method2">function mail()</label> '."\n";
			echo '<input id="method3" name="method" type="radio" value="sendmail" '.($this->data['method']=='sendmail'?'checked="checked"':'').'/> <label for="method3">program sendmail</label> <br/>'."\n";
			echo $this->SCF_LANG['smtp_use_auth'].' <input name="SMTPAuth" type="checkbox" '.($this->data['SMTPAuth']?'checked="checked"' : '').' /><br/>'."\n";
			echo $this->SCF_LANG['smtp_host'].': <input name="Host" type="text" value="'.$this->data['Host'].'" /> '."\n";
			echo '+ '.$this->SCF_LANG['smtp_port'].': <input name="Port" type="text" value="'.$this->data['Port'].'" /><br/>'."\n";
			echo $this->SCF_LANG['smtp_sec'].': <input name="SMTPSecure" type="text" value="'.$this->data['SMTPSecure'].'" /><br/> '."\n";
			echo '<span style="color:green">'.$this->SCF_LANG['smtp_user'].'</span> : <input name="Username" type="text" value="'.(isset($config['smtp_user'])&&$config['smtp_user']!=''?$config['smtp_user']:'-').'" /><br/>'."\n";
			echo '<span style="color:green">'.$this->SCF_LANG['smtp_pass'].'</span> : <input name="Password" type="password" value="'.(isset($config['smtp_pass'])?$config['smtp_pass']:'').'" /><br/>'."\n";
			echo '<span style="color:green">'.$langmessage['sendmail_path'].'</span> : '.(isset($config['sendmail_path'])?$config['sendmail_path']:$langmessage['default']).'<br/>'."\n";
			echo '<div style="clear:both;height:1em;"></div>';
			echo '<input type="submit" name="save_emailsettings" value="'.$langmessage['save'].'" />'."\n";
	/*end3*/	echo '</form></div><br/>';

	/*begin 4*/	echo '<div onclick="$(this).next(\'div\').toggle()" style="cursor:pointer"><b> 4. </b>'.$this->SCF_LANG['other'].'</div>';
			echo '<div style="display:none"><br/>';
			echo '<form action="'.\common::GetUrl($title).'" method="post">'."\n";

			echo '<label for="EnableCKE">'.$this->SCF_LANG['ckeditor_enable'].'</label>';
			echo '<input id="EnableCKE" name="EnableCKE" type="checkbox" '.($this->data['EnableCKE']?'checked="checked"' : '').'/> <br/>'."\n";
			echo '<a onclick="$(\'#ckdiv\').toggle()" style="cursor:pointer">'.$this->SCF_LANG['ckeditor_settings'].'</a>';
			echo ' ( <a href="http://docs.cksource.com/ckeditor_api/symbols/CKEDITOR.config.html" title="CKEditor 3 JavaScript API Documentation" target="_blank">ck info</a> / ';
			echo \common::Link('Admin_scf',$this->SCF_LANG['ckeditor_def'],'cmd=set_defaults').' ) <br/>';
			echo '<div id="ckdiv" style="display:none"><textarea id="ckValues" name="ckValues" rows="11" cols="35" wrap="off" style="width:100%">'.$this->data['ckValues'].'</textarea></div><br/>';

			echo '<a onclick="$(\'#stylediv\').toggle()" style="cursor:pointer">'.$langmessage['style'].'</a> ( ';
			echo \common::Link($title,$this->SCF_LANG['style_restore'],'cmd=style_restore').' ) <br/>'."\n";
			echo '<div id="stylediv" style="display:none">';
			echo '<textarea id="cfstyle" name="cfstyle" wrap="off" rows="15" cols="50" style="width:100%">';
			if( file_exists($addonPathData.'/scf_style.css'))
				echo file_get_contents($addonPathData.'/scf_style.css');
			else
				echo file_get_contents($addonPathCode.'/scf_style.css');//default
			echo '</textarea>';
			echo '</div>';
			echo '<div style="clear:both;height:1em;"></div>';

			echo '<i>'.$this->SCF_LANG['messages'].'</i><br/><br/>';
			echo '<label for="msg_noscript"> '.$this->SCF_LANG['noscript'].': </label>';
			echo '<input id="msg_noscript" name="msg_noscript" type="text" value="'.str_replace('"','&quot;', $this->data['msg_noscript']).'" style="width:97%" /><br/><br/>'."\n";
			echo '<label for="msg_listing"> '.$this->SCF_LANG['listing'].': </label>';
			echo '<input id="msg_listing" name="msg_listing" type="text" value="'.str_replace('"','&quot;', $this->data['msg_listing']).'" style="width:97%" /><br/><br/>'."\n";
			echo '<label for="msg_rcerror"> '.$this->SCF_LANG['rcerror'].': </label>';
			echo '<input id="msg_rcerror" name="msg_rcerror" type="text" value="'.str_replace('"','&quot;', $this->data['msg_rcerror']).'" style="width:97%" /><br/><br/>'."\n";
			echo '<label for="msg_success"> '.$this->SCF_LANG['success'].': </label>';
			echo '<input id="msg_success" name="msg_success" type="text" value="'.str_replace('"','&quot;', $this->data['msg_success']).'" style="width:97%" /><br/><br/>'."\n";
			echo '<label for="msg_fail"> '.$this->SCF_LANG['fail'].': </label>';
			echo '<input id="msg_fail" name="msg_fail" type="text" value="'.str_replace('"','&quot;', $this->data['msg_fail']).'" style="width:97%" /><br/><br/>'."\n";
			echo '<label for="msg_presubject"> '.$this->SCF_LANG['presubject'].': </label>';
			echo '<input id="msg_presubject" name="msg_presubject" type="text" value="'.str_replace('"','&quot;', $this->data['msg_presubject']).'" style="width:97%" /><br/><br/>'."\n";
			echo $this->SCF_LANG['pmlang'].' : <select name="Language">';
			echo '<optgroup label="'.$this->SCF_LANG['pmlang'].'">';
			echo '<option value="ar"'. ($this->data['Language']=='ar' ? 'selected="selected"':'') .'> ar - Arabic </option>';
			echo '<option value="br"'. ($this->data['Language']=='br' ? 'selected="selected"':'') .'> br - Portuguese </option>';
			echo '<option value="ca"'. ($this->data['Language']=='ca' ? 'selected="selected"':'') .'> ca - Catalan </option>';
			echo '<option value="cz"'. ($this->data['Language']=='cz' ? 'selected="selected"':'') .'> cz - Czech </option>';
			echo '<option value="de"'. ($this->data['Language']=='de' ? 'selected="selected"':'') .'> de - German </option>';
			echo '<option value="dk"'. ($this->data['Language']=='dk' ? 'selected="selected"':'') .'> dk - Danish </option>';
			echo '<option value="en"'. ($this->data['Language']=='en' ? 'selected="selected"':'') .'> en - English </option>';
			echo '<option value="es"'. ($this->data['Language']=='es' ? 'selected="selected"':'') .'> es - Spanish </option>';
			echo '<option value="et"'. ($this->data['Language']=='et' ? 'selected="selected"':'') .'> et - Estonian </option>';
			echo '<option value="fi"'. ($this->data['Language']=='fi' ? 'selected="selected"':'') .'> fi - Finnish </option>';
			echo '<option value="fo"'. ($this->data['Language']=='fo' ? 'selected="selected"':'') .'> fo - Faroese </option>';
			echo '<option value="fr"'. ($this->data['Language']=='fr' ? 'selected="selected"':'') .'> fr - French </option>';
			echo '<option value="hu"'. ($this->data['Language']=='hu' ? 'selected="selected"':'') .'> hu - Hungarian </option>';
			echo '<option value="ch"'. ($this->data['Language']=='ch' ? 'selected="selected"':'') .'> ch - Chinese </option>';
			echo '<option value="it"'. ($this->data['Language']=='it' ? 'selected="selected"':'') .'> it - Italian </option>';
			echo '<option value="ja"'. ($this->data['Language']=='ja' ? 'selected="selected"':'') .'> ja - Japanese </option>';
			echo '<option value="nl"'. ($this->data['Language']=='nl' ? 'selected="selected"':'') .'> nl - Dutch </option>';
			echo '<option value="no"'. ($this->data['Language']=='no' ? 'selected="selected"':'') .'> no - Norwegian </option>';
			echo '<option value="pl"'. ($this->data['Language']=='pl' ? 'selected="selected"':'') .'> pl - Polish </option>';
			echo '<option value="ro"'. ($this->data['Language']=='ro' ? 'selected="selected"':'') .'> ro - Romanian </option>';
			echo '<option value="ru"'. ($this->data['Language']=='ru' ? 'selected="selected"':'') .'> ru - Russian </option>';
			echo '<option value="se"'. ($this->data['Language']=='se' ? 'selected="selected"':'') .'> se - Swedish </option>';
			echo '<option value="sk"'. ($this->data['Language']=='sk' ? 'selected="selected"':'') .'> sk - Slovak </option>';
			echo '<option value="tr"'. ($this->data['Language']=='tr' ? 'selected="selected"':'') .'> tr - Turkish </option>';
			echo '<option value="zh"'. ($this->data['Language']=='zh' ? 'selected="selected"':'') .'> zh - Traditional Chinese </option>';
			echo '<option value="zh_cn"'. ($this->data['Language']=='zh_cn' ? 'selected="selected"':'') .'> zh_cn - Simplified Chinese </option>';
			echo '</optgroup>';
			echo '</select><br/><br/>';

			echo $this->SCF_LANG['validator_errors'].' : <select name="validator_errors">';
			echo '<option value="1"'. ($this->data['validator_errors']==1 ? 'selected="selected"':'') .'>'.$this->SCF_LANG['validator_errors1'].'</option>';
			echo '<option value="2"'. ($this->data['validator_errors']==2 ? 'selected="selected"':'') .'>'.$this->SCF_LANG['validator_errors2'].'</option>';
			echo '</select><br/><br/>';

			echo '<input type="submit" name="save_othersettings" value="'.$langmessage['save'].'" /> &nbsp;&nbsp;&nbsp;&nbsp;'."\n";
	/*end4*/	echo '</form></div><br/><br/><br/>';

			echo '<div style="font-size:16px; margin:1.5em 0;">'.$this->SCF_LANG['template'].'</div>'."\n";
			echo '<p> '.\common::Link($title,$this->SCF_LANG['create'],'cmd=create_template').'&nbsp; | &nbsp;';
			echo \common::Link($title,$this->SCF_LANG['view'],'cmd=view_template').'&nbsp; | &nbsp;';
			echo \common::Link($title,$this->SCF_LANG['edita'],'cmd=edit_templatea').'&nbsp; | &nbsp;';
			echo \common::Link($title,$this->SCF_LANG['edite'],'cmd=edit_template').' </p>';

			echo '<div style="font-size:16px; margin:1.5em 0;">'.$this->SCF_LANG['form'].'</div>'."\n";
			echo '<p> '.\common::Link($title,$this->SCF_LANG['create'],'cmd=create_form').'&nbsp; | &nbsp;';
			echo \common::Link('Special_scf',$this->SCF_LANG['test'],'','target="_blank"').'&nbsp; | &nbsp;';
			echo \common::Link($title,$this->SCF_LANG['edita'],'cmd=edit_forma').' </p>';

			echo '<br/><br/><br/>';
		}

		function Select_Languages(){
			global $addonPathCode, $languages, $langmessage;
			$avail=array();
			if( $handle = opendir($addonPathCode.'/language')){
				while (false !== ($file = readdir($handle)))
					if( strpos($file, 'lang_')!==false)
						$avail[] = substr($file,5,-4);
				closedir($handle);
			}
			//uksort($avail,"strnatcasecmp");
			//print_r($avail);
			echo '<select name="iLanguage" onchange="switch_language(this)">';
			echo '<optgroup label="'.$langmessage['language'].'">';
			foreach ($avail as $lang){
				if( !strlen($lang))
					continue;
				$lang1 = isset($languages[$lang])?$languages[$lang]:'*';
				echo '<option value="'.$lang.'"'. ($this->lang==$lang ? 'selected="selected"':'') .'> '.$lang.' - '.$lang1.' </option>';
			}
			echo '</optgroup>';
			echo '</select>';
		}

		function edit_template(){
			global $title, $langmessage, $addonFolderName, $addonPathData;
			if( !file_exists($this->template)){
				message($this->SCF_LANG['template_none']);
				return;
			}
			$a = $_GET['cmd']=='edit_templatea'; //in textarea or in ckeditor
			$text = $this->getfile($this->template,1);

			echo '<p style="font-size:16px; margin-bottom:1.5em;">'.$this->SCF_LANG['template'].' - ';
			echo $a? $this->SCF_LANG['edita']:$this->SCF_LANG['edite'];
			echo '</p>'."\n";
			echo '<form action="'.\common::GetUrl($title).'" method="post">';
			if( $a){
				$text = htmlspecialchars($text);
				echo '<textarea id="textfield" name="textfield" wrap="off" rows="20" cols="50" spellcheck="false" style="width:100%">'.$text.'</textarea>'."\n";
			}else{
				if( file_exists($addonPathData.'/scf_style.css'))
					$css = '/data/_addondata/'.$addonFolderName.'/scf_style.css';//custom
				else
					$css = '/data/_addoncode/'.$addonFolderName.'/scf_style.css';//default
				$options = array('contentsCss'=>\common::GetDir($css));
				//var_export($options);
				includeFile('tool/editing.php');
				\gp_edit::UseCK($text,'textfield',$options);
			}
			echo '<input type="submit" name="save_template" value="'.$langmessage['save'].'" /> <br/>'."\n";
			echo '</form>';
		}

		function edit_form(){ //in textarea

			global $page,$addonRelativeCode,$addonRelativeData,$addonPathData,$title, $langmessage;
			echo '<p style="font-size:16px; margin-bottom:1.5em;">'.$this->SCF_LANG['form'].' - '.$this->SCF_LANG['edita'].'</p>'."\n";
			echo '<form action="'.\common::GetUrl($title).'" method="post">';
			echo '<textarea id="textfield" name="textfield" wrap="off" rows="20" cols="50" spellcheck="false" style="width:100%">';
			echo htmlspecialchars($this->getfile($addonPathData.'/contact_form.php',1));
			echo '</textarea>'."\n";
			echo '<input type="submit" name="save_form" value="'.$langmessage['save'].'" /> <br/>'."\n";
			echo '</form>';
		}

		function save_template($newcontent){
			$begin = $this->getfile($this->template,0);
			if( file_put_contents($this->template, $begin.' ?'.'> '.$newcontent))
				message($this->SCF_LANG['template_saved']);
		}

		function save_form($newcontent){
			global $addonPathData,$config;
			$form_file = $addonPathData.'/contact_form.php';
			$begin = $this->getfile($form_file,0);
			if( file_put_contents($form_file, $begin.' ?'.'> '.$newcontent))
				message($this->SCF_LANG['cf_saved'].' '.$config['toemail'].'. >>  '.\common::Link('Special_scf',\common::GetLabel('Special_scf'),'','target="_blank"').'<br/><br/>');
		}

		function create_template(){
			$t = '<div>'.$this->SCF_LANG['form'].'</div><br/>'."\n";
			$t.= '<form enctype="multipart/form-data" action="" method="post" name="special_contact_form" class="scf">'."\n";
			$t.= ' <fieldset>'."\n";
			//var_export($this->items);
			foreach ($this->items as $i => $value){
				if( $value['type']=='radio')
					$t.= '  <p><b>'.$value['label'].'</b></p>'."\n";
				else
					$t.= '  <label for="item'.$i.'"><b>'.$value['label'].'</b>'."\n";
				$rnr = '    *('.(strpos($value['valid'],'req')===false ? $this->SCF_LANG['recommended']:$this->SCF_LANG['required']).')'."\n";
				if( $value['type']=='textarea' && $i!=$this->data['id_sendermessage'])
					$t.= $rnr;
				switch ($value['type']){
					case 'input':
						$t.= '   <input id="item'.$i.'" name="item'.$i.'" type="text" value="" />'."\n";
					break;
					case 'checkbox':
						$t.= '   <input id="item'.$i.'" name="item'.$i.'" type="checkbox" />'."\n";
					break;
					case 'radio':
						if( $value['multi_values']=='')
							break; //skips wrong field
						$vs = explode(',', $value['multi_values']);
						$first = true;
						foreach ($vs as $j => $str){
							$t.= '   <label for="item'.$i.'_'.$j.'"><b>'.$str.'</b> <input id="item'.$i.'_'.$j.'" name="item'.$i.'" type="radio" value="'.$str.'"'.($first?' checked="checked"':'').' /> </label><br/>'."\n";
							if( $first) $first=false;
						}
					break;
					case 'select':
						$t.= '  <select id="item'.$i.'" name="item'.$i.'">'."\n";
						if( $value['multi_values']!=''){
							$vs = explode(',', $value['multi_values']);
							foreach ($vs as $str){
								$t.= '    <option value="'.$str.'">'.$str.'</option> '."\n";
							}
						}
						$t.= '   </select>'."\n";
					break;
					case 'textarea':
						$t.= '   <textarea id="item'.$i.'" name="item'.$i.'" '.($i==$this->data['id_sendermessage'] ? $this->data['message_ta_params']:'cols="30" rows="5"').'></textarea>'."\n";
					break;
					case 'file':
						$t.= '    ('.$this->SCF_LANG['max_filesize'].': '.ini_get('upload_max_filesize').'B)'."\n";
						$t.= '   <input id="item'.$i.'" name="item'.$i.'" type="file" value="" style="margin-right:90px"/>'."\n";
					break;
				}
				if( $value['type']=='input')
					$t.= $rnr;
				if( $this->data['validator_errors']==2)
					$t .= '    <span class="error_strings" id="special_contact_form_item'.$i.'_errorloc"> </span>';
				if( $value['type']!='radio')
					$t.= '  </label>'."\n";
				//$t .= '<br/>';
			}
			if( $this->data['sendcopytosender']){
				$t.= '  <label for="sendcopytosender">'.$this->data['msg_sendcopytosender']."\n";
				$t.= '   <input id="sendcopytosender" name="sendcopytosender" type="checkbox" /> '."\n";
				$t.= '  </label>'."\n";
			}
			if( $this->data['aspam']=='math'){
				$t.= '  <label for="check"><b>'.$this->SCF_LANG['antispam'].'</b>'."\n";
				$t.= '    <span style="float:left">'.$this->SCF_LANG['enter_result'].' [NUMBERS] : </span>'."\n";
				$t.= '    <input id="check" name="check" type="text" value="" class="scf_input" />'."\n";
				$t.= '  </label>'."\n";
			}

			if( $this->data['aspam']=='capt'){
				$t.= '  <label><b>'.$this->SCF_LANG['antispam'].'</b>'."\n";
				$t.= '   [CAPTCHA]</label><br/>'."\n";
			}
			$t.= '    <input class="scf_submit" name="submitForm" type="submit" value="'.$this->SCF_LANG['send'].'" />'."\n";
			$t.= '    <input id="url" name="url" type="text" value="" style="display:none" />'."\n";
			$t.= '    <input id="website" name="website" type="text" value="" style="display:none" />'."\n";
			if( $this->data['validator_errors']==1){
				$t.= '    <span class="error_strings" id="special_contact_form_errorloc"> </span>'."\n";
			}
			if( $this->data['validator_errors']==2){
				$t.= '    <span class="error_strings" id="special_contact_form_check_errorloc"> </span>'."\n";
			}
			$t.= ' </fieldset>'."\n";
			$t.= '</form>'."\n";
			$str= '<'.'?'.'php defined(\'is_running\') or die(\'Not an entry point...\');
	 ?'.'>'."\n";
			file_put_contents($this->template, $str.$t); // save template
			message($this->SCF_LANG['template_created'].'<br/><br/>');
		}

		function view_template(){
			global $page,$addonPathData,$addonRelativeData,$addonRelativeCode;
			if( !file_exists($this->template)){
				message($this->SCF_LANG['template_none']);
				return;
			}
			if( file_exists($addonPathData.'/scf_style.css'))
				$page->css_user[] = $addonRelativeData.'/scf_style.css';//default
			else
				$page->css_user[] = $addonRelativeCode.'/scf_style.css';//default
			echo $this->SCF_LANG['template_preview'].'<br/><br/>';
			//include($this->template);
			$t = $this->getfile($this->template,1);
			$t = str_replace('type="submit"','type="submit" disabled="disabled"',$t);
			echo $t.'<br/>';
		}

		function save_config(){
			global $addonPathData;
			\gpFiles::SaveArray($addonPathData.'/config.php', 'items', $this->items, 'data', $this->data);
		}

		function save_fields(){
			global $config,$addonPathData;
			$j = 1;
			$this->items = array();
			for ($i=0; $i<=$_POST['maxval']; $i++){
				if( isset($_POST['type'.$i])){
					$this->items[$j]['type'] = $_POST['type'.$i];
					$this->items[$j]['label'] = isset($_POST['label'.$i]) ?  $_POST['label'.$i] : 'label '.$j;
					$valid = isset($_POST['valid'.$i]) ? $_POST['valid'.$i] : '';
					if( $valid!=''){
						$x=array();
						$y=explode(',',$valid);
						foreach($y as $c){
							$condition = trim($c);
							if( $condition=='required')
								$condition='req';
							$x[] = $condition;
						}
						$valid = implode(',',$x);//condensed
					}
					$this->items[$j]['valid'] = $valid;
					$this->items[$j]['multi_values'] = isset($_POST['multi_values'.$i]) ? $_POST['multi_values'.$i] : '';
					if( $_POST['id_sendername']==$i){
						$this->data['id_sendername'] = $j;
						if( $this->items[$j]['type'] != 'input')
							echo $this->SCF_LANG['warn_name'].'<br/>';
					}
					if( $_POST['id_senderemail']==$i){
						$this->data['id_senderemail'] = $j;
						if( $this->items[$j]['type'] != 'input')
							echo $this->SCF_LANG['warn_email'].'<br/>';
					}
					if( $_POST['id_sendersubject']==$i){
						$this->data['id_sendersubject'] = $j;
						if( $this->items[$j]['type'] != 'input' && $this->items[$j]['type'] != 'select')
							echo $this->SCF_LANG['warn_subject'].'<br/>';
					}
					if( $_POST['id_sendermessage']==$i){
						$this->data['id_sendermessage'] = $j;
						if( $this->items[$j]['type'] != 'textarea')
							echo $this->SCF_LANG['warn_message'].'<br/>';
					}
					$j++;
				}
			}
			$this->data['sendcopytosender']= isset($_POST['sendcopytosender1'])  ? true:false;
			$this->data['msg_sendcopytosender']= $_POST['msg_sendcopytosender'];
			$this->data['message_ta_params']= $_POST['message_ta_params'];
			$this->save_config();
			message($this->SCF_LANG['settings_saved'].'<br/>');
		}

		function save_antispams(){
			global $config,$addonPathData;
			$this->data['aspam'] = $_POST['aspam'];
			$this->data['Math'] = 0;
			$this->data['Math'] |= isset($_POST['Math_show1'])  ? 1:0;
			$this->data['Math'] |= isset($_POST['Math_show2'])  ? 2:0;
			$this->data['Math'] |= isset($_POST['Math_show4'])  ? 4:0;
			$this->data['Math'] |= isset($_POST['Math_show8'])  ? 8:0;
			$this->data['Math'] |= isset($_POST['Math_show16'])  ? 16:0;
			$this->data['msg_enter_letter']= $_POST['msg_enter_letter'];
			$this->data['msg_enter_unique']= $_POST['msg_enter_unique'];
			$this->data['Captcha']['rctheme']= $_POST['captcha_rctheme'];
			$this->save_config();
			message($this->SCF_LANG['settings_saved'].'<br/>');
		}

		function save_emailsettings(){
			global $config,$addonPathData;
			if( ($config['toemail']!=$_POST['Receiver']) || ($config['toname']!=$_POST['ReceiverName'])
			   || ($config['smtp_user']!=$_POST['Username']) || ($config['smtp_pass']!=$_POST['Password']) ){
				$config['toemail'] = $_POST['Receiver'];
				$config['toname'] = $_POST['ReceiverName'];
				$config['smtp_user'] = $_POST['Username'];
				$config['smtp_pass'] = $_POST['Password'];
				\admin_tools::SaveConfig();
			}
			$this->data['WordWrap']= 0+$_POST['WordWrap'];
			$this->data['CharSet']= $_POST['CharSet'];
			$this->data['method']= $_POST['method'];
			$this->data['SMTPAuth']= isset($_POST['SMTPAuth']) ? true:false;
			$this->data['Host']= $_POST['Host'];
			$this->data['Port']= 0+$_POST['Port'];
			$_POST['SMTPSecure']= strtolower($_POST['SMTPSecure']);
			if( $_POST['SMTPSecure']=='' || $_POST['SMTPSecure']=='ssl' || $_POST['SMTPSecure']=='tls'){
				$this->data['SMTPSecure']= $_POST['SMTPSecure'];
			}else{
				$this->data['SMTPSecure']= '';
			}
			$this->save_config();
			message($this->SCF_LANG['settings_saved'].'<br/>');
		}

		function save_othersettings(){
			global $config,$addonPathData;
			$this->data['EnableCKE']= isset($_POST['EnableCKE']) ? true:false;
			$this->data['msg_noscript']= $_POST['msg_noscript'];
			$this->data['msg_listing']= $_POST['msg_listing'];
			$this->data['msg_success']= $_POST['msg_success'];
			$this->data['msg_fail']= $_POST['msg_fail'];
			$this->data['msg_presubject']= $_POST['msg_presubject'];
			$this->data['msg_rcerror']= $_POST['msg_rcerror'];
			$this->data['ckValues']= $_POST['ckValues'];
			$this->data['Language']= $_POST['Language']; //phpmailer
			$this->data['validator_errors']= 0+$_POST['validator_errors'];
			$this->save_config();
			file_put_contents($addonPathData.'/scf_style.css',$_POST['cfstyle']);
			message($this->SCF_LANG['settings_saved'].'<br/>');
		}

		function Start(){
			global $addonPathData;

			if( isset($_POST['save_fields']) ){ //settings 1
				$this->save_fields();

			}elseif( isset($_POST['save_antispams']) ){ //settings 2

				$this->save_antispams();
			}elseif( isset($_POST['save_emailsettings'])){ //settings 3

				$this->save_emailsettings();
			}elseif( isset($_POST['save_othersettings'])){ //settings 4

				$this->save_othersettings();
			}

			if( isset($_POST['save_template'])){
				$this->save_template($_POST['textfield']);
			}
			if( isset($_POST['save_form'])){
				$this->save_form($_POST['textfield']);
			}

			$cmd = \common::GetCommand();
			switch($cmd){
				case 'set_defaults':
				$this->data['ckValues'] = ckDefault;
				$this->save_config();
				message($this->SCF_LANG['ckeditor_default'].'<br/><br/>');
				break;

				case 'create_template':
				$this->create_template();
				break;

				case 'create_form':
				$this->create_form();
				break;

				case 'edit_templatea':
				case 'edit_template':
				$this->edit_template();
				break;

				case 'edit_forma';
				$this->edit_form();
				break;

				case 'view_template':
				$this->view_template();
				break;

				case 'style_restore':
				if( file_exists($addonPathData.'/scf_style.css') ){
					unlink($addonPathData.'/scf_style.css');
					message($this->SCF_LANG['style_restored']);
				}
			}
			$this->menu();
		}
	}

}

