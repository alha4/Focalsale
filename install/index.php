<?

global $MESS;

$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang)-strlen("/install/index.php"));
include(GetLangFileName($strPath2Lang."/lang/", "/install/index.php"));

IncludeModuleLangFile($PathInstall."/index.php");

class alfa4_chinavasion extends CModule {
 
  public $MODULE_ID = "alfa4.chinavasion";
  public $MODULE_NAME;
  public $MODULE_DESCRIPTION;

  function alfa4_chinavasion() {

     $this->MODULE_NAME = GetMessage("MOD_NAME");
     $this->MODULE_DESCRIPTION = GetMessage("MOD_DESC");
     $this->PARTNER_NAME = GetMessage("SPER_PARTNER");
     $this->PARTNER_URI =  GetMessage("PARTNER_URI");
     $this->MODULE_VERSION = '1.0.0';
     //$this->MODULE_VERSION_DATE = '07.11.2015';

  }
  function DoInstall() {
        RegisterModule("alfa4.chinavasion");
	return true;        
  }	 
  function DoUninstall()  { 
       return true;
   }
 }
?>