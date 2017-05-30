<?php    
//## NextScripts App.net Connection Class
$nxs_snapAvNts[] = array('code'=>'AP', 'lcode'=>'ap', 'name'=>'App.net (!)', 'type'=>'Social Networks');

if (!class_exists("nxs_snapClassAP")) { class nxs_snapClassAP extends nxs_snapClassNT { 
  var $ntInfo = array('code'=>'AP', 'lcode'=>'ap', 'name'=>'App.net (!)', 'defNName'=>'', 'tstReq' => true, 'instrURL'=>'http://www.nextscripts.com/setup-installation/app-net-social-networks-auto-poster-wordpress/');      
  //#### Update
  function toLatestVer($ntOpts){ if( !empty($ntOpts['v'])) $v = $ntOpts['v']; else $v = 340; $ntOptsOut = '';  switch ($v) {
      case 340: $ntOptsOut = $this->toLatestVerNTGen($ntOpts); $ntOptsOut['do'] = $ntOpts['do'.$this->ntInfo['code']]; $ntOptsOut['nName'] = $ntOpts['nName'];  $ntOptsOut['attchImg'] = $ntOpts['attchImg'];
        $ntOptsOut['msgFormat'] = $ntOpts['apTextFormat']; $ntOptsOut['appKey'] = $ntOpts['appID'];  $ntOptsOut['appSec'] = $ntOpts['appSec']; 
        $ntOptsOut['accessToken'] = $ntOpts['apAppAuthToken']; $ntOptsOut['authUserID'] = $ntOpts['appAppUserID']; $ntOptsOut['authUserName'] = $ntOpts['appAppUserName']; $ntOptsOut['isUpdd'] = '1'; $ntOptsOut['v'] = NXS_SETV;
      break;
    }
    return !empty($ntOptsOut)?$ntOptsOut:$ntOpts; 
  }   
  //#### Show Common Settings
  function showGenNTSettings($ntOpts){ $this->nt = $ntOpts;  $this->showNTGroup(); }  
  //#### Show NEW Settings Page
  function showNewNTSettings($ii){ $defO = array('nName'=>'', 'do'=>'1', 'appKey'=>'', 'appSec'=>'', 'attchImg'=>1, 'msgFormat'=>"New post (%TITLE%) has been published on %SITENAME% - %URL%", 'imgSize'=>'original'); $this->showGNewNTSettings($ii, $defO); }
  //#### Show Unit  Settings  
  function checkIfSetupFinished($options) { return !empty($options['authUserID']) && !empty($options['accessToken']); }
  public function doAuth() { $ntInfo = $this->ntInfo; global $nxs_snapSetPgURL;     
    if ( !empty($_GET['code']) && (!empty($_GET['state']) && stripos($_GET['state'], $ntInfo['lcode'].'-')!==false) ){ $at = $_GET['code']; $to = explode('-', $_GET['state']); $_GET['acc'] = $to[1];
     echo "-= This is normal technical authorization info that will dissapear (Unless you get some errors) =- <br/><br/><br/>";
     if (isset($_GET['acc'])) $options = $this->nt[$_GET['acc']]; $wprg = array(); $response = nxs_remote_get('https://graph.facebook.com/nextscripts', $wprg);
     echo $nxs_snapSetPgURL.'&auth=ap&acc='.$_GET['acc']."||"; if(stripos($nxs_snapSetPgURL, 'page=NextScripts_SNAP.php')===false) { $newURL = explode('?', $nxs_snapSetPgURL); $nxs_snapSetPgURL = $newURL[0]; }
     if( is_nxs_error( $response) && isset($response->errors['http_request_failed']) && stripos($response->errors['http_request_failed'][0], 'SSL')!==false ) {  prr($response->errors); $wprg['sslverify'] = false; }
     if (isset($options['appKey'])){ echo "-="; prr($options);
       $wprg['body'] = array('client_id'=>$options['appKey'], 'client_secret'=>$options['appSec'], 'grant_type'=>'authorization_code', 'redirect_uri'=>$nxs_snapSetPgURL, 'state'=>'ap-'.$_GET['acc'], 'code'=>$at); prr($wprg);  
       $response = nxs_remote_post('https://account.app.net/oauth/access_token', $wprg); 
       if ( (is_object($response) && (isset($response->errors))) || (is_array($response) && stripos($response['body'],'"error":')!==false )) { prr($response); die(); }  
       $params = json_decode($response['body'], true);  $options['accessToken'] = $params['access_token']; if ($params['user_id']>0) { $options['authUserID'] = $params['user_id']; $options['authUserName'] = $params['username'];  }
       if ($params['user_id']>0) { nxs_save_glbNtwrks($ntInfo['lcode'],$_GET['acc'],$options,'*'); ?><script type="text/javascript">window.location = "<?php echo $nxs_snapSetPgURL; ?>"</script>      
       <?php } die(); }
    }  
  }    
  
  function accTab($ii, $options, $isNew=false){ global $nxs_snapSetPgURL; $ntInfo = $this->ntInfo; $nt = $ntInfo['lcode']; ?>
  <div style="color:red;padding:5px;margin:5px; border: 1px solid darkred;">[January 2017] App.net is shutting down on March 15, 2017. Please see the  <a target="_blank" href="http://blog.app.net/2017/01/12/app-net-is-shutting-down/">blog post</a> for more information.<br/></div>
  <?php $this->elemKeySecret($ii,'Client ID','Client Secret', $options['appKey'], $options['appSec'],'appKey','appSec','https://account.app.net/developer/apps/'); ?>
    <br/><?php $this->elemMsgFormat($ii,'Message Format','msgFormat',$options['msgFormat']); ?>
    
    <div style="margin: 0px;"><input value="1" type="checkbox" name="<?php echo $nt; ?>[<?php echo $ii; ?>][attchImg]"  <?php if ((int)$options['attchImg'] == 1) echo "checked"; ?> /> <strong><?php _e('Attach Image to the Post', 'social-networks-auto-poster-facebook-twitter-g'); ?></strong></div>
    
    <br/><br/>
    <?php  if($options['appKey']=='') { ?>
      <b><?php _e('Authorize Your '.$ntInfo['name'].' Account', 'social-networks-auto-poster-facebook-twitter-g'); ?></b> <?php _e('Please click "Update Settings" to be able to Authorize your account.', 'social-networks-auto-poster-facebook-twitter-g');  
    } else { if(isset($options['authUserID']) && $options['authUserID']>0) { 
      _e('Your '.$ntInfo['name'].' Account has been authorized.', 'social-networks-auto-poster-facebook-twitter-g'); ?> User ID: <?php _e(apply_filters('format_to_edit', htmlentities($options['authUserID'].' - '.$options['authUserName'], ENT_COMPAT, "UTF-8")), 'social-networks-auto-poster-facebook-twitter-g') ?>.
      <?php _e('You can', 'social-networks-auto-poster-facebook-twitter-g'); ?> Re- <?php } ?>            
      <a href="https://account.app.net/oauth/authenticate?client_id=<?php echo trim($options['appKey']);?>&response_type=code&redirect_uri=<?php echo trim(urlencode($nxs_snapSetPgURL).'&state='.$nt.'-'.$ii.'&scope=stream+write_post+follow+messages+update_profile+files');?>">Authorize Your <?php echo $ntInfo['name']; ?> Account</a>       
      <?php if (!isset($options['authUserID']) || $options['authUserID']<1) { ?> <div class="blnkg">&lt;=== <?php _e('Authorize your account', 'social-networks-auto-poster-facebook-twitter-g'); ?> ===</div> <?php } 
    } ?><br/><br/> <?php
  }
  function advTab($ii, $options){}
  //#### Set Unit Settings from POST
  function setNTSettings($post, $options){ 
    foreach ($post as $ii => $pval){       
      if (!empty($pval['appKey']) && !empty($pval['appKey'])){ if (!isset($options[$ii])) $options[$ii] = array(); $options[$ii] = $this->saveCommonNTSettings($pval,$options[$ii]);
        //## Uniqe Items        
        if (isset($pval['attchImg'])) $options[$ii]['attchImg'] = trim($pval['attchImg']); else $options[$ii]['attchImg'] = 0;                               
      } elseif ( count($pval)==1 ) if (isset($pval['do'])) $options[$ii]['do'] = $pval['do']; else $options[$ii]['do'] = 0; 
    } return $options;
  }
    
  //#### Show Post->Edit Meta Box Settings
  
  function showEdPostNTSettings($ntOpts, $post){ $post_id = $post->ID; $nt = $this->ntInfo['lcode']; $ntU = $this->ntInfo['code'];
      foreach($ntOpts as $ii=>$ntOpt)  { $isFin = $this->checkIfSetupFinished($ntOpt); if (!$isFin) continue; 
        $pMeta = maybe_unserialize(get_post_meta($post_id, 'snap'.$ntU, true)); if (is_array($pMeta) && !empty($pMeta[$ii])) $ntOpt = $this->adjMetaOpt($ntOpt, $pMeta[$ii]);         
        
        if (empty($ntOpt['imgToUse'])) $ntOpt['imgToUse'] = ''; if (empty($ntOpt['urlToUse'])) $ntOpt['urlToUse'] = ''; $postType = isset($ntOpt['postType'])?$ntOpt['postType']:'';
        $msgFormat = !empty($ntOpt['msgFormat'])?htmlentities($ntOpt['msgFormat'], ENT_COMPAT, "UTF-8"):''; $msgTFormat = !empty($ntOpt['msgTFormat'])?htmlentities($ntOpt['msgTFormat'], ENT_COMPAT, "UTF-8"):'';
        $imgToUse = $ntOpt['imgToUse'];  $urlToUse = $ntOpt['urlToUse']; $ntOpt['ii']=$ii;
        
        $this->nxs_tmpltAddPostMeta($post, $ntOpt, $pMeta); 
        
          $this->elemEdMsgFormat($ii, __('Message Format:', 'social-networks-auto-poster-facebook-twitter-g'),$msgFormat);  
          ?>
          <tr><td>&nbsp;</td><td><div style="margin: 0px;"><input value="0" type="hidden" name="<?php echo $nt; ?>[<?php echo $ii; ?>][attchImg]"/>
          <input value="1" type="checkbox" name="<?php echo $nt; ?>[<?php echo $ii; ?>][attchImg]"  <?php if ((int)$ntOpt['attchImg'] == 1) echo "checked"; ?> /> <strong><?php _e('Attach Image to the Post', 'social-networks-auto-poster-facebook-twitter-g'); ?></strong></div></td></tr>          <?php
          nxs_showImgToUseDlg($nt, $ii, $imgToUse);        
    
       /* ## Select Image & URL ## */  nxs_showURLToUseDlg($nt, $ii, $urlToUse); $this->nxs_tmpltAddPostMetaEnd($ii);     
     }
  }
  
  //#### Save Meta Tags to the Post
  function adjMetaOpt($optMt, $pMeta){ $optMt = $this->adjMetaOptG($optMt, $pMeta);     
    if (!empty($pMeta['attchImg'])) $optMt['attchImg'] = $pMeta['attchImg']; else $optMt['attchImg'] = 0;          
    return $optMt;
  }
  
  function adjPublishWP(&$options, &$message, $postID){ 
    if (!empty($postID)) { if (trim($options['imgToUse'])!='') $imgURL = $options['imgToUse']; else $imgURL = nxs_getPostImage($postID, !empty($options['wpImgSize'])?$options['wpImgSize']:'full');
      if (preg_match("/noImg.\.png/i", $imgURL)) { $imgURL = ''; $isNoImg = true; }
      $message['imageURL'] = $imgURL;
    }
  }   
  
}}

if (!function_exists("nxs_doPublishToAP")) { function nxs_doPublishToAP($postID, $options){ if (!is_array($options)) $options = maybe_unserialize(get_post_meta($postID, $options, true)); $cl = new nxs_snapClassAP(); $cl->nt[$options['ii']] = $options; return $cl->publishWP($options['ii'], $postID); }} 

?>